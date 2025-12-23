<?php

declare(strict_types=1);

use GoodMaven\Anvil\Support\LivewireTester;

it('shows the initial merged classes from the workbench demo', function (): void {
    $page = visit('/')
        ->wait(1)
        ->assertNoJavaScriptErrors()
        ->assertPresent('[data-testid="component-input"]');

    $defaultOriginal = 'inline-flex items-center gap-2 px-4 py-2 text-sm text-slate-800 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 shadow-sm';
    $defaultOverride = 'px-6 bg-sky-500 text-white hover:bg-sky-600 shadow-md rounded-xl';

    LivewireTester::waitForDomInputValue($page, '[data-testid="component-input"]', $defaultOriginal);
    LivewireTester::waitForDomInputValue($page, '[data-testid="call-input"]', $defaultOverride);

    $expected = trim(twMerge($defaultOriginal, $defaultOverride));
    LivewireTester::waitForRenderedInputValue($page, '[data-testid="merged-output"]', $expected);

    $merged = $page->script("document.querySelector('[data-testid=\"merged-output\"]').value");

    expect($merged)->toBe($expected);
});

it('merges tailwind v4 classes in the browser demo, including new arbitrary value syntax', function (): void {
    $page = visit('/')
        ->wait(1)
        ->assertNoJavaScriptErrors()
        ->assertPresent('[data-testid="component-input"]');

    $original = 'shadow-sm shadow-md bg-(--brand-color) text-lg outline-hidden ring';
    $override = 'shadow-xs bg-(--brand-strong) text-sm outline-2 outline-none ring-3';
    $expected = trim(twMerge($original, $override));

    $page
        ->clear('[data-testid="component-input"]')
        ->type('[data-testid="component-input"]', $original)
        ->clear('[data-testid="call-input"]')
        ->type('[data-testid="call-input"]', $override)
        ->wait(1);

    LivewireTester::waitForRenderedInputValue($page, '[data-testid="merged-output"]', $expected);
    $merged = $page->script("document.querySelector('[data-testid=\"merged-output\"]').value");

    expect($merged)->toBe($expected);
});
