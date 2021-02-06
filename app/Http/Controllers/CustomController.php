<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomController extends Controller
{
    public function search(Request $request,$table){
        $words =  $request->words;
        $route =  $request->route;
        if(!empty($words)){
            $data = $table()->where('title','like','%'.$words.'%')->get();
        }else{
            $data = $table()->orderBy('id','DESC')->get();
        }
        $html =null;
        $i =1;
        if(!empty($data)){

            foreach ($data as $list){

                $html .= '<tr>
            <td>'.$i++.'</td>
            <td>'.$list->title.'</td>
            <td>'.$list->is_standard.'</td>
            <td>
                <a href="'.route($route.".edit",$list->uuid).'">Edit </a>
               </td>
        </tr>';
            }
        }
        return $html;
    }

    public function getSubjectList(Request $request){
        $subjects = subjects()
            ->where("stream_uuid",$request->stream_uuid)
            ->get()->pluck("title","uuid");
        return response()->json($subjects);
    }
    public function getStateList(Request $request){
        $states = state()
            ->where("country_id",$request->country_id)
            ->get()->pluck("name","id");
        return response()->json($states);
    }
    public function getCityList(Request $request){
        $cities = city()
            ->where("state_id",$request->state_id)
            ->get()->pluck("name","id");
        return response()->json($cities);
    }
}
