<?php

namespace Cockpit;

use Cockpit\Console\InstallCockpitCommand;
use Cockpit\Context\JobContext;
use Cockpit\Exceptions\Handler;
use Cockpit\View\Components\Icons;
use Illuminate\Log\LogManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Monolog\Logger;

class CockpitServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if (!defined('COCKPIT_PATH')) {
            define('COCKPIT_PATH', realpath(__DIR__ . '/../'));
        }

        if (!defined('COCKPIT_REPO')) {
            define('COCKPIT_REPO', 'https://github.com/elitedevsquad/cockpit');
        }

        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('cockpit', function ($app, $config) {
                $handler = new Handler();

                return new Logger('cockpit', [$handler]);
            });
        }

        $this->app->singleton(JobContext::class);

        $this->configureJobContext();
        $this->configureQueue();
    }

    public function boot()
    {
        Paginator::defaultView('cockpit::pagination.default');

        $this->bootPublishables()
            ->bootCommands();

        $this->loadRoutesFrom(COCKPIT_PATH . '/routes/web.php');

        $this->loadViewComponentsAs('cockpit', [
            Icons::class,
        ]);

        $this->loadViewsFrom(COCKPIT_PATH . '/resources/views', 'cockpit');

        $this->mergeConfigFrom(COCKPIT_PATH . '/config/cockpit.php', 'cockpit');
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

    protected function configureJobContext(): void
    {
        $this->app->make(JobContext::class)->startTrackingQueueEvents();
    }

    protected function configureQueue()
    {
        if (!$this->app->bound('queue')) {
            return;
        }

        $queue = $this->app->get('queue');

        $queue->before(function () {
            $this->resetJobContext();
        });

        $queue->after(function () {
            $this->resetJobContext();
        });
    }

    protected function resetJobContext()
    {
        $this->app->make(JobContext::class)->reset();
    }
}
