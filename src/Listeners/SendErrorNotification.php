<?php

namespace Cockpit\Listeners;

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
                $notification = $notification->route($channel, $config['to']);
            });

        if (empty($notification->routes)) {
            return;
        }

        $notification->notify(new ErrorNotification($event->error));
    }
}
