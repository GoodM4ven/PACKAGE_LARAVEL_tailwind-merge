<?php

declare(strict_types=1);

namespace GoodMaven\TailwindMerge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string classes(...$args)
 */
class TailwindMerge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tailwind-merge';
    }
}
