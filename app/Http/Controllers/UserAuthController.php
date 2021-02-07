<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthChangePasswordRequest;
use App\Http\Requests\UserAuthForgotPasswordOtpRequest;
use App\Http\Requests\UserAuthForgotPasswordRequest;
use App\Http\Requests\UserAuthPostLoginRequest;
use App\Services\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * UserAuthController constructor.
     * @param AuthService $authService
     */
    function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function login(){
        return redirect('');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function index(){
        if(user_role() == 'ADMIN'){
            return redirect('/admin/dashboard');
        }elseif(user_role() == 'MODERATOR') {
            return redirect('/moderator/dashboard');
        }else{
            return redirect('/logout');
        }
    }

    /**
     * @param UserAuthPostLoginRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function postLogin(UserAuthPostLoginRequest $request)
    {
        $login = $this->authService->postLogin($request);
        if($login['success']){
            return redirect('/index')->with(['message'=>$login['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$login['message'],'class'=>'alert-danger'])->withInput();
        }
    }

    /**
     *
     */
    public function postRegister()
    {

    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function forgotPassword(){
        return view('auth.forgot-password');
    }

    public function postForgotPassword(UserAuthForgotPasswordRequest  $request){
        $forgot = $this->authService->postForgotPassword($request);
        if($forgot['success']){
            return redirect('/verify-otp')->with(['message'=>$forgot['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$forgot['message'],'class'=>'alert-danger']);
        }
    }

    public function verifyOTP(){
        if(session()->get('user_uuid')){
            if(forgot_password()->where('user_uuid',session()->get('user_uuid'))->count() > 0){
                return view('auth.verify-otp');
            }else{
                return redirect('/');
            }
        }else{
            return redirect('/');
        }
    }

    public function postVerifyOTP(UserAuthForgotPasswordOtpRequest $request){
        $return = $this->authService->verifyOTP($request);
        if($return ['success']){
            return redirect('/change-password')->with(['message'=>$return['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$return ['message'],'class'=>'alert-danger']);
        }
    }

    public function changePassword(){
        if(session()->get('user_uuid')){
                return view('auth.change-password');
        }else{
            return redirect('/');
        }
    }

    public function postChangePassword(UserAuthChangePasswordRequest $request){
        $return = $this->authService->changePassword($request);
        if($return ['success']){
            return redirect('/index')->with(['message'=>$return['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$return ['message'],'class'=>'alert-danger']);
        }
    }

    public function resendOtp(){
        $return = $this->authService->resendOTP();
        if($return ['success']){
            return redirect('/verify-otp')->with(['message'=>$return['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$return ['message'],'class'=>'alert-danger']);
        }
    }

}
