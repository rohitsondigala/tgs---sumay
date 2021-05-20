<?php
namespace App\Services;


class ModeratorService {
    function storeData($request,$model,$title,$uuid=null){
        if($uuid){
            if($model->where('uuid',$uuid)->update($request)){
                return ['success'=>true,'message'=>trans('strings.moderator|'.$title.'|edited')];
            }else{
                return ['success'=>false,'message'=>trans('strings.moderator|fail')];
            }
        }else{
            $submit = $model->create($request);
            if($submit){
                return ['success'=>true,'message'=>trans('strings.moderator|'.$title.'|created'),'model'=>$submit];
            }else{
                return ['success'=>false,'message'=>trans('strings.moderator|fail')];
            }
        }
    }

    function deleteModuleItem($uuid,$model){
        if($model->where('uuid',$uuid)->delete()){
            return ['success'=>true,'message'=>trans('strings.moderator|delete')];
        }else{
            return ['success'=>true,'message'=>trans('strings.moderator|fail')];
        }
    }

    function getDashboardCounts(){
        $moderator_subject_uuid = auth()->user()->moderator->subject_uuid;

        $notes = notes()->where('subject_uuid',$moderator_subject_uuid)->ofApprove()->count();
        $moderators = post_query()->where('subject_uuid',$moderator_subject_uuid)->count();
        $professors = user()->where('subject_uuid',$moderator_subject_uuid)->ofRole('PROFESSOR')->ofVerify()->count();
        $students = user()->where('subject_uuid',$moderator_subject_uuid)->ofRole('STUDENT')->ofVerify()->count();
        return [
            'notes' => $notes,
            'queries' => $moderators,
            'professors' => $professors,
            'students' => $students,
        ];
    }

    function getLatestUsers($userRole){
        $moderator_subject_uuid = auth()->user()->moderator->stream->uuid;
        return user()->where('stream_uuid',$moderator_subject_uuid)->ofRole($userRole)->ofVerify()->orderBy('id','DESC')->take(5)->get();
    }

    function getLatestNotes(){
        $moderator_subject_uuid = auth()->user()->moderator->subject_uuid;
        return notes()->where('subject_uuid',$moderator_subject_uuid)->orderBy('id','DESC')->take(5)->get();
    }
}
