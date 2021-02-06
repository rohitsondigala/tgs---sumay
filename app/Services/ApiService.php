<?php

namespace App\Services;


use App\Mail\ForgotPasswordOtp;
use App\Mail\ProfessorRegistraionOtp;
use App\Mail\StudentRegistraionOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class ApiService
 * @package App\Services
 */
class ApiService
{
    /**
     * @param $validate
     * @return array
     */
    function validationResponse($validate)
    {
        $errors = json_decode($validate->errors());
        if (!empty($errors)) {
            foreach ($errors as $list) {
                if ($validate->fails()) {
                    return [
                        'success' => false,
                        'message' => $list[0],
                        'data' => array()
                    ];
                } else {
                    return [
                        'success' => true,
                        'message' => $list[0],
                        'data' => array()
                    ];
                }
            }
        } else {
            return [
                'success' => true,
                'message' => 'No Errors',
                'data' => array()
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function studentRegisterValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'password' => 'required',
            'university_name' => 'required',
            'college_name' => 'required',
            'preferred_language' => 'required',
            'other_information' => 'required',
            'stream_uuid' => 'required',
            'subjects' => 'array|required|max:3',
            'access_token' => 'required'
        ]);
        return $this->validationResponse($validate);

    }

    /**
     * @param $request
     * @return array
     */
    function studentRegistration($request)
    {
        if (user()->where('email', $request->email)->count() > 0) {
            $user = user()->where('email', $request->email)->first();
            if($user->verify){
                return ['success' => false, 'message' => trans('api.email_exists'), 'data' => array()];
            }
            if (user_otp()->where('user_uuid', $user->uuid)->where('status', 1)->count() > 0) {
                return ['success' => false, 'message' => trans('api.email_exists'), 'data' => array()];
            } else {
                user_otp()->where('user_uuid', $user->uuid)->delete();
                student_subjects()->where('user_uuid', $user->uuid)->delete();
                student_details()->where('user_uuid', $user->uuid)->delete();
                return $this->studentCreate($user, $request);
            }
        } else {
            $userArray = $request->only('name', 'email', 'password', 'mobile', 'country', 'state', 'city');
            $userArray['role_uuid'] = student_role_uuid();
            $user = user()->create($userArray);
            return $this->studentCreate($user, $request);
        }

    }

