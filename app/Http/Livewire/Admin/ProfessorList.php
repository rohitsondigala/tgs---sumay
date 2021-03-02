<?php

namespace App\Http\Livewire\Admin;

use App\Services\AdminService;
use Livewire\Component;

class ProfessorList extends Component
{
    public $perPage = 10;

    public $searchWords;
    /**
     * @var string
     */
    public $route = 'admin.professor';
    /**
     * @var string
     */
    public $directory = 'admin.professor-list';

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }


    public function render()
    {

        $pageTitle = trans('strings.admin|professor|index');
        $directory = $this->directory;
        $route = $this->route;
        $searchWords = $this->searchWords;
        $professors = user()->with('professor_subjects')->ofRole('PROFESSOR')
            ->where(function ($query) use ($searchWords){
                $query->orWhere('name','like','%'.$searchWords.'%');
                $query->orWhere('email','like','%'.$searchWords.'%');

            })->orWhereHas('professor_subjects.subject',function ($query) use ($searchWords){
                $query->where('title','like','%'.$searchWords.'%');
            })
            ->ofVerify()->orderBy('id','DESC')->paginate($this->perPage);

        return view($directory.'.list',compact('directory','route','professors','pageTitle'));

    }
}
