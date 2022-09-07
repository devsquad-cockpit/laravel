<?php

namespace Cockpit\Events;

use Cockpit\Models\Error;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ErrorReport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Error $error;

    public function __construct(Error $error)
    {
        $this->error = $error;
    }
}
