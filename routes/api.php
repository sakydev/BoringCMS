<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\StatusController;

Route::prefix('api')->group(function() {
    Route::get('/status/{item}', [StatusController::class, 'status']);

    // Forms
    Route::group([], boringPath('routes/api-group/form.php'));
});
