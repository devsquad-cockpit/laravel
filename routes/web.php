<?php

use Cockpit\Http\Controllers\ReportsController;
use Cockpit\Http\Controllers\CockpitController;
use Cockpit\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    Authenticate::class,
])->group(function () {
    Route::prefix('cockpit')->name('cockpit.')->group(function () {

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('', [ReportsController::class, 'index'])->name('index');
        });

        Route::get('', [CockpitController::class, 'index'])->name('index');
        Route::get('{cockpitError}', [CockpitController::class, 'show'])->name('show');
        Route::patch('{cockpitError}', [CockpitController::class, 'resolve'])->name('resolve');
    });
});
