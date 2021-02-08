<?php

    function getUserDetail($user_uuid){
        if(user()->where('uuid',$user_uuid)->count() > 0){
            return user()->where('uuid',$user_uuid)->first();
        }else{
            return false;
        }
    }

    function getSubjectList($userDetail){
        $roleTitle = $userDetail->role->title;
        $subjects = ($roleTitle == 'PROFESSOR')
                        ? $userDetail->professor_subjects
                        : $userDetail->student_subjects;
        if(!empty($subjects)){
            $subjectList = array();
            foreach ($subjects as $list){
                array_push($subjectList,$list->subject->uuid);
            }
            return $subjectList;
        }else{
            return false;
        }
    }
