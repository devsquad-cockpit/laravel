<?php

use Cockpit\Http\Controllers\CockpitController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/cockpit', [CockpitController::class, 'index'])->name('cockpit.index');
    Route::get('/cockpit/{occurrence}', [CockpitController::class, 'show'])->name('cockpit.show');
});
