<?php

namespace Cockpit\Listeners;

use Cockpit\Events\ErrorReport;
use Cockpit\Notifications\ErrorMailNotification;
use Cockpit\Notifications\ErrorSlackNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class SendErrorNotification
{
    private Collection $config;

    private const CLASSES = [
        'mail'  => ErrorMailNotification::class,
        'slack' => ErrorSlackNotification::class,
    ];

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
            })->each(function ($config, $channel) use (&$notification, $event) {
                $class = resolve(self::CLASSES[$channel]);
                $notification->route($channel, $config['to'])->notify((new $class($event->error)));
            });
    }
}
