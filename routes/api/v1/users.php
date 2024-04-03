<?php

declare(strict_types=1);

use App\Http\Controllers\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [AuthenticationController::class, 'currentUser']);
});
