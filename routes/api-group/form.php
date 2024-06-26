<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\Form\FormController;

Route::prefix('/forms')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [FormController::class, 'index'])->name('forms.index');
    Route::get('/{slug}', [FormController::class, 'show'])->name('forms.show');
    Route::Post('/', [FormController::class, 'store'])->name('forms.store');
    Route::patch('/{slug}', [FormController::class, 'update'])->name('forms.update');
    Route::delete('/{slug}', [FormController::class, 'destroy'])->name('forms.destroy');
});
