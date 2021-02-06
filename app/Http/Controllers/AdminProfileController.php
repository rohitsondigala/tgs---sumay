<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\AdminProfileRequest;
use App\Http\Requests\ModeratorProfileRequest;
use App\Services\CustomService;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
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
        $pageTitle = "My Profile";
        return view('admin.profile',compact('pageTitle','user'));
    }

    public function postProfile(ModeratorProfileRequest $request){
        $store = $this->customService->saveProfile($request);
        if($store['success']){
            return redirect('/admin/profile')->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }
    public function changePassword(){
        $user = user();
        $pageTitle = "Change Password";
        return view('admin.change-password',compact('pageTitle','user'));
    }

    public function postChangePassword(ChangePasswordRequest $request){
        $store = $this->customService->changePassword($request);
        if($store['success']){
            return redirect('/admin/change-password')->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }

}
