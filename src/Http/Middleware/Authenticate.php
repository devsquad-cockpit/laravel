<?php

namespace Cockpit\Http\Middleware;

use Closure;
use Cockpit\Cockpit;
use Illuminate\Http\Request;

class Authenticate
{
    public function __construct(protected Cockpit $cockpit)
    {
    }
    public function handle(Request $request, Closure $next)
    {
        return $this->cockpit->check($request) ? $next($request) : abort(403);
    }
}
