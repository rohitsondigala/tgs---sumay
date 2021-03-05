<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Http\Request;
use Livewire\Component;

class NoteActivityList extends Component
{
    public $perPage = 10;

    public $route = 'admin.notes';
    /**
     * @var string
     */
    public $directory = 'admin.notes-list';
    public $user_uuid;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function mount($user_uuid){
        $this->user_uuid = $user_uuid;
    }

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }

    public function render()
    {
        $user_uuid = $this->user_uuid;
        $detail = user()->where('uuid',$user_uuid)->first();

        $notes = notes()->ofApprove()->where('stream_uuid',$detail->stream_uuid)->where('user_uuid',$user_uuid)->orderBy('updated_at','DESC')->paginate($this->perPage);
        $route = $this->route;
        return view($this->directory.'.list',compact('notes','detail','route'));
    }
}
