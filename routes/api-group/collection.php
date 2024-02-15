<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\Collection\CollectionController;

Route::prefix('/collections')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/{collectionName}', [CollectionController::class, 'show'])->name('collection.show');
    Route::post('/', [CollectionController::class, 'store'])->name('collection.store');
});
