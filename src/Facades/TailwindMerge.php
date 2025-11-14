<?php

declare(strict_types=1);

namespace TailwindMerge\Facades;

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
