<?php

namespace Cockpit\Notifications;

use Cockpit\Channels\CustomSlackChannel;
use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;

class ErrorNotification extends Notification
{
    use Queueable;

    protected Error $error;
    private string $description;

    public function __construct(Error $error)
    {
        $this->error = $error;

        $this->description = sprintf('%s: %s', $this->error->exception, $this->error->message);
    }

    public function via($notifiable)
    {
        return [
            'mail',
            TwilioChannel::class,
            WebhookChannel::class,
            CustomSlackChannel::class,
        ];
    }

    public function toMail()
    {
        return (new MailMessage)
            ->greeting('Hello!')
            ->error()
            ->subject($this->description)
            ->line('A new error has been registered in Cockpit. You can check details about the error below or, if you prefer, click on the "Error Details" button to be redirected to the Cockpit.')
            ->line($this->description)
            ->action('View Error Details', $this->error->url);
    }

    public function toWebhook($notifiable)
    {
        return WebhookMessage::create()
            ->data([
                'payload' => [
                    'webhook' => $this->description
                ]
            ])
            ->userAgent("Cockpit-User-Agent")
            ->header('X-Cockpit', 'Cockpit-Header');
    }

    public function toCustomSlack()
    {
        return $this->error;
    }

    public function toTwilio($notifiable)
    {
        $notifiable->phone_number = config('cockpit.notifications.twilio.to');

        return (new TwilioSmsMessage())
            ->content("New error registered in Cockpit: {$this->description}");
    }
}
