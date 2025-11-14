<?php

declare(strict_types=1);

namespace TailwindMerge;

use Illuminate\Foundation\AliasLoader;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TailwindMerge\Facades\TailwindMerge as TailwindMergeFacade;

class TailwindMergeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('tailwind-merge');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(TailwindMerge::class, fn () => new TailwindMerge);

        $this->app->alias(TailwindMerge::class, 'tailwind-merge');
    }

    public function packageBooted(): void
    {
        $this->registerBladeDirective();
        $this->registerAttributeBagMacros();
        $this->registerFacadeAlias();
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
    }

    protected function registerFacadeAlias(): void
    {
        if (class_exists(AliasLoader::class)) {
            AliasLoader::getInstance()->alias('TailwindMerge', TailwindMergeFacade::class);

            return;
        }

        if (! class_exists('TailwindMerge')) {
            class_alias(TailwindMergeFacade::class, 'TailwindMerge');
        }
    }
}
