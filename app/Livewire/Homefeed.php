<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Homefeed extends Component
{
    use WithPagination;

    public $pageLimit = 10;

    protected $listeners = [
        "loadMore" => "loadMore",
    ];

    public function loadMore(){
        $this->pageLimit += 10;
    }

    public function render()
    {
        $posts = Post::latest()->paginate($this->pageLimit);
        return view('livewire.homefeed', compact('posts'));
    }
}
