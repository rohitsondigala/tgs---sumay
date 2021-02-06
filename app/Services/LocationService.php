<?php
namespace App\Services;


class LocationService {
    function storeData($request,$model,$id=null){
        if($id){
            if($model->where('id',$id)->update($request)){
                return true;
            }else{
                return false;
            }
        }else{
            if($model->create($request)){
                return true;
            }else{
                return false;
            }
        }
    }

    function deleteModuleItem($id,$model){
        if($model->where('id',$id)->delete()){
            return true;
        }else{
            return false;
        }
    }
}
