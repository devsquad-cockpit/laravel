<?php

namespace Cockpit\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class CustomDiscordChannel
{
    private const BASE_URL = "https://discord.com/api/v8/webhooks";

    public function send($notifiable, Notification $notification): void
    {
        $token = config('cockpit.notifications.discord.token');

        if ((($channel = $notifiable->routes[static::class] ?? null) === null) || empty($token)) {
            return;
        }

        $error = $notification->toCustomDiscord();
        Http::post(self::BASE_URL . "/" . $channel . "/" . $token, [
            "username"   => "Cockpit Bot",
            "avatar_url" => "",
            "content"    => "A new error has been registered in Cockpit.",
            "embeds"     => [
                [
                    "title"       => "You can check details",
                    "url"         => $error->url,
                    "description" => sprintf('%s: %s', $error->exception, $error->message),
                    "color"       => 15258703
                ]
            ]
        ]);
    }
}
