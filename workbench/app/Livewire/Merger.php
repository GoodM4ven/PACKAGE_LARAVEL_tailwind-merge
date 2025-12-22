<?php

declare(strict_types=1);

namespace Workbench\App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Merger extends Component
{
    public string $original = 'inline-flex items-center gap-2 px-4 py-2 text-sm text-slate-800 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 shadow-sm';

    public string $override = 'px-6 bg-sky-500 text-white hover:bg-sky-600 shadow-md rounded-xl';

    public function getMergedProperty(): string
    {
        return twMerge($this->original, $this->override);
    }

    public function render(): View
    {
        return view('livewire.merger');
    }
}
