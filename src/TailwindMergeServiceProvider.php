<?php

namespace GoodMaven\TailwindMerge;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use GoodMaven\TailwindMerge\Commands\TailwindMergeCommand;

class TailwindMergeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('tailwind-merge')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_tailwind_merge_table')
            ->hasCommand(TailwindMergeCommand::class);
    }
}
