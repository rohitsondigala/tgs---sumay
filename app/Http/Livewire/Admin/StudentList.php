<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentList extends Component
{
    public $perPage = 10;

    public $searchWords;
    public $subjectUuid = 'all';
    public $streamUuid;
    /**
     * @var string
     */
    public $route = 'admin.student';
    /**
     * @var string
     */
    public $directory = 'admin.student-list';

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }


    public function render(AdminService $adminService)
    {

        $pageTitle = trans('strings.admin|student|index');
        $directory = $this->directory;
        $route = $this->route;
        $searchWords = $this->searchWords;
        $streamList = $adminService->getStreamDropdown();
        $subjectList = $adminService->getStudentAllSelectedSubjectDropdown();
        $subjectUuid =$this->subjectUuid;
        $students = user()
            ->with('student_subjects','notes','student_post_queries')->ofRole('STUDENT')

            ->where(function ($query) use ($searchWords){
                $query->orWhere('name','like','%'.$searchWords.'%');
                $query->orWhere('email','like','%'.$searchWords.'%');
                $query->orWhereHas('student_subjects.subject',function ($query) use ($searchWords){
                    $query->where('title','like','%'.$searchWords.'%');
                });
                $query->orWhereHas('stream',function ($query) use ($searchWords){
                    $query->where('title','like','%'.$searchWords.'%');
                });
            });

        if($subjectUuid != 'all'){
            $students->whereHas('student_subjects.subject',function ($query) use ($subjectUuid){
                $query->where('uuid',$subjectUuid);
            });
            $students->withCount(['notes_approve_count as notes_count','student_post_approve_queries as queries_count']);
            $students->orderBy(DB::raw("notes_count + queries_count"),'DESC');
        }



        $students = $students->ofVerify()->orderBy('id','DESC')->paginate($this->perPage);

        return view($directory.'.list',compact('directory','route','students','pageTitle','streamList','subjectList'));
    }
}
