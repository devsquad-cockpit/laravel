<?php

namespace Cockpit\Context\Livewire;

use Illuminate\Http\Request;

trait HasRequestAccess
{
    protected function getRequest(): Request
    {
        return app()->make(Request::class);
    }
}
