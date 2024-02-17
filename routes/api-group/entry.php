<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\Collection\EntryController;

Route::prefix('/collections/{collectionName}/entries')->middleware(['auth:sanctum'])->group(function() {
    // Route::get('/', [EntryController::class, 'index'])->name('entry.index');
    // Route::get('/{entryUUID}', [EntryController::class, 'show'])->name('entry.show');
    Route::post('/', [EntryController::class, 'store'])->name('entry.store');
    // Route::patch('/{entryUUID}', [EntryController::class, 'update'])->name('entry.update');
    //Route::delete('/{entryUUID}', [EntryController::class, 'destroy'])->name('entry.destroy');
});
