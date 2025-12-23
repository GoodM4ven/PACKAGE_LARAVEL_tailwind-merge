<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/build/{path}', function (string $path) {
    $buildDirectory = realpath(base_path('workbench/public/build'));
    $assetPath = realpath(base_path('workbench/public/build/'.ltrim($path, '/')));

    if ($buildDirectory === false || $assetPath === false) {
        abort(404);
    }

    if (! str_starts_with($assetPath, $buildDirectory.DIRECTORY_SEPARATOR) || ! is_file($assetPath)) {
        abort(404);
    }

    return response()->file($assetPath);
})->where('path', '.*');

Route::view('/', 'demo');
