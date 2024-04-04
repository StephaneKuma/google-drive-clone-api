<?php

declare(strict_types=1);

use App\Http\Controllers\File\Folder\FolderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('folders')->group(function () {
    Route::post('', [FolderController::class, 'store']);
});
