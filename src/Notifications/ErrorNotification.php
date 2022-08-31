<?php

namespace Cockpit\Notifications;

use Cockpit\Channels\SlackChannel;
use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;

class ErrorNotification extends Notification
{
    use Queueable;

    private Error $error;

    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    public function via($notifiable)
    {
        return collect(config('cockpit.notifications'))
            ->filter(function ($config) {
                return $config['enabled'];
            })->map(function ($config, $key) {
                return $this->getChannel($key);
            })->toArray();
    }

    private function getChannel(string $channel)
    {
        return [
            'slack'   => SlackChannel::class,
            'twilio'  => TwilioChannel::class,
            'webhook' => WebhookChannel::class,
            'teams'   => MicrosoftTeamsChannel::class,
        ][$channel] ?? $channel;
    }

    public function toMail()
    {
        return (new MailMessage)
            ->greeting('Hello!')
            ->error()
            ->subject($this->error->description)
            ->line('A new error has been registered in Cockpit. You can check details about the error below or, if you prefer, click on the "Error Details" button to be redirected to the Cockpit.')
            ->line($this->error->description)
            ->action('View Error Details', $this->error->url);
    }

    public function toWebhook()
    {
        return WebhookMessage::create()
                             ->data([
                                 'payload' => [
                                     'webhook' => [
                                         'id'          => $this->error->id,
                                         'url'         => $this->error->url,
                                         'description' => $this->error->description,
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

    public function toTwilio($notifiable)
    {
        $notifiable->phone_number = config('cockpit.notifications.twilio.to');

        return (new TwilioSmsMessage())
            ->content("New error registered in Cockpit: {$this->error->description}");
    }

    public function toTelegram()
    {
        if (empty(config('cockpit.notifications.telegram.token'))) {
            return;
        }

        return TelegramMessage::create()
            ->token(config('cockpit.notifications.telegram.token'))
            ->to(config('cockpit.notifications.telegram.to'))
            ->content("A new error has been registered in Cockpit. You can check details click on the \"Error Details\" button to be redirected to the Cockpit: {$this->error->description}")
            ->button('Error Details', $this->error->url);
    }

    public function toCustomDiscord(): Error
    {
        return $this->error;
    }

    public function toMicrosoftTeams()
    {
        return MicrosoftTeamsMessage::create()
                                    ->to(config('cockpit.notifications.teams.to'))
                                    ->type('error')
                                    ->title('A new error has been registered in Cockpit')
                                    ->content($this->error->description)
                                    ->button('Error Details', $this->error->url);
    }
}
