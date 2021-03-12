<?php
namespace App\Services;


class AdminService{

    function getDashboardCounts(){
        $notes = notes()->ofApprove()->count();
        $moderators = user()->ofRole('MODERATOR')->count();
        $professors = user()->ofRole('PROFESSOR')->where('verify',1)->count();
        $students = user()->ofRole('STUDENT')->where('verify',1)->count();
        return [
            'notes' => $notes,
            'moderators' => $moderators,
            'professors' => $professors,
            'students' => $students,
        ];
    }



    function getUserListDropDownByRole($role,$field){
        return user()->ofRole($role)->where('verify',1)->pluck($field,'uuid')->prepend('All','all');
    }

    function getStreamDropdown(){
        return streams()->pluck('title','uuid')->prepend('All','all');
    }

    function getStudentAllSelectedSubjectDropdown(){
        return purchased_packages()->with('subject')->groupby('subject_uuid')->get()->pluck('subject.title','subject.uuid')->prepend('All','all');
    }

    function getLatestUsers($userRole){
        return user()->ofRole($userRole)->ofVerify()->orderBy('id','DESC')->take(5)->get();
    }

    function getLatestNotes(){
        return notes()->orderBy('id','DESC')->take(5)->get();
    }
}
