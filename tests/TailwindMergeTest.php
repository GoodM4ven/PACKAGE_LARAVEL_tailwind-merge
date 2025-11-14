<?php

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

    expect($updated->get('class'))->toBe('text-sm');
});

it('exposes a global TailwindMerge facade alias', function (): void {
    expect(\TailwindMerge\Facades\TailwindMerge::classes('text-lg', 'text-sm'))->toBe('text-sm');
});
