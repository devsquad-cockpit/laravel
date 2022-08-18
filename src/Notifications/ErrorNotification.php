<?php

namespace Cockpit\Notifications;

use Cockpit\Channels\CustomSlackChannel;
use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return [
            'mail',
            CustomSlackChannel::class,
        ];
    }

    public function toMail()
    {
        $description = sprintf('%s: %s', $this->error->exception, $this->error->message);

        return (new MailMessage)
            ->greeting('Hello!')
            ->error()
            ->subject($description)
            ->line('A new error has been registered in Cockpit. You can check details about the error below or, if you prefer, click on the "Error Details" button to be redirected to the Cockpit.')
            ->line($description)
            ->action('View Error Details', $this->error->url);
    }

    public function toCustomSlack()
    {
        return $this->error;
    }
}
