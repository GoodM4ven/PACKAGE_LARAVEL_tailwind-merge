<?php

declare(strict_types=1);

use GoodMaven\TailwindMerge\Tests\TestCase;
use Pest\Browser\Execution;
use Symfony\Component\Process\Process;

uses(TestCase::class)->in(__DIR__);

beforeAll(function (): void {
    ensureDemoAssetsBuilt();
});

function ensureDemoAssetsBuilt(): void
{
    static $built = false;

    $projectRoot = dirname(__DIR__);
    $javascriptAsset = $projectRoot.'/workbench/public/build/demo.js';
    $stylesheetAsset = $projectRoot.'/workbench/public/build/demo.css';

    if ($built && file_exists($javascriptAsset) && file_exists($stylesheetAsset)) {
        return;
    }

    if (file_exists($javascriptAsset) && file_exists($stylesheetAsset)) {
        $built = true;

        return;
    }

    $process = new Process(['npm', 'run', 'build:demo'], $projectRoot);
    $process->setTimeout(120);
    $process->mustRun();

    if (! file_exists($javascriptAsset) || ! file_exists($stylesheetAsset)) {
        throw new \RuntimeException('Workbench demo assets were not generated in workbench/public/build.');
    }

    $built = true;
}

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
