<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Http\Request;
use Livewire\Component;

class PostQueryActivityList extends Component
{
    public $perPage = 10;

    public $route = 'admin.queries';
    /**
     * @var string
     */
    public $directory = 'admin.query-list';

    protected $listeners = [
        'load-more' => 'loadMore'
    ];
    public $user_uuid;

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }

    public function mount($user_uuid){
        $this->user_uuid = $user_uuid;
    }
    public function render()
    {
        $user_uuid = $this->user_uuid;
        $detail = user()->where('uuid',$user_uuid)->first();

        $queries = post_query();

        if($detail->role->title == 'PROFESSOR'){
            $queries->where('to_user_uuid',$user_uuid);
        }else{
            $queries->where('from_user_uuid',$user_uuid);
        }
        $queries = $queries->paginate($this->perPage);
        $route = $this->route;
        return view($this->directory.'.list',compact('queries','detail','route'));
    }
}
