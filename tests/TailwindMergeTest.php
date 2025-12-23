<?php

declare(strict_types=1);

dataset('merge_examples', [
    'font size wins' => [
        ['text-lg leading-5 text-white text-3xl'],
        'leading-5 text-white text-3xl',
    ],
    'responsive variants' => [
        ['sm:text-lg sm:text-3xl'],
        'sm:text-3xl',
    ],
    'custom prefix variants' => [
        ['tw:text-lg tw:text-3xl'],
        'tw:text-3xl',
    ],
    'shadow scale' => [
        ['shadow-sm shadow-xs'],
        'shadow-xs',
    ],
    'blur aliases' => [
        ['blur-sm blur'],
        'blur',
    ],
    'margin shorthands' => [
        ['m-4 mt-8'],
        'mt-8',
    ],
    'gap shorthands' => [
        ['gap-2 gap-x-4 gap-y-8'],
        'gap-x-4 gap-y-8',
    ],
    'text color keywords' => [
        ['text-black text-white'],
        'text-white',
    ],
    'object positions' => [
        ['object-center object-left object-top'],
        'object-top',
    ],
    'important object positions' => [
        ['object-center! object-left! object-top!'],
        'object-top!',
    ],
    'background theme colors' => [
        ['bg-primary-500 bg-primary-700'],
        'bg-primary-700',
    ],
    'text theme colors' => [
        ['text-gray-500 text-danger-600 text-info-700'],
        'text-info-700',
    ],
    'conditional arrays' => [
        [[
            'sm:text-lg py-10 px-5' => true,
            'sm:text-xl' => false,
            'sm:text-3xl py-5',
            'sm:text-sm' => true,
        ]],
        'px-5 py-5 sm:text-sm',
    ],
    'flex basis arbitrary' => [
        ['basis-1/2 basis-(200px)'],
        'basis-(200px)',
    ],
    'aspect ratio' => [
        ['aspect-square aspect-video'],
        'aspect-video',
    ],
    'float merging' => [
        ['float-left float-end'],
        'float-end',
    ],
    'overscroll axis' => [
        ['overscroll-y-auto overscroll-y-none'],
        'overscroll-y-none',
    ],
    'decoration line vs thickness' => [
        ['underline no-underline decoration-2 decoration-4'],
        'no-underline decoration-4',
    ],
    'filter combination' => [
        ['blur-sm grayscale blur'],
        'grayscale blur',
    ],
    'grid columns' => [
        ['grid-cols-3 grid-cols-5'],
        'grid-cols-5',
    ],
    'border color shades' => [
        ['border-black border-red-500'],
        'border-red-500',
    ],
    'outline color arbitrary' => [
        ['outline-red-500 outline-(--brand-outline)'],
        'outline-(--brand-outline)',
    ],
    'opacity scales' => [
        ['opacity-50 opacity-75'],
        'opacity-75',
    ],
    'translate axis' => [
        ['translate-x-2 translate-x-4'],
        'translate-x-4',
    ],
    'scroll snap types' => [
        ['snap-x snap-y snap-center snap-start'],
        'snap-y snap-start',
    ],
    'z index arbitrary' => [
        ['z-10 z-[99]'],
        'z-[99]',
    ],
    'transition duration' => [
        ['duration-200 duration-500'],
        'duration-500',
    ],
    'user select' => [
        ['select-none select-text'],
        'select-text',
    ],
    'background position arbitrary' => [
        ['bg-center bg-(left_20px_top_10px)'],
        'bg-(left_20px_top_10px)',
    ],
]);

it('merges tailwind classes according to spec', function (array $inputs, string $expected): void {
    expect(twMerge(...$inputs))->toBe($expected);
})->with('merge_examples');

it('flattens nested arrays and ignores falsy values', function (): void {
    expect(twMerge(['text-lg', ['', null, false, ['text-sm']]]))->toBe('text-sm');
});

it('registers attribute bag macros', function (): void {
    $attributes = new \Illuminate\View\ComponentAttributeBag(['class' => 'text-lg']);
    $updated = $attributes->twMerge('text-sm');

    expect($updated->get('class'))->toBe('text-lg');
});

it('honors component defaults when no external classes are provided', function (): void {
    $attributes = new \Illuminate\View\ComponentAttributeBag;

    expect($attributes->twMerge('text-sm font-semibold')->get('class'))->toBe('text-sm font-semibold');
});

it('lets consumer-provided classes take precedence over defaults', function (): void {
    $attributes = new \Illuminate\View\ComponentAttributeBag(['class' => 'text-lg px-4']);
    $updated = $attributes->twMerge('text-sm px-2', 'font-semibold');

    expect($updated->get('class'))->toBe('font-semibold text-lg px-4');
});

it('exposes a global TailwindMerge facade alias', function (): void {
    expect(\GoodMaven\TailwindMerge\Facades\TailwindMerge::classes('text-lg', 'text-sm'))->toBe('text-sm');
});
