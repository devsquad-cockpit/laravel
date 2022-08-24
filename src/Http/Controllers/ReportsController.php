<?php

namespace Cockpit\Http\Controllers;

class ReportsController extends Controller
{
    public function index()
    {
        return view('cockpit::reports.index');
    }
}
