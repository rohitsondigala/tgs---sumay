<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class PackageList extends Component
{
    public $route = 'admin.packages';
    /**
     * @var string
     */
    public $directory = 'admin.masters.packages';


    public $searchWords;

    public $perPage = 10;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];


    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }

    public function render()
    {
        $pageTitle = trans('strings.admin|package|index');
        $directory = $this->directory;
        $route = $this->route;
        $searchWords = $this->searchWords;

        $packages = packages()->where(function ($query) use ($searchWords) {
            $query->orWhere('title', 'like', '%' . $searchWords . '%');
            $query->orWhere('description', 'like', '%' . $searchWords . '%');
            $query->orWhereHas('subject', function ($query) use ($searchWords) {
                $query->where('title', 'like', '%' . $searchWords . '%');
            });
            $query->orWhereHas('stream', function ($query) use ($searchWords) {
                $query->where('title', 'like', '%' . $searchWords . '%');
            });
        });
        $packages = $packages->orderBy('id', 'DESC')->paginate($this->perPage);

        return view($directory.'.list',compact('directory','route','packages','pageTitle'));
    }
}
