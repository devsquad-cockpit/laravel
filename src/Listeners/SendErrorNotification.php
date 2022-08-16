<?php

namespace Cockpit\Listeners;

use Cockpit\Events\ErrorReport;
use Cockpit\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;

class SendErrorNotification
{
    public function __construct()
    {
        //
    }

    public function handle(ErrorReport $event)
    {
        Mail::to('test@gmail.com')->send(new ErrorMail($event->error));
    }
}
