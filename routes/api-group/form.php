<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\FormController;

Route::prefix('/forms')->group(function() {
    Route::get('/', [FormController::class, 'index']);
});
