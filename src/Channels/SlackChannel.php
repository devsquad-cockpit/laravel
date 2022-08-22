<?php

namespace Cockpit\Channels;

use Cockpit\Models\Error;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SlackChannel
{
    public function send($notifiable, Notification $notification): void
    {
        /** @var Error $error */
        $error = $notification->toCustomSlack();

        $description = sprintf('%s: %s', $error->exception, $error->message);

        if (($url = $notifiable->routes[static::class] ?? null) === null) {
            return;
        }

        Http::post($url, [
            'type'   => 'mrkdwn',
            'text'   => $description,
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "*A new error has been registered in Cockpit.*\n\nYou can check details about the error below or, if you prefer, click on the _\"Error Details\" (or in error description)_ button to be redirected directly to the error details of in the Cockpit.",
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "*<{$error->url}|{$description}>*",
                    ],
                ],
                [
                    'type'     => 'actions',
                    'elements' => [
                        [
                            'type'  => 'button',
                            'style' => 'danger',
                            'text'  => [
                                'type' => 'plain_text',
                                'text' => 'Error Details',
                            ],
                            'url' => $error->url,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
