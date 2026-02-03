<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class TestableWorkbenchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::addNamespace(
            namespace: 'tailwind-merge',
            classNamespace: 'Workbench\\App\\Livewire',
            classPath: app_path('Livewire'),
            classViewPath: resource_path('views/livewire')
        );
    }
}
