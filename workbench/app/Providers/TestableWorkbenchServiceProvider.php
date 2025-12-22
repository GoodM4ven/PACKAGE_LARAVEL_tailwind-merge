<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Workbench\App\Livewire\Merger;

final class TestableWorkbenchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('tailwind-merge::merger', Merger::class);
    }
}
