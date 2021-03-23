<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class NotificationList extends Component
{
    public $perPage = 10;
    public $searchWords;
    public $stream_uuid;

    public $route = 'admin.notifications';
    public $directory_path = 'admin.masters.notifications';

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }


    public function render()
    {
        $notifications= push_notifications()->where('type','general')->where('title','like','%'.$this->searchWords.'%')->orderBy('id','DESC')->paginate($this->perPage);
        return view($this->directory_path.'.list',compact('notifications'));
    }
}
