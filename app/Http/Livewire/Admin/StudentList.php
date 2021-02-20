<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Livewire\Component;

class StudentList extends Component
{
    public $perPage = 10;

    public $searchWords;
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
        $students = user()->with('student_subjects')->ofRole('STUDENT')
            ->where(function ($query) use ($searchWords){
                $query->orWhere('name','like','%'.$searchWords.'%');
                $query->orWhere('email','like','%'.$searchWords.'%');

            })->orWhereHas('student_subjects.subject',function ($query) use ($searchWords){
                $query->where('title','like','%'.$searchWords.'%');
            })
            ->ofVerify()->orderBy('id','DESC')->paginate($this->perPage);

        return view($directory.'.list',compact('directory','route','students','pageTitle'));
    }
}
