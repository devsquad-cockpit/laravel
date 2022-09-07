<?php

namespace Cockpit\Observers;

use Cockpit\Events\ErrorReport;
use Cockpit\Models\Error;

class ErrorObserver
{
    public function created(Error $error): void
    {
        ErrorReport::dispatch($error);
    }
}
