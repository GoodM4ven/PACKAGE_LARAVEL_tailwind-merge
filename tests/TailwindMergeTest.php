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
