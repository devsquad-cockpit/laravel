<?php

namespace Cockpit\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class CustomDiscordChannel
{
    protected string $baseUrl = "https://discord.com/api/v8/webhooks";

    public function send($notifiable, Notification $notification): void
    {
        if (($channel = $notifiable->routes[static::class] ?? null) === null) {
            return;
        }

        $token       = config('cockpit.notifications.discord.token');
        $error       = $notification->toCustomDiscord();
        $description = sprintf('%s: %s', $error->exception, $error->message);
        Http::post($this->baseUrl . "/" . $channel . "/" . $token, [
            "username"   => "Cockpit Bot",
            "avatar_url" => "",
            "content"    => "A new error has been registered in Cockpit.",
            "embeds"     => [
                [
                    "title"       => "You can check details",
                    "url"         => $error->url,
                    "description" => $description,
                    "color"       => 15258703
                ]

            ]
        ]);
    }
}
