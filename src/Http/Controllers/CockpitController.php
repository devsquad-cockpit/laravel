<?php

namespace Cockpit\Http\Controllers;

class CockpitController extends Controller
{
    public function index()
    {
        return view('cockpit::index');
    }

    public function show()
    {
        return view('cockpit::show');
    }
}
