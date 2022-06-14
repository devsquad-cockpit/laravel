<?php

use Cockpit\Http\Controllers\CockpitController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
])->group(function () {
    Route::get('/cockpit', [CockpitController::class, 'index'])->name('cockpit.index');
    Route::get('/cockpit/{occurrence}', [CockpitController::class, 'show'])->name('cockpit.show');
});
