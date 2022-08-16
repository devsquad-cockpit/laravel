<?php

namespace Cockpit\Listeners;

use Cockpit\Events\ErrorReport;
use Cockpit\Notifications\ErrorNotification;
use Illuminate\Support\Facades\Notification;

class SendErrorNotification
{
    public function __construct()
    {
        //
    }

    public function handle(ErrorReport $event)
    {
        Notification::route('mail', ['taylor@example.com', 'teste@gmail.com'])->notify(new ErrorNotification($event->error));
    }
}
