<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Services\CrudService;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    /**
     * @var CrudService
     */
    public $crudService,$adminService;
    /**
     * @var string
     */
    public $route = 'admin.student';
    /**
     * @var string
     */
    public $directory = 'admin.student-list';

    /**
     * AdminStudentController constructor.
     * @param CrudService $crudService
     * @param AdminService $adminService
     */
    public function __construct(CrudService $crudService,AdminService $adminService){
        $this->crudService = $crudService;
        $this->adminService = $adminService;
    }
    public function index(Request  $request)
    {

        $pageTitle = trans('strings.admin|student|index');
        $directory = $this->directory;
        $route = $this->route;
        $students = user()->with('student_subjects')->ofRole('STUDENT');
        $userList = $this->adminService->getUserListDropDownByRole('STUDENT','Name');
        $userEmailList = $this->adminService->getUserListDropDownByRole('STUDENT','Email');
        $streamList = $this->adminService->getStreamDropdown();
        $subjectList = $this->adminService->getStudentAllSelectedSubjectDropdown();
        $title = null;


        $stream_uuid = $request->stream_uuid;
        $subject_uuid = $request->subject_uuid;
        $user_uuid = $request->user_uuid;
        $email = $request->email;

        if(!is_null($user_uuid) && $user_uuid != 'all'){
            $students->where('uuid',$user_uuid);
        }
        if(!is_null($stream_uuid) && $stream_uuid != 'all'){
            $students->where('stream_uuid',$stream_uuid);
        }
        if(!is_null($email) && $email != 'all'){
            $students->where('uuid',$email);
        }
        if(!is_null($subject_uuid) && $subject_uuid != 'all'){
            $students->whereHas('student_subjects',function ($query) use ($subject_uuid){
                $query->where('subject_uuid',$subject_uuid);
            });
        }
        $students = $students->ofVerify()->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','students','pageTitle','userList','email','streamList','subjectList','stream_uuid','subject_uuid','user_uuid','userEmailList'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
