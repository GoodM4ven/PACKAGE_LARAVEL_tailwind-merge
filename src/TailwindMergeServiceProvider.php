<?php

declare(strict_types=1);

namespace TailwindMerge;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TailwindMergeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('tailwind-merge');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(TailwindMerge::class, fn () => new TailwindMerge());

        $this->app->alias(TailwindMerge::class, 'tailwind-merge');
    }

    public function packageBooted(): void
    {
        $this->registerBladeDirective();
        $this->registerAttributeBagMacros();
    }

    protected function registerBladeDirective(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade): void {
            $blade->directive('twMerge', function (?string $expression): string {
                return "<?php echo twMerge($expression); ?>";
            });
        });
    }

    protected function registerAttributeBagMacros(): void
    {
        ComponentAttributeBag::macro('twMerge', function (...$args): ComponentAttributeBag {
            /** @var ComponentAttributeBag $this */
            $current = $this->get('class', '');
            $merged = twMerge($current, $args);

            return $this->except('class')->merge(['class' => $merged]);
        });

        ComponentAttributeBag::macro('twMergeFor', function (string $for, ...$args): ComponentAttributeBag {
            /** @var ComponentAttributeBag $this */

            $attribute = 'class'.($for !== '' ? ':'.$for : '');

            /** @var string $existing */
            $existing = $this->get($attribute, '');

            $merged = twMerge($existing, $args);

            return $this->except($attribute)->merge([$attribute => $merged]);
        });
    }
}
