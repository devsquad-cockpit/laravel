<?php

use Cockpit\Http\Controllers\CockpitController;
use Cockpit\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    Authenticate::class,
])->group(function () {
    Route::get('/cockpit', [CockpitController::class, 'index'])->name('cockpit.index');
    Route::get('/cockpit/{cockpitError}', [CockpitController::class, 'show'])->name('cockpit.show');
    Route::patch('/cockpit/{cockpitError}', [CockpitController::class, 'resolve'])->name('cockpit.resolve');
});
