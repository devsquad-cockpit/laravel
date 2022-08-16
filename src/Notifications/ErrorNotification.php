<?php

namespace Cockpit\Notifications;

use Cockpit\Mail\ErrorMail;
use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ErrorNotification extends Notification
{
    use Queueable;

    protected Error $error;

    public function __construct(Error $error)
    {
        $this->error = $error;
    }
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new ErrorMail($this->error))->to(data_get($notifiable->routes, 'mail'));
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
