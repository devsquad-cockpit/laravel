<?php

namespace Cockpit;

use Cockpit\Console\InstallCockpitCommand;
use Cockpit\Console\MigrateCockpitCommand;
use Cockpit\Context\DumpContext;
use Cockpit\Context\JobContext;
use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\View\Components\Icons;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Monolog\Logger;

class CockpitServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        if (!defined('COCKPIT_PATH')) {
            define('COCKPIT_PATH', realpath(__DIR__ . '/../'));
        }

        if (!defined('COCKPIT_REPO')) {
            define('COCKPIT_REPO', 'https://github.com/elitedevsquad/cockpit');
        }

        $this->registerErrorHandler();
        $this->registerContexts();
        $this->app->register(CockpitEventServiceProvider::class);
    }

    public function boot(): void
    {
        Paginator::defaultView('cockpit::pagination.default');

        $this->bootPublishables()
            ->bootCommands()
            ->bootMacros()
            ->configureQueue();

        $this->loadRoutesFrom(COCKPIT_PATH . '/routes/web.php');

        $this->loadViewComponentsAs('cockpit', [
            Icons::class,
        ]);

        $this->loadViewsFrom(COCKPIT_PATH . '/resources/views', 'cockpit');

        $this->mergeConfigFrom(COCKPIT_PATH . '/config/cockpit.php', 'cockpit');

        $this->bootDatabaseConnection();
    }

    public function bootMacros(): self
    {
        Str::macro('spaceTitle', function (string $value, array $replace = ['_', '.', '-']) {
            return Str::title(Str::replace($replace, ' ', Str::kebab($value)));
        });

        return $this;
    }

    public function bootCommands(): self
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCockpitCommand::class,
                MigrateCockpitCommand::class,
            ]);
        }

        return $this;
    }

    private function bootPublishables(): self
    {
        if ($this->app->runningInConsole()) {
            $configPath = function_exists('config_path')
                ? config_path('cockpit.php')
                : base_path('config/cockpit.php');

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
        $this->app->singleton('cockpit.logger', function () {
            $handler = new CockpitErrorHandler();

            $handler->setMinimumLogLevel(
                $this->getLogLevel()
            );

            return tap(
                new Logger('Cockpit'),
                fn (Logger $logger) => $logger->pushHandler($handler)
            );
        });

        Log::extend('cockpit', fn ($app) => $app['cockpit.logger']);
    }

    protected function registerContexts(): void
    {
        $this->app->singleton(JobContext::class);
        $this->app->singleton(DumpContext::class);

        $this->configureContexts();
    }

    protected function bootDatabaseConnection(): void
    {
        $defaultConnection = config('cockpit.database.default');

        config([
            'database.connections.cockpit' => config('cockpit.database.connections.' . $defaultConnection),
        ]);
    }

    protected function configureContexts(): void
    {
        $this->app->make(JobContext::class)->start();
        $this->app->make(DumpContext::class)->start();
    }

    protected function configureQueue(): void
    {
        if (!$this->app->bound('queue')) {
            return;
        }

        $queue = $this->app->get('queue');

        $queue->before(fn () => $this->resetContexts());
        $queue->after(fn () => $this->resetContexts());
    }

    protected function resetContexts(): void
    {
        $this->app->make(JobContext::class)->reset();
        $this->app->make(DumpContext::class)->reset();
    }

    protected function getLogLevel(): int
    {
        $logLevel = config('logging.channels.cockpit.level', 'error');
        $logLevel = Logger::getLevels()[strtoupper($logLevel)] ?? null;

        if (!$logLevel) {
            throw new InvalidArgumentException('The given log level is invalid');
        }

        return $logLevel;
    }
}
