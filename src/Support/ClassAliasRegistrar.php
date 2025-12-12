<?php

declare(strict_types=1);

namespace GoodMaven\TailwindMerge\Support;

final class ClassAliasRegistrar
{
    /**
     * Register class aliases with optional conditional logic.
     *
     * @param  array<class-string, class-string>  $aliases
     */
    public static function register(array $aliases, ?callable $shouldAlias = null): void
    {
        if ($shouldAlias !== null && $shouldAlias() === false) {
            return;
        }

        foreach ($aliases as $alias => $target) {
            if (self::classOrInterfaceExists($alias)) {
                continue;
            }

            if (! class_exists($target) && ! interface_exists($target)) {
                continue;
            }

            class_alias($target, $alias);
        }
    }

    protected static function classOrInterfaceExists(string $name): bool
    {
        return class_exists($name, false) || interface_exists($name, false);
    }
}
