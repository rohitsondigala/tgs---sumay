<?php
namespace App\Services;


class CrudService {
    function storeData($request,$model,$title,$uuid=null){
        if($uuid){
            $submit = $model->where('uuid',$uuid)->update($request);
            if($submit){
                return ['success'=>true,'message'=>trans('strings.admin|'.$title.'|edited'),'model'=>$submit];
            }else{
                return ['success'=>false,'message'=>trans('strings.admin|fail')];
            }
        }else{
            $submit = $model->create($request);
            if($submit){
                return ['success'=>true,'message'=>trans('strings.admin|'.$title.'|created'),'model'=>$submit];
            }else{
                return ['success'=>false,'message'=>trans('strings.admin|fail')];
            }
        }
    }

    function deleteModuleItem($uuid,$model){
        if($model->where('uuid',$uuid)->delete()){
            return ['success'=>true,'message'=>trans('strings.admin|delete')];
        }else{
            return ['success'=>true,'message'=>trans('strings.admin|fail')];
        }
    }
}
