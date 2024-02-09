<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\RegisterController;
use Sakydev\Boring\Http\Controllers\Api\LoginController;
use Sakydev\Boring\Http\Controllers\Api\UserController;

Route::prefix('/account')->group(function() {
    Route::post('/register', [RegisterController::class, 'store'])->name('account.register');
    Route::post('/login', [LoginController::class, 'login'])->name('account.login');

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::get('/me', [UserController::class, 'me'])->name('account.me');
    });
});
