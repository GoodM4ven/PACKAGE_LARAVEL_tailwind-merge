<?php

declare(strict_types=1);

use GoodMaven\TailwindMerge\Tests\TestCase;
use Pest\Browser\Execution;

uses(TestCase::class)->in(__DIR__);

function waitForValue($page, string $selector, string $expected): void
{
    Execution::instance()->waitForExpectation(function () use ($page, $selector, $expected): void {
        $page->assertValue($selector, $expected);
    });
}

function waitForMergedValue($page, string $selector, string $expected): void
{
    Execution::instance()->waitForExpectation(function () use ($page, $selector, $expected): void {
        $merged = $page->script("document.querySelector('{$selector}').value");

        expect($merged)->toBe($expected);
    });
}
