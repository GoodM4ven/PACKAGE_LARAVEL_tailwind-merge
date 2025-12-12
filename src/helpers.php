<?php

declare(strict_types=1);

use GoodMaven\TailwindMerge\TailwindMerge;

if (! function_exists('twMerge')) {
    /**
     * @param  string|array<int, string|array<int, string>>  ...$args
     */
    function twMerge(...$args): string
    {
        /** @var TailwindMerge $merger */
        $merger = app('tailwind-merge');

        return $merger->classes(...$args);
    }
}
