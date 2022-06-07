<?php

namespace Cockpit;

use Cockpit\Exceptions\Handler;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaraBug\Logger\LaraBugHandler;
use Monolog\Logger;

class CockpitServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('cockpit', function ($app, $config) {
                $handler = new Handler();

                return new Logger('cockpit', [$handler]);
            });
        }
    }

    public function boot()
    {
        //
    }
}
