<?php

use Illuminate\Support\Facades\Route;

Route::get('/cockpit', function () {
    return '<h2>Cockpit</h2>';
});

Route::get('/cockpit/{cockpit}', function () {
    return '<h2>Cockpit Show</h2>';
});
