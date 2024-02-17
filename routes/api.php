<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\StatusController;

Route::prefix('api')->middleware([])->group(function() {
    Route::get('/status/{item}', [StatusController::class, 'status']);

    Route::group([], boringPath('routes/api-group/account.php'));
    Route::group([], boringPath('routes/api-group/blueprint.php'));
    Route::group([], boringPath('routes/api-group/collection.php'));
    Route::group([], boringPath('routes/api-group/container.php'));
    Route::group([], boringPath('routes/api-group/field.php'));
    Route::group([], boringPath('routes/api-group/entry.php'));
    Route::group([], boringPath('routes/api-group/folder.php'));
    Route::group([], boringPath('routes/api-group/form.php'));
    Route::group([], boringPath('routes/api-group/setting.php'));
    Route::group([], boringPath('routes/api-group/taxonomy.php'));
});
