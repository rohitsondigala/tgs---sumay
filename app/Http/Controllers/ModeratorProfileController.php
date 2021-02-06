<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\AdminProfileRequest;
use App\Services\CustomService;
use Illuminate\Http\Request;

class ModeratorProfileController extends Controller
{
    public $customService;
    /**
     * @param CustomService $customService
     */
    public function __construct(CustomService $customService){
        $this->customService = $customService;
        generateLog('Stream table activity');
    }
    public function profile(){
        $user = user();
        $countries = country()->orderBy('name','ASC')->pluck('name','id')->prepend('Select Country','');
        $states = state()->where('country_id',auth()->user()->country)->orderBy('name','ASC')->pluck('name','id')->prepend('Select State','');
        $cities = city()->where('state_id',auth()->user()->state)->orderBy('name','ASC')->pluck('name','id')->prepend('Select City','');

        $pageTitle = "My Profile";
        return view('moderator.profile',compact('pageTitle','user','countries','states','cities'));
    }

    public function postProfile(AdminProfileRequest $request){
        $store = $this->customService->saveProfile($request);
        if($store['success']){
            return redirect('/moderator/profile')->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }
    public function userChangePassword(){
        $user = user();
        $pageTitle = "Change Password";
        return view('moderator.user-change-password',compact('pageTitle','user'));
    }

    public function userPostChangePassword(ChangePasswordRequest $request){
        $store = $this->customService->changePassword($request);
        if($store['success']){
            return redirect('/moderator/user-change-password')->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }
}
