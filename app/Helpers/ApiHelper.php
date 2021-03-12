<?php

use App\Notifications\NotesUploadedNotification;
use App\Notifications\SendEditNoteUpdateNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

function checkRoles()
{
    return ['PROFESSOR', 'STUDENT'];
}

function getUserDetail($user_uuid)
{
    if (user()->where('uuid', $user_uuid)->count() > 0) {
        return user()->where('uuid', $user_uuid)->first();
    } else {
        return false;
    }
}

function getSubjectListUUID($userDetail)
{
    $roleTitle = $userDetail->role->title;
    $subjects = ($roleTitle == 'PROFESSOR')
        ? $userDetail->professor_subjects
        : $userDetail->student_subjects;
    if (!empty($subjects)) {
        $subjectList = array();
        foreach ($subjects as $list) {
            array_push($subjectList, $list->subject->uuid);
        }
        return $subjectList;
    } else {
        return false;
    }
}

function getSubjectListArray($userDetail)
{
    $roleTitle = $userDetail->role->title;
    $subjects = ($roleTitle == 'PROFESSOR')
        ? $userDetail->professor_subjects
        : $userDetail->student_subjects;
    $subjects->map(function ($post) {
        return $post->subject;
    });
    return $subjects;
}

function getStudentStreamDetail($userDetail)
{
    return student_subjects()->where('user_uuid', $userDetail->uuid)->first() ?? null;
}


function uploadNotesFiles($files, $notes_uuid,$file_type)
{
    foreach ($files as $file) {
        $fileType = $file_type;
        $fileMimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $fileName = $file->getClientOriginalName();
        $filePath = uploadMedia($file, 'notes');
        $fileArray = [
            'note_uuid' => $notes_uuid,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_mime_type' => $fileMimeType,
            'file_size' => $fileSize,
            'file_path' => $filePath,
            'status' => 1
        ];
        notes_files()->create($fileArray);
    }
    return true;
}

function getStreamUUIDbySubjectUUID($subject_uuid)
{
    if (subjects()->where('uuid', $subject_uuid)->count() > 0) {
        return subjects()->where('uuid', $subject_uuid)->value('stream_uuid');
    } else {
        return false;
    }
}


function sendNewNewNoteUploadNotification($noteDetail)
{
    $subject_uuid = $noteDetail->subject_uuid;
    $moderators = user()->whereHas('moderator', function ($query) use ($subject_uuid) {
        $query->where('subject_uuid', $subject_uuid);
    })->get();

    $data = [
        'title' => $noteDetail->title,
        'icon' => 'mdi mdi-file-image',
        'url' => '/moderator/notes',
    ];
    if (!empty($moderators)) {
        foreach ($moderators as $moderator) {
            $moderator->notify(new NotesUploadedNotification($data));
        }
        return true;
    } else {
        return false;
    }
}

function sendEditNotesUpdateNotification($noteDetail)
{
    $subject_uuid = $noteDetail->subject_uuid;
    $moderators = user()->whereHas('moderator', function ($query) use ($subject_uuid) {
        $query->where('subject_uuid', $subject_uuid);
    })->get();

    $data = [
        'title' => $noteDetail->title,
        'icon' => 'mdi mdi-file-image',
        'url' => '/moderator/notes',
    ];
    if (!empty($moderators)) {
        foreach ($moderators as $moderator) {
            $moderator->notify(new SendEditNoteUpdateNotification($data));
        }
        return true;
    } else {
        return false;
    }
}

function getStudentSubjects($userDetail){
    $roleTitle = $userDetail->role->title;
    $subjects = ($roleTitle == 'PROFESSOR')
        ? $userDetail->professor_subjects
        : $userDetail->student_subjects;
//    $subjects = $userDetail->student_subjects;
    if (!empty($subjects)) {
        $subjectList = array();
        foreach ($subjects as $list) {
            array_push($subjectList, $list->subject->uuid);
        }
        return $subjectList;
    } else {
        return false;
    }


}

function getStudentSubjectsPurchased($userDetail){
    $subjects = $userDetail->student_subjects;
    if (!empty($subjects)) {
        $subjectList = array();
        foreach ($subjects as $list) {
            if($list->is_purchased){
                array_push($subjectList, $list->subject->uuid);
            }
        }
        return $subjectList;
    } else {
        return false;
    }
}


