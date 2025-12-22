<?php

declare(strict_types=1);

namespace Workbench\App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Merger extends Component
{
    public function render(): View
    {
        return view('livewire.merger');
    }
}
