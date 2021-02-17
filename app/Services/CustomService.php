<?php
namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


/**
 * Class CustomService
 * @package App\Services
 */
class CustomService {
    /**
     * @param $request
     * @param null $uuid
     * @return array
     */
    function storeModerator($request, $uuid=null){
        $temporaryPassword = Str::random(8);
        $request->merge(['password'=>bcrypt($temporaryPassword)]);

        $moderatorArray = ['stream_uuid'=>$request->stream_uuid,'subject_uuid'=>$request->subject_uuid];

        if($uuid){
            $userArray = $request->except(['_token','_method','stream_uuid','subject_uuid','image','password']);
            if(!empty($request->image)){
                $filePath = uploadMedia($request->image,'profile');
                $userArray['image'] = $filePath;
            }
            $submit = user()->where('uuid',$uuid)->update($userArray);
            if($submit){
                moderator_subjects()->where('user_uuid',$uuid)->update($moderatorArray);
                return ['success'=>true,'message'=>trans('strings.admin|moderator|edited'),'model'=>$submit];
            }else{
                return ['success'=>false,'message'=>trans('strings.admin|fail')];
            }
        }else{
            $userArray = $request->except(['_token','_method','stream_uuid','subject_uuid','image']);
            $filePath = uploadMedia($request->image,'profile');
            $userArray['image'] = $filePath;
            $userArray['role_uuid'] = moderator_role_uuid();
            $submit = user()->create($userArray);
            if($submit){
                $moderatorArray['user_uuid'] = $submit->uuid;
                moderator_subjects()->create($moderatorArray);
                $user = user()->where('uuid',$submit->uuid)->first();
                Mail::to($user->email)->send(new \App\Mail\ModeratorRegistered($user,$temporaryPassword));
                return ['success'=>true,'message'=>trans('strings.admin|moderator|created'),'model'=>$submit];
            }else{
                return ['success'=>false,'message'=>trans('strings.admin|fail')];
            }
        }
    }

    /**
     * @param $uuid
     * @return array
     */
    function deleteModerator($uuid){
        $userDetail = user()->where('uuid',$uuid)->first();
        if($userDetail->delete()){
            return ['success'=>true,'message'=>trans('strings.admin|delete')];
        }else{
            return ['success'=>false,'message'=>trans('strings.admin|fail')];
        }
    }

    function saveProfile($request){
        $userArray = $request->except('_token','_method','image');
        if(!empty($request->image)){
            $filePath = uploadMedia($request->image,Str::slug($request->name));
            $userArray['image'] = $filePath;
        }

        if(user()->where('uuid',user_uuid())->update($userArray)){
            return ['success'=>true,'message'=>trans('strings.admin|profile')];
        }else{
            return ['success'=>false,'message'=>trans('strings.admin|fail')];
        }
    }

    function changePassword($request){
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;
        if(Hash::check($current_password,auth()->user()->password)){
            if($current_password == $new_password){
                return ['success'=>false,'message'=>trans('auth.old_new_same')];
            }elseif($new_password == $confirm_password){
                user()->where('uuid',auth()->user()->uuid)->update(['password'=>bcrypt($confirm_password)]);
                $user = user()->where('uuid', auth()->user()->uuid)->first();
                \auth()->login($user);
                return ['success'=>true,'message'=>trans('auth.password_updated')];
            }else{
                return ['success'=>false,'message'=>trans('auth.password_not_match')];
            }
        }else{
            return ['success'=>false,'message'=>trans('auth.current_password_does_not_match')];
        }
    }


}
