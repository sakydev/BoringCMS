<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\RegisterController;
use Sakydev\Boring\Http\Controllers\Api\LoginController;

Route::prefix('/account')->group(function() {
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [LoginController::class, 'login']);
});
