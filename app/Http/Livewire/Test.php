<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Test extends Component
{
    public $perPage = 15;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];


    public function loadMore()
    {
        $this->perPage = $this->perPage + 2;
    }



    public function render()
    {
        $users = subjects()->paginate($this->perPage);
        $this->emit('userStore');
        return view('livewire.test', ['users' => $users]);
    }
}
