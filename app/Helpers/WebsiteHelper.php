<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

function upload_path(){
    return '/uploads/media/:year/:month/:day/';
}
function logo(){
    return asset('logo.jpeg');
}
/**
 * @param $model
 */
function addUUID($model): void
{
    $model->uuid = Str::uuid();
}

function user_image(){
    if(auth()->check()){
        if(!empty(auth()->user()->image)){
            return auth()->user()->image;
        }else{
            return '/assets/img/default-user.png';
        }
    }else{
        return '/assets/img/default-user.png';
    }
}
function user_uuid(){
    if(auth()->check()){
        return auth()->user()->uuid;
    }else{
        return null;
    }
}
function user_name(){
    if(auth()->check()){
            return auth()->user()->name;
    }else{
            return null;
    }
}
function user_email(){
    if(auth()->check()){
        return auth()->user()->email;
    }else{
        return null;
    }
}

function user_role(){
    if(auth()->check()){
        return auth()->user()->role->title;
    }else{
        return null;
    }
}

function user_status(){
    if(auth()->check()){
        return auth()->user()->status;
    }
}

function generateLog($subject){
    $log = [];

    $log['subject'] = $subject;

    $log['url'] = Request::fullUrl();

    $log['method'] = Request::method();

    $log['ip'] = Request::ip();

    $log['agent'] = Request::header('user-agent');

    $log['user_id'] = auth()->check() ? auth()->user()->id : 1;

    log_activities()->create($log);
}

function moderator_role_uuid(){
    return user_roles()->where('title','MODERATOR')->value('uuid');
}
function professor_role_uuid(){
    return user_roles()->where('title','PROFESSOR')->value('uuid');
}
function student_role_uuid(){
    return user_roles()->where('title','STUDENT')->value('uuid');
}


function uploadMedia(\Illuminate\Http\UploadedFile $requestFile,$folderName): string
{
    $fileName = $requestFile->getClientOriginalName();
    $carbon = new Carbon();
    $replacePathArray = [
        ':year' => $carbon->format('Y'),
        ':month' => $carbon->format('m'),
        ':day' => $carbon->format('d'),
    ];
    $uploadLocalPath = strtr(upload_path().$folderName, $replacePathArray);
    $uploadPath = public_path($uploadLocalPath);
    $extension = $requestFile->getClientOriginalExtension();

    if (!File::isDirectory($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true, true);
    }
    $uploadFileName = \Illuminate\Support\Str::slug($fileName,'-');
    $uploadFileName = $uploadFileName.'-'.rand(111111,999999) . '.' . $extension;
    $requestFile->move($uploadPath, $uploadFileName);

    return $uploadLocalPath.'/'.$uploadFileName;
}
function getVerifyBadge($status){
    if($status == 1){
        return '<span class="badge badge-success">Approved</span>';
    }elseif($status == 2){
        return '<span class="badge badge-danger">Rejected</span>';
    }elseif($status == 0) {
        return '<span class="badge badge-warning">Pending</span>';
    }else{
        return '<span class="badge badge-danger">Edited</span>';
    }

}

function getExpiryDateByMonth($months){
    return Carbon::now()->addMonths($months);
}

function getDurationInDaysByExpiryDate($expiryDate){
    return Carbon::parse($expiryDate)->diffInDays(Carbon::now());
}

function getPriceByMonthPackage($package_uuid,$month){
    $columnName = 'price_month_3';
    if($month == 3){
        $columnName = 'price_month_3';
    }elseif($month == 6){
        $columnName = 'price_month_6';
    }elseif($month == 12){
        $columnName = 'price_month_12';
    }elseif($month == 24){
        $columnName = 'price_month_24';
    }elseif($month == 36){
        $columnName = 'price_month_36';
    }
    return packages()->where('uuid',$package_uuid)->value($columnName);
}
function getGeneratePackageDetail($request,$userDetail,$packageDetail){
    $stream_uuid = $packageDetail->stream->uuid;
    $subject_uuid = $packageDetail->subject->uuid;
    $purchase_date = \Carbon\Carbon::now();
    $expiry_date = getExpiryDateByMonth($request->duration_month);
    $duration_in_days = getDurationInDaysByExpiryDate($expiry_date);
    $price = getPriceByMonthPackage($packageDetail->uuid,$request->duration_month);
    return [
        'user_uuid' => $userDetail->uuid,
        'package_uuid' => $packageDetail->uuid,
        'stream_uuid' => $stream_uuid,
        'subject_uuid' => $subject_uuid,
        'purchase_date' => $purchase_date,
        'expiry_date' => $expiry_date,
        'duration_in_days' => $duration_in_days,
        'price' => $price,
        'is_purchased' =>1,
    ];
}

function getDateInFormat($date){
    return Carbon::parse($date)->format('d M Y');
}

function getRatingStarHtmlAdmin($rating){
    $html = '';
    for ($i=1;$i<=5;$i++){
        if($i<=$rating){
            $html .= '<button type="button" class="btn btn-warning btn-sm ml-1" aria-label="Left Align">
                                <span class="mdi mdi-star" aria-hidden="true"></span>
                            </button>';
        }else{
            $html .= '<button type="button" class="m btn btn-default btn-grey btn-sm ml-1" aria-label="Left Align">
                                <span class="mdi mdi-star" aria-hidden="true"></span>
                            </button>';
        }

    }
    return $html;
}
