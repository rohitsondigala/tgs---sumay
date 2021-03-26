<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ModeratorList extends Component
{
    /**
     * @var string
     */
    public $searchWords;
    public $subjectUuid = 'all';

    public $route = 'admin.moderator';
    /**
     * @var string
     */
    public $directory = 'admin.masters.moderator';

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
        $pageTitle = trans('strings.admin|moderator|index');
        $directory = $this->directory;
        $route = $this->route;
        $users = user()->ofRole('MODERATOR');
        $searchWords = $this->searchWords;
        $subjectList = $adminService->getStudentAllSelectedSubjectDropdown();
        $subjectUuid =$this->subjectUuid;
        $users->where(function ($query) use ($searchWords) {
            $query->orWhere('name', 'like', '%' . $searchWords . '%');
            $query->orWhere('email', 'like', '%' . $searchWords . '%');
            $query->orWhereHas('moderator.subject', function ($query) use ($searchWords) {
                $query->where('title', 'like', '%' . $searchWords . '%');
            });
            $query->orWhereHas('moderator.stream', function ($query) use ($searchWords) {
                $query->where('title', 'like', '%' . $searchWords . '%');
            });
        });
        if($subjectUuid != 'all'){
            $users->whereHas('moderator.subject',function ($query) use ($subjectUuid){
                $query->where('uuid',$subjectUuid);
            });
            $users->withCount(['notes_approve_count as notes_count','student_post_approve_queries as queries_count']);
            $users->orderBy(DB::raw("notes_count + queries_count"),'DESC');
        }

        $users = $users->ofStatus()->orderBy('id', 'DESC')->paginate($this->perPage);

        return view($directory . '.list', compact('directory', 'route', 'users', 'pageTitle','subjectList'));
    }
}
