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
}
