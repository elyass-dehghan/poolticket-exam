<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('events', [\App\Http\Controllers\EventController::class, 'store']);
    Route::get('events', [\App\Http\Controllers\EventController::class, 'index']);
});
