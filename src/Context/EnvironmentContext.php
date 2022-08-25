<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class EnvironmentContext implements ContextInterface
{
    protected Application $app;

    protected Request $request;

    public function __construct(
        Application $app,
    ) {
        $this->app     = $app;
        $this->request = $this->app->make(Request::class);
    }

    public function getContext(): ?array
    {
        return [
            'laravel_version'       => app()->version(),
            'laravel_locale'        => app()->getLocale(),
            'laravel_config_cached' => app()->configurationIsCached(),
            'app_debug'             => config('app.debug'),
            'app_env'               => config('app.env'),
            'environment_date_time' => config('app.timezone'),
            'php_version'           => phpversion(),
            'os_version'            => PHP_OS,
            'server_software'       => !empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '',
            'database_version'      => $this->getDatabaseVersion(),
            'browser_version'       => $this->request->header('User-Agent'),
            'node_version'          => $this->runExec('node -v'),
            'npm_version'           => $this->runExec('npm -v')
        ];
    }

    private function getDatabaseVersion(): string
    {
        $pdo = DB::connection()->getPdo();

        return $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . " " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    private function runExec($command): string
    {
        try {
            return exec($command);
        } catch (Exception $e) {
            return 'Not Captured';
        }
    }
}
