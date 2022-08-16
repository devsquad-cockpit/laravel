<?php

namespace Cockpit;

use Cockpit\Events\ErrorReport;
use Cockpit\Listeners\SendErrorNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class CockpitEventServiceProvider extends ServiceProvider
{
    /**
    * The event listener mappings for the application.
    *
    * @var array
    */

    protected $listen = [
        ErrorReport::class => [
            SendErrorNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */

    public function boot()
    {
        parent::boot();

        //
    }
}
