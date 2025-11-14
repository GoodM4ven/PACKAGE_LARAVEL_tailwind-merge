<?php

namespace GoodMaven\TailwindMerge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GoodMaven\TailwindMerge\TailwindMerge
 */
class TailwindMerge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GoodMaven\TailwindMerge\TailwindMerge::class;
    }
}
