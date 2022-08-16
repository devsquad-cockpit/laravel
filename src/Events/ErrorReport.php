<?php

namespace Cockpit\Events;

use Cockpit\Models\Error;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ErrorReport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $error;

    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
