<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\Collection\CollectionController;

Route::prefix('/collections')->middleware(['auth:sanctum'])->group(function() {
    Route::post('/', [CollectionController::class, 'store'])->name('collection.store');
});
