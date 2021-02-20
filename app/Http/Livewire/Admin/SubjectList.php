<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Livewire\Component;

class SubjectList extends Component
{
    public $perPage = 10;
    public $searchWords;
    public $stream_uuid;

    public $route = 'admin.subjects';


    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }


    public function render(AdminService $adminService)
    {
        $streamList = $adminService->getStreamDropdown();
        $subjects= subjects()->whereHas('stream',function ($query){
          $query->where('title','like','%'.$this->searchWords.'%');
        })->orWhere('title','like','%'.$this->searchWords.'%');

        $subjects = $subjects->paginate($this->perPage);
        return view('admin.masters.subjects.list',compact('subjects','streamList'));
    }
}
