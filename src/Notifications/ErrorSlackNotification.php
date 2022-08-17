<?php

namespace Cockpit\Notifications;

use Cockpit\Channels\CustomSlackChannel;
use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ErrorSlackNotification extends Notification
{
    use Queueable;

    protected Error $error;

    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    public function via($notifiable)
    {
        return [CustomSlackChannel::class];
    }

    public function toCustomSlack()
    {
        return $this->error;
    }
}
