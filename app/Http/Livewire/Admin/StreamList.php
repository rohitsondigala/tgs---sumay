<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Livewire\Component;

class StreamList extends Component
{
    public $perPage = 10;
    public $searchWords;
    public $stream_uuid;

    public $route = 'admin.streams';
    public $directory_path = 'admin.masters.streams';


    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }


    public function render()
    {
        $streams= streams()->where('title','like','%'.$this->searchWords.'%')->orderBy('id','DESC')->paginate($this->perPage);
        return view($this->directory_path.'.list',compact('streams'));
    }
}
