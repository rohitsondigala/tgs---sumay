<?php

namespace App\Services;

use App\Mail\ForgotPasswordOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthService
{

    function postLogin($request)
    {
        $email = $request->email;
        $password = $request->password;
        $loginDetails = ['email' => $email, 'password' => $password];
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            if (Auth::attempt($loginDetails)) {
                if (Hash::check($request->password, $userDetail->password)) {
                    return ['success' => true, 'message' => trans('auth.login')];
                } else {
                    return ['success' => false, 'message' => trans('auth.password')];
                }
            } else {
                return ['success' => false, 'message' => trans('auth.failed')];
            }
        } else {
            return ['success' => false, 'message' => trans('auth.email_not_found')];
        }
    }

    public function postForgotPassword($request)
    {
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            $user_uuid = $userDetail->uuid;
            $otp = rand(111111, 999999);

            $forgotOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );

            if (forgot_password()->where('user_uuid', $user_uuid)->where('status', 0)->count() > 0) {
                $forgotPasswordDetail = forgot_password()->where('user_uuid', $user_uuid)->first();
                $currentDate = Carbon::parse($forgotPasswordDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    forgot_password()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($forgotPasswordDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('auth.maximum_attempt')];
                } else {

                    Mail::to($email)->send(new ForgotPasswordOtp($forgotPasswordDetail));
                    session(['user_uuid' => $user_uuid]);
                    forgot_password()->where('user_uuid', $user_uuid)->increment('attempt');
                    return ['success' => true, 'message' => trans('auth.forgot_password')];
                }
            } else {
                $forgotPasswordDetail = forgot_password()->create($forgotOtpArray);
                if ($forgotPasswordDetail) {
                    Mail::to($email)->send(new ForgotPasswordOtp($forgotPasswordDetail));
                    session(['user_uuid' => $user_uuid]);
                    return ['success' => true, 'message' => trans('auth.forgot_password')];
                } else {
                    return ['success' => false, 'message' => trans('auth.server_issue')];
                }
            }


        } else {
            return ['success' => false, 'message' => trans('auth.email_not_found')];
        }
    }

    public function verifyOTP($request)
    {
        $user_uuid = Session::get('user_uuid');
        $otp = $request->otp;
        if (forgot_password()->where('user_uuid', $user_uuid)->where('otp', $otp)->count() > 0) {
            $update = forgot_password()->where('user_uuid', $user_uuid)->where('otp', $otp)->update(['status' => 1]);
            if ($update) {
                return ['success' => true, 'message' => trans('auth.otp_verified')];
            } else {
                return ['success' => false, 'message' => trans('auth.server_issue')];
            }
        } else {
            return ['success' => false, 'message' => trans('auth.incorrect_otp')];
        }
    }

    public function changePassword($request)
    {
        $user_uuid = Session::get('user_uuid');
        if (forgot_password()->where('user_uuid', $user_uuid)->where('status', 1)->count() > 0) {
            $password = $request->password;
            $confirm_password = $request->confirm_password;
            if ($password == $confirm_password) {
                if (user()->where('uuid', $user_uuid)->update(['password' => bcrypt($password)])) {
                    $user = user()->where('uuid', $user_uuid)->first();
                    forgot_password()->where('user_uuid', $user_uuid)->delete();
                    \session()->flush();
                    \auth()->login($user);
                    return ['success' => true, 'message' => trans('auth.password_updated')];
                } else {
                    return ['success' => false, 'message' => trans('auth.server_issue')];
                }
            } else {
                return ['success' => false, 'message' => trans('auth.password_not_match')];
            }
        } else {
            return ['success' => false, 'message' => trans('auth.server_issue')];
        }
    }

    public function resendOTP()
    {
        $user_uuid = Session::get('user_uuid');
        if ($user_uuid) {
            $forgotPasswordDetail = forgot_password()->where('user_uuid', $user_uuid)->first();
            $currentDate = Carbon::parse($forgotPasswordDetail->updated_at)->format('d-m-y');
            $todayDate = Carbon::now()->format('d-m-y');
            if ($currentDate != $todayDate) {
                forgot_password()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
            }
            if ($forgotPasswordDetail->attempt >= 3 && $currentDate == $todayDate) {
                return ['success' => false, 'message' => trans('auth.maximum_attempt')];
            }else{
                $forgotPasswordDetail->increment('attempt');
                Mail::to($forgotPasswordDetail->user->email)->send(new ForgotPasswordOtp($forgotPasswordDetail));
                return ['success' => true, 'message' => trans('auth.resend_otp')];
            }
        } else {
            return ['success' => false, 'message' => trans('auth.server_issue')];
        }
    }

    public function moderatorChangePassword($request)
    {
        $user_uuid = auth()->user()->uuid;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        if ($password == $confirm_password) {
            if (user()->where('uuid', $user_uuid)->update(['password' => bcrypt($password), 'password_generated' => 1])) {
                $user = user()->where('uuid', $user_uuid)->first();
                \auth()->login($user);
                return ['success' => true, 'message' => trans('auth.password_updated')];
            } else {
                return ['success' => false, 'message' => trans('auth.server_issue')];
            }
        } else {
            return ['success' => false, 'message' => trans('auth.password_not_match')];
        }
    }
}
