<?php

use App\Notifications\NotesUploadedNotification;
use App\Notifications\SendEditNoteUpdateNotification;

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
    $subjects = $userDetail->student_subjects;
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