function uploadPostQueryFiles($files, $post_query_uuid,$file_type)
{
    foreach ($files as $file) {
        $fileType = $file_type;
        $fileMimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $fileName = $file->getClientOriginalName();
        $filePath = uploadMedia($file, 'notes');
        $fileArray = [
            'post_query_uuid' => $post_query_uuid,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_mime_type' => $fileMimeType,
            'file_size' => $fileSize,
            'file_path' => $filePath,
            'status' => 1
        ];
        post_query_files()->create($fileArray);
    }
    return true;
}


function uploadPostQueryReplyFiles($files, $post_reply_uuid,$file_type)
{
    foreach ($files as $file) {
        $fileType = $file_type;
        $fileMimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $fileName = $file->getClientOriginalName();
        $filePath = uploadMedia($file, 'notes');
        $fileArray = [
            'post_reply_uuid' => $post_reply_uuid,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_mime_type' => $fileMimeType,
            'file_size' => $fileSize,
            'file_path' => $filePath,
            'status' => 1
        ];
        post_query_reply_files()->create($fileArray);
    }
    return true;
}

function isReviewed($student_uuid,$professor_uuid){
    if(reviews()->where('from_user_uuid',$student_uuid)->where('to_user_uuid',$professor_uuid)->count() > 0){
        return 1;
    }else{
        return 0;
    }
}


function storePurchasePackage($request,$purchasedPackageDetail)
{
    $user_uuid = $request->user_uuid;
    $package_uuid = $request->package_uuid;
    $subject_uuid = $request->subject_uuid;
    $payment_id = $request->payment_id;
    $duration_in_days = $request->duration_in_days;
    $price = $request->price;

    $packageArray = array(
        'package_uuid' => $package_uuid,
        'purchase_date' => Carbon::now(),
        'expiry_date' => Carbon::now()->addDays($duration_in_days),
        'duration_in_days' => $duration_in_days,
        'is_purchased' => 1,
        'price' => $price
    );

    if ($purchasedPackageDetail) {
        if ($purchasedPackageDetail->update($packageArray)) {
            $paymentArray = array(
                'purchase_package_uuid' => $purchasedPackageDetail->uuid,
                'payment_id' => $payment_id,
                'price' => $price,
            );
            purchased_packages_payments()->create($paymentArray);
            return true;
        } else {
            return false;
        }
    } else {
        $packageArray['stream_uuid'] = getStreamUUIDbySubjectUUID($subject_uuid);
        $packageArray['package_uuid'] = $package_uuid;
        $packageArray['user_uuid'] = $user_uuid;
        $packageArray['subject_uuid'] = $subject_uuid;
        $purchasedPackageDetail = purchased_packages()->create($packageArray);
        if ($purchasedPackageDetail) {
            $paymentArray = array(
                'purchase_package_uuid' => $purchasedPackageDetail->uuid,
                'payment_id' => $payment_id,
                'price' => $price,
            );
            purchased_packages_payments()->create($paymentArray);
            return true;
        } else {
            return false;
        }
    }
}


    function delete_image_files($noteDetail){
        if(!empty($noteDetail->image_files)){
            foreach ($noteDetail->image_files as $list){
                if(File::exists(public_path($list->file_path))){
                    File::delete(public_path($list->file_path));
                }
            }
        }
        $noteDetail->image_files()->delete();
        return true;
    }

    function delete_pdf_files($noteDetail){
        if(!empty($noteDetail->pdf_files)){
            foreach ($noteDetail->pdf_files as $list){
                if(File::exists(public_path($list->file_path))){
                    File::delete(public_path($list->file_path));
                }
            }
        }
        $noteDetail->pdf_files()->delete();
        return true;
    }

    function delete_audio_files($noteDetail){
        if(!empty($noteDetail->audio_files)){
            foreach ($noteDetail->audio_files as $list){
                if(File::exists(public_path($list->file_path))){
                    File::delete(public_path($list->file_path));
                }
            }
        }
        $noteDetail->audio_files()->delete();
        return true;
    }


     function pushNotification($device_token,$msg){
            /* FCM Notification */
            $api_access_key =env('FIREBASE_KEY');
            $registrationIds = $device_token;

            $msg = array
            (
                'body' => $msg,
                'title' => 'Guardian'
            );

            $fields = array
            (
                'to' => $registrationIds,
                'notification' => $msg,
                'data' => null
            );

            $headers = array
            (
                'Authorization: key=' . $api_access_key,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);

     /* End FCM Notification */
    }



