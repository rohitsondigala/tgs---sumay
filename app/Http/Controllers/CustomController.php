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

    public function getUserListNotes(Request $request){
        $role = $request->role;
        $notes = notes()->where('subject_uuid',auth()->user()->moderator->subject_uuid)->whereHas('user.role',function ($query) use ($role){
            $query->where('title',$role);
        })->orderBy('id','DESC')->get()->pluck('user.name','user.uuid')->prepend('All','all');
        return response()->json($notes);
    }

    public function getPackageDetail(Request  $request){
        $packageData = packages()
            ->where("uuid",$request->package_uuid)
            ->first();
        $returnArray = [
            '3' =>'3 Months - INR '.$packageData->price_month_3,
            '6' =>'6 Months - INR '.$packageData->price_month_6,
            '12' =>'12 Months - INR '.$packageData->price_month_12,
            '24' =>'24 Months - INR '.$packageData->price_month_24,
            '36' =>'36 Months - INR '.$packageData->price_month_36,
            ];
        return response()->json($returnArray);
    }
}
