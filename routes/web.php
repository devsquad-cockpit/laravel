<?php

use Cockpit\Http\Controllers\CockpitController;
use Illuminate\Support\Facades\Route;

Route::get('/cockpit', [CockpitController::class, 'index'])->name('cockpit.index');
Route::get('/cockpit/{cockpit}', [CockpitController::class, 'show'])->name('cockpit.show');
