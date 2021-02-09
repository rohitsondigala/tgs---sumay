<?php

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
    return student_subjects()->where('user_uuid',$userDetail->uuid)->first() ?? null;
}
