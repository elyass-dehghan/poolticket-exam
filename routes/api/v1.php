<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->namespace('v1')->group(function () {
    Route::post('event', [\App\Http\Controllers\EventController::class, 'store'])->name('store.event');
    Route::get('events', [\App\Http\Controllers\EventController::class, 'index'])->name('index.events');
});
