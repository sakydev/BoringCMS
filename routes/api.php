<?php

use Illuminate\Support\Facades\Route;
use Sakydev\Boring\Http\Controllers\Api\StatusController;

Route::get('/status/{item}', [StatusController::class, 'status']);
