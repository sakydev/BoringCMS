<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\Collection\CollectionController;

Route::prefix('/collections')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [CollectionController::class, 'index'])->name('collection.index');
    Route::get('/{name}', [CollectionController::class, 'show'])->name('collection.show');
    Route::post('/', [CollectionController::class, 'store'])->name('collection.store');
    Route::delete('/{name}', [CollectionController::class, 'destroy'])->name('collection.destroy');
});
