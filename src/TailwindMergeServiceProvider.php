<?php

declare(strict_types=1);

namespace GoodMaven\TailwindMerge;

use GoodMaven\TailwindMerge\Facades\TailwindMerge as TailwindMergeFacade;
use GoodMaven\TailwindMerge\Support\ClassAliasRegistrar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\JsonSchema\JsonSchema as IlluminateJsonSchema;
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
        $this->registerJsonSchemaContractAlias();

        $this->app->singleton(TailwindMerge::class, fn() => new TailwindMerge);

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

            $merged = twMerge(...array_merge($args, [$current]));

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

    protected function registerJsonSchemaContractAlias(): void
    {
        ClassAliasRegistrar::register(
            [\Illuminate\Contracts\JsonSchema\JsonSchema::class => IlluminateJsonSchema::class],
            fn() => $this->shouldAliasForBoostMcp(),
        );
    }

    protected function shouldAliasForBoostMcp(): bool
    {
        if (! $this->app->runningInConsole()) {
            return false;
        }

        $argv = $_SERVER['argv'] ?? [];

        return in_array('boost:mcp', $argv, true);
    }
}
