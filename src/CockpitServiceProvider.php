<?php

namespace Cockpit;

use Cockpit\Console\InstallCockpitCommand;
use Cockpit\Exceptions\Handler;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Monolog\Logger;

class CockpitServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if (!defined('COCKPIT_PATH')) {
            define('COCKPIT_PATH', realpath(__DIR__ . '/../'));
        }

        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('cockpit', function ($app, $config) {
                $handler = new Handler();

                return new Logger('cockpit', [$handler]);
            });
        }
    }

    public function boot()
    {
        $this->bootPublishables()
            ->bootCommands();

        $this->loadRoutesFrom(COCKPIT_PATH . '/routes/web.php');
        $this->loadViewsFrom(COCKPIT_PATH . '/resources/views', 'cockpit');
        $this->mergeConfigFrom(COCKPIT_PATH .'/config/cockpit.php', 'cockpit');
    }

    public function bootCommands(): self
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCockpitCommand::class,
            ]);
        }

        return $this;
    }

    private function bootPublishables(): self
    {
        if ($this->app->runningInConsole()) {
            $configPath   = function_exists('config_path') ? config_path('cockpit.php') : base_path('config/cockpit.php');
            $databasePath = function_exists('database_path') ? database_path() : base_path('database');

            $this->publishes([
                COCKPIT_PATH . '/config/cockpit.php' => $configPath,
            ], 'cockpit-config');

            $this->publishes([
                COCKPIT_PATH . '/database/cockpit.sqlite' => $databasePath . '/cockpit.sqlite',
            ], 'cockpit-database');

            $this->publishes([
                COCKPIT_PATH . '/database/migrations/' => $databasePath . '/migrations/cockpit',
            ], 'cockpit-migrations');

            $this->publishes([
                COCKPIT_PATH . '/public' => public_path('vendor/cockpit'),
            ], 'cockpit-assets');
        }

        return $this;
    }
}
