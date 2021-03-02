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

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }

    public function render(Request $request)
    {
        $user_uuid = $request->user_uuid;
        $detail = user()->where('uuid',$user_uuid)->first();

        $notes = notes()->ofApprove()->where('user_uuid',$user_uuid)->orderBy('updated_at','DESC')->paginate($this->perPage);
        $route = $this->route;
        return view($this->directory.'.list',compact('notes','detail','route'));
    }
}
