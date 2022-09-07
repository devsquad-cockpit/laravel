<?php

namespace Cockpit\Channels;

use Cockpit\Models\Error;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DiscordChannel
{
    private const BASE_URL = "https://discord.com/api/v8/webhooks";

    public function send($notifiable, Notification $notification): void
    {
        $token = config('cockpit.notifications.discord.token');

        if ((($channel = $notifiable->routes[static::class] ?? null) === null) || empty($token)) {
            return;
        }

        /** @var Error $error */
        $error = $notification->toCustomDiscord();

        Http::post(self::BASE_URL . '/' . $channel . '/' . $token, [
            "username"   => "Cockpit Bot",
            "avatar_url" => "",
            "content"    => "A new error has been registered in Cockpit.",
            "embeds"     => [
                [
                    "title"       => "You can check details",
                    "url"         => $error->url,
                    "description" => $error->description,
                    "color"       => 15258703
                ]
            ]
        ]);
    }
}
