<?php

namespace Cockpit\Listeners;

use Cockpit\Channels\CustomDiscordChannel;
use Cockpit\Channels\CustomSlackChannel;
use Cockpit\Events\ErrorReport;
use Cockpit\Notifications\ErrorNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class SendErrorNotification
{
    private Collection $config;

    public function __construct()
    {
        $this->config = collect(config('cockpit.notifications'));
    }

    public function handle(ErrorReport $event)
    {
        $notification = new Notification;

        $this->config
            ->filter(function ($config) {
                return $config['enabled'] && !empty($config['to']);
            })->each(function ($config, $channel) use (&$notification) {
                $notification = $notification->route($this->getChannel($channel), $config['to']);
            });

        if (empty($notification->routes)) {
            return;
        }

        $notification->notify((new ErrorNotification($event->error)));
    }

    private function getChannel(string $channel): string
    {
        return [
            'slack'   => CustomSlackChannel::class,
            'discord' => CustomDiscordChannel::class,
        ][$channel] ?? $channel;
    }
}