    /**
     * @param $user
     * @param $request
     * @return array
     */
    function studentCreate($user, $request)
    {
        $studentDetails = $request->only('university_name', 'college_name', 'other_information', 'preferred_language');
        $subjects = $request->only('subjects');
        if ($user) {
            $user_uuid = $user->uuid;
            $studentDetails['user_uuid'] = $user_uuid;
            $otp = rand(111111, 999999);
            $userOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );
            foreach ($subjects as $subject_uuid) {
                foreach ($subject_uuid as $list) {
                    student_subjects()->create(['user_uuid' => $user_uuid, 'stream_uuid' => $request->stream_uuid, 'subject_uuid' => $list]);
                }
            }
            student_details()->create($studentDetails);
            user_otp()->create($userOtpArray);
            $user = user_otp()->where('user_uuid', $user_uuid)->first();
            Mail::to($request->email)->send(new StudentRegistraionOtp($user));
            return ['success' => true, 'message' => trans('api.user_registration_otp_sent'), 'data' => array()];
        } else {
            return ['success' => false, 'message' => trans('api.fail'), 'data' => array()];
        }
    }


    function professorRegisterValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'password' => 'required',
            'university_name' => 'required',
            'education_qualification' => 'required',
            'research_of_expertise' => 'required',
            'achievements' => 'required',
            'college_name' => 'required',
            'preferred_language' => 'required',
            'other_information' => 'required',
            'stream_uuid' => 'required',
            'subjects' => 'array|required|max:3',
            'access_token' => 'required'
        ]);
        return $this->validationResponse($validate);

    }

    function professorRegistration($request)
    {
        if (user()->where('email', $request->email)->count() > 0) {
            $user = user()->where('email', $request->email)->first();
            if($user->verify){
                return ['success' => false, 'message' => trans('api.email_exists'), 'data' => array()];
            }
            if (user_otp()->where('user_uuid', $user->uuid)->where('status', 1)->count() > 0) {
                return ['success' => false, 'message' => trans('api.email_exists'), 'data' => array()];
            } else {
                user_otp()->where('user_uuid', $user->uuid)->delete();
                professor_subjects()->where('user_uuid', $user->uuid)->delete();
                professor_details()->where('user_uuid', $user->uuid)->delete();
                return $this->professorCreate($user, $request);
            }
        } else {
            $userArray = $request->only('name', 'email', 'password', 'mobile', 'country', 'state', 'city');
            $userArray['role_uuid'] = professor_role_uuid();
            $user = user()->create($userArray);
            return $this->professorCreate($user, $request);
        }

    }

    function professorCreate($user, $request)
    {
        $professorDetails = $request->only('education_qualification','research_of_expertise','achievements','university_name', 'college_name', 'other_information', 'preferred_language');
        $subjects = $request->only('subjects');
        if ($user) {
            $user_uuid = $user->uuid;
            $professorDetails['user_uuid'] = $user_uuid;
            $otp = rand(111111, 999999);
            $userOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );
            foreach ($subjects as $subject_uuid) {
                foreach ($subject_uuid as $list) {
                    professor_subjects()->create(['user_uuid' => $user_uuid, 'stream_uuid' => $request->stream_uuid, 'subject_uuid' => $list]);
                }
            }
            professor_details()->create($professorDetails);
            user_otp()->create($userOtpArray);
            $user = user_otp()->where('user_uuid', $user_uuid)->first();
            Mail::to($request->email)->send(new ProfessorRegistraionOtp($user));
            return ['success' => true, 'message' => trans('api.user_registration_otp_sent'), 'data' => array()];
        } else {
            return ['success' => false, 'message' => trans('api.fail'), 'data' => array()];
        }
    }


    /**
     * @param $request
     * @return array
     */
    function verifyEmailValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function verifyEmail($request)
    {
        $email = $request->email;
        $otp = $request->otp;
        if (user()->where('email', $request->email)->count() > 0) {
            $user_uuid = user()->where('email', $email)->value('uuid');
            if (user_otp()->where('user_uuid', $user_uuid)->where('status', 1)->count() > 0) {
                return ['success' => false, 'message' => trans('api.already_verified')];
            }
            if (user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->count() > 0) {
                $update = user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->update(['status' => 1]);
                if ($update) {
                    user()->where('uuid',$user_uuid)->update(['verify'=>1]);
                    user_otp()->where('user_uuid', $user_uuid)->delete();
                    return ['success' => true, 'message' => trans('api.otp_verified'), 'data' => array()];
                } else {
                    return ['success' => false, 'message' => trans('api.server_issue'), 'data' => array()];
                }
            } else {
                return ['success' => false, 'message' => trans('api.incorrect_otp'), 'data' => array()];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found'), 'data' => array()];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function resendOtpValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function resendOtp($request)
    {
        $email = $request->email;
        if (user()->where('email', $request->email)->count() > 0) {
            $user_uuid = user()->where('email', $email)->value('uuid');
            if ($user_uuid) {
                if (user_otp()->where('user_uuid', $user_uuid)->where('status', 1)->count() > 0) {
                    return ['success' => false, 'message' => trans('api.already_verified'), 'data' => array()];
                }
                $userOtpDetail = user_otp()->where('user_uuid', $user_uuid)->first();
                $currentDate = Carbon::parse($userOtpDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    user_otp()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($userOtpDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('api.maximum_attempt'), 'data' => array()];
                } else {
                    $userOtpDetail->increment('attempt');
                    if ($request->type == 'forgot-password') {
                        Mail::to($userOtpDetail->user->email)->send(new ForgotPasswordOtp($userOtpDetail));
                    } else {
                        if ($request->type == 'student') {
                            Mail::to($userOtpDetail->user->email)->send(new StudentRegistraionOtp($userOtpDetail));
                        }else{
                            Mail::to($userOtpDetail->user->email)->send(new ProfessorRegistraionOtp($userOtpDetail));
                        }
                    }
                    return ['success' => true, 'message' => trans('api.resend_otp'), 'data' => array()];
                }
            } else {
                return ['success' => false, 'message' => trans('api.server_issue'), 'data' => array()];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found'), 'data' => array()];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function forgotPasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function forgotPassword($request)
    {
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            $user_uuid = $userDetail->uuid;
            $otp = rand(111111, 999999);

            $userOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );
            if (user_otp()->where('user_uuid', $user_uuid)->where('status', 0)->count() > 0) {
                $userOtpDetail = user_otp()->where('user_uuid', $user_uuid)->where('status', 0)->first();
                $currentDate = Carbon::parse($userOtpDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    user_otp()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($userOtpDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('auth.maximum_attempt'), 'data' => array()];
                } else {

                    Mail::to($userOtpDetail->user->email)->send(new ForgotPasswordOtp($userOtpDetail));
                    user_otp()->where('user_uuid', $user_uuid)->increment('attempt');
                    return ['success' => true, 'message' => trans('auth.forgot_password'), 'data' => array()];
                }
            } else {
                $userOtpDetail = user_otp()->create($userOtpArray);
                if ($userOtpDetail) {
                    Mail::to($userOtpDetail->user->email)->send(new ForgotPasswordOtp($userOtpDetail));
                    return ['success' => true, 'message' => trans('auth.forgot_password'), 'data' => array()];
                } else {
                    return ['success' => false, 'message' => trans('auth.server_issue'), 'data' => array()];
                }
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found'), 'data' => array()];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function verifyForgotPasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function verifyForgotPassword($request)
    {
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            $user_uuid = $userDetail->uuid;
            $otp = $request->otp;
            if (user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->count() > 0) {
                $update = user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->update(['status' => 1]);
                if ($update) {
                    user_otp()->where('user_uuid', $user_uuid)->delete();
                    return ['success' => true, 'message' => trans('api.otp_verified'), 'data' => array()];
                } else {
                    return ['success' => false, 'message' => trans('api.server_issue'), 'data' => array()];
                }
            } else {
                return ['success' => false, 'message' => trans('api.incorrect_otp'), 'data' => array()];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found'), 'data' => array()];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function changePasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function changePassword($request)
    {
        $email = $request->email;
        $password = $request->password;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            $user_uuid = $userDetail->uuid;
            if(user_otp()->where('user_uuid',$user_uuid)->count() <= 0) {
                if (user()->where('email', $email)->update(['password' => bcrypt($password)])) {
                    return ['success' => true, 'message' => trans('api.password_updated'), 'data' => array()];
                } else {
                    return ['success' => false, 'message' => trans('api.server_issue'), 'data' => array()];
                }
            }else{
                return ['success' => false, 'message' => trans('api.otp_not_verified'), 'data' => array()];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found'), 'data' => array()];
        }
    }


}
