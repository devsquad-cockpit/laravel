<?php

namespace Cockpit\Tests\Fixtures\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TestController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {

    }
}
