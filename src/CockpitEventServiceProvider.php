<?php

namespace Cockpit;

use Cockpit\Events\ErrorReport;
use Cockpit\Listeners\SendErrorNotification;
use Cockpit\Models\Error;
use Cockpit\Observers\ErrorObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class CockpitEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ErrorReport::class => [
            SendErrorNotification::class
        ]
    ];

    public function boot()
    {
        parent::boot();

        Error::observe(ErrorObserver::class);
    }
}
