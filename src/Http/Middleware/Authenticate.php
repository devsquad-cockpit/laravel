<?php

namespace Cockpit\Http\Middleware;

use Closure;
use Cockpit\Cockpit;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        return Cockpit::check($request) ? $next($request) : abort(403);
    }
}
