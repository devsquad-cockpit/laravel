<?php

namespace Cockpit;

use Cockpit\Console\InstallCockpitCommand;
use Cockpit\Context\JobContext;
use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\View\Components\Icons;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
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

        $this->registerErrorHandler();
        $this->registerContexts();
    }

    public function boot()
    {
        Paginator::defaultView('cockpit::pagination.default');

        $this->bootPublishables()
            ->bootCommands()
            ->configureQueue();

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
            $configPath = function_exists('config_path') ? config_path('cockpit.php') : base_path(
                'config/cockpit.php'
            );
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

            $this->publishes([
                COCKPIT_PATH . '/stubs/CockpitServiceProvider.stub' => app_path('Providers/CockpitServiceProvider.php'),
            ], 'cockpit-provider');
        }

        return $this;
    }

    protected function registerErrorHandler(): void
    {
        $this->app->singleton('cockpit.logger', function ($app) {
            $handler = new CockpitErrorHandler();

            return tap(
                new Logger('Cockpit'),
                fn (Logger $logger) => $logger->pushHandler($handler)
            );
        });

        Log::extend('cockpit', fn ($app) => $app['cockpit.logger']);
    }

    protected function registerContexts()
    {
        $this->app->singleton(JobContext::class);

        $this->configureJobContext();
    }

    protected function configureJobContext(): void
    {
        $this->app->make(JobContext::class)->startTrackingQueueEvents();
    }

    protected function configureQueue(): void
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

    protected function resetJobContext(): void
    {
        $this->app->make(JobContext::class)->reset();
    }
}
