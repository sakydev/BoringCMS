<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\User\LoginController;
use Sakydev\Boring\Http\Controllers\Api\User\RegisterController;
use Sakydev\Boring\Http\Controllers\Api\User\UserController;

Route::prefix('/account')->group(function() {
    Route::middleware('guest')->group(function() {
        Route::post('/register', [RegisterController::class, 'store'])->name('account.register');
        Route::post('/login', [LoginController::class, 'login'])->name('account.login');
    });

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::get('/me', [UserController::class, 'me'])->name('account.me');
    });
});
