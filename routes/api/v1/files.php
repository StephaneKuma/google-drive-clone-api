<?php

declare(strict_types=1);

use App\Http\Controllers\File\FileController;
use App\Http\Controllers\File\Folder\FolderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('folders')->group(function () {
        Route::post('', [FolderController::class, 'store']);
    });

    Route::prefix('files')->group(function () {
        Route::get('{folder?}', [FileController::class, 'index'])
            ->where('folder', '(.*)');
        Route::post('', [FileController::class, 'store']);
    });
});
