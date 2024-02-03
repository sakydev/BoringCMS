<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\StatusController;
use Sakydev\Boring\Http\Controllers\Api\FormController;

Route::prefix('api')->group(function() {
    Route::get('/status/{item}', [StatusController::class, 'status']);

    // Forms
    Route::prefix('/forms')->group(function() {
        Route::get('/', [FormController::class, 'index']);
    });
});
