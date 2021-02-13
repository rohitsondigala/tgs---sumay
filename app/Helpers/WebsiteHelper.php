<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

function upload_path(){
    return '/uploads/media/:userId/:year/:month/:day/';
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
        ':userId' => auth()->user()->uuid ?? '',
        ':year' => $carbon->format('Y'),
        ':month' => $carbon->format('m'),
        ':day' => $carbon->format('d')
    ];
    $uploadLocalPath = strtr(upload_path().$folderName, $replacePathArray);
    $uploadPath = public_path($uploadLocalPath);
    $extension = $requestFile->getClientOriginalExtension();

    if (!File::isDirectory($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true, true);
    }
    $uploadFileName = \Illuminate\Support\Str::slug($fileName,'-');
    $uploadFileName = $uploadFileName.time() . '.' . $extension;
    $requestFile->move($uploadPath, $uploadFileName);

    return $uploadLocalPath.'/'.$uploadFileName;
}
function getVerifyBadge($status){
    if($status == 1){
        return '<span class="badge badge-success">Approved</span>';
    }elseif($status == 2){
        return '<span class="badge badge-danger">Rejected</span>';
    }else{
        return '<span class="badge badge-warning">Pending</span>';
    }
}

