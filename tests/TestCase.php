<?php

namespace GoodMaven\TailwindMerge\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use GoodMaven\TailwindMerge\TailwindMergeServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            TailwindMergeServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
