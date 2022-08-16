<?php

namespace Cockpit\Listeners;

use Cockpit\Events\ErrorReport;
use Cockpit\Notifications\ErrorNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class SendErrorNotification
{
    private Collection $configuration;

    private array $channels = [
        'email' => 'mail'
    ];

    public function __construct()
    {
        $this->configuration = collect(config('cockpit.notifications'));
    }

    public function handle(ErrorReport $event)
    {
        $notification = new Notification;

        $this->configuration
            ->filter(function ($configuration, $key) {
                return $configuration[sprintf('COCKPIT_%s_ENABLED', str($key)->upper())] === true;
            })->each(function ($configuration, $key) use (&$notification, $event) {
                $notification = $notification->route($this->channels[$key] ?? $key, $configuration[sprintf('COCKPIT_TO_%s', str($key)->upper())]);
            });

        if (empty($notification->routes) || empty($notification->routes['mail'])) {
            return;
        }

        $notification->notify(new ErrorNotification($event->error));
    }
}
