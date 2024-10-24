<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     */

    public $content;
    public $user;
    public $username;

    public function __construct($content, $user, $username)
    {
        //
        $this->content = $content;
        $this->user = $user;
        $this->username = $username;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card');
    }
}
