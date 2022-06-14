<?php

namespace Cockpit\Http\Controllers;

use Cockpit\Models\Occurrence;

class CockpitController extends Controller
{
    public function index()
    {
        return view('cockpit::index');
    }

    public function show(Occurrence $occurrence)
    {
        $occurrence->load('error');

        return view('cockpit::show', compact('occurrence'));
    }
}
