<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\Field\FieldController;

Route::prefix('/collections/{collectionName}/fields')->group(function() {
    //Route::get('/', [FieldController::class, 'index'])->name('field.index');
    Route::post('/', [FieldController::class, 'store'])->name('field.store');
    //Route::patch('/{fieldUUID}', [FieldController::class, 'update'])->name('field.update');
    //Route::delete('/{fieldUUID}', [FieldController::class, 'destroy'])->name('field.destroy');
});
