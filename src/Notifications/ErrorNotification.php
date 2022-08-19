<?php

namespace Cockpit\Notifications;

use Cockpit\Channels\CustomSlackChannel;
use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;

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
            WebhookChannel::class,
            CustomSlackChannel::class,
            'telegram'
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

    public function toWebhook($notifiable)
    {
        return WebhookMessage::create()
            ->data([
                'payload' => [
                    'webhook' => [
                        'id'          => $this->error->id,
                        'url'         => $this->error->url,
                        'description' => sprintf('%s: %s', $this->error->exception, $this->error->message),
                    ]
                ]
            ])
        ->userAgent("Cockpit-User-Agent")
        ->header('X-Cockpit', 'Cockpit-Header');
    }

    public function toCustomSlack()
    {
        return $this->error;
    }

    public function toTelegram($notifiable)
    {
        $description = sprintf('%s: %s', $this->error->exception, $this->error->message);

        return TelegramMessage::create()
            ->token(config('cockpit.notifications.telegram.token'))
            ->to(config('cockpit.notifications.telegram.to'))
            ->content('A new error has been registered in Cockpit. You can check details click on the "Error Details" button to be redirected to the Cockpit. ' . $description)
            ->button('Error Details', $this->error->url);
    }
}
