<?php

namespace Cockpit\Http\Controllers;

class CockpitController extends Controller
{
    public function index()
    {
        return '<h2>Cockpit</h2>';
    }

    public function show()
    {
        return '<h2>Cockpit Show</h2>';
    }
}
