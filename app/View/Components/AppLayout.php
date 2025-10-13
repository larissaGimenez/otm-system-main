<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public bool $showSidebar;

    public function __construct(bool $showSidebar = true)
    {
        $this->showSidebar = $showSidebar;
    }

    public function render(): View
    {
        return view('layouts.app');
    }
}