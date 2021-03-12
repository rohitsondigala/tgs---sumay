<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProfessorList extends Component
{
    public $subjectUuid = 'all';
    public $searchWords;

    public $rating=0;
    /**
     * @var string
     */
    public $route = 'admin.professor';
    /**
     * @var string
     */
    public $directory = 'admin.professor-list';
    public $changeStatus;


    public $perPage = 10;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];


    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }


    public function render(AdminService $adminService)
    {


        $pageTitle = trans('strings.admin|professor|index');
        $directory = $this->directory;
        $route = $this->route;
        $searchWords = $this->searchWords;
        $subjectList = $adminService->getStudentAllSelectedSubjectDropdown();
        $subjectUuid =$this->subjectUuid;
        $rating =$this->rating;
        $upRating = $this->rating + 1;

        $professors = user()->with('professor_subjects')->ofRole('PROFESSOR')
            ->where(function ($query) use ($searchWords) {
                $query->orWhere('name', 'like', '%' . $searchWords . '%');
                $query->orWhere('email', 'like', '%' . $searchWords . '%');
                $query->orWhereHas('professor_subjects.subject', function ($query) use ($searchWords) {
                    $query->where('title', 'like', '%' . $searchWords . '%');
                });
                $query->orWhereHas('stream', function ($query) use ($searchWords) {
                    $query->where('title', 'like', '%' . $searchWords . '%');
                });
            });

        if($subjectUuid != 'all' ){
            $professors->whereHas('professor_subjects.subject',function ($query) use ($subjectUuid){
                $query->where('uuid',$subjectUuid);
            });
            if($rating){
                $professors->withCount(['reviews as average_rating' => function ($query) use ($subjectUuid) {
                    $query->select(DB::raw('coalesce(avg(rating),0)'))->where('subject_uuid',$subjectUuid);
                }])->having('average_rating','>=',$rating)->having('average_rating','<',$upRating )->orderByDesc('average_rating');
            }else{
                $professors->withCount(['reviews as average_rating' => function ($query) use ($subjectUuid) {
                    $query->select(DB::raw('coalesce(avg(rating),0)'))->where('subject_uuid',$subjectUuid);
                }])->orderByDesc('average_rating');
            }
        } elseif($rating) {
            $professors->withCount(['reviews as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating),0)'));
            }])->having('average_rating','>=',$rating)->having('average_rating','<',$upRating )->orderByDesc('average_rating');
        }else{
            $professors->withCount(['reviews as average_rating' => function ($query){
                $query->select(DB::raw('coalesce(avg(rating),0)'));
            }])->orderByDesc('average_rating');
        }

        $professors->ofVerify();

        $professors = $professors->orderBy('id','DESC')->paginate($this->perPage);



        return view($directory . '.list', compact('directory', 'route', 'professors', 'pageTitle','subjectList'));

    }
}
