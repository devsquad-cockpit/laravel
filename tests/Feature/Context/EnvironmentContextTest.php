<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\EnvironmentContext;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PDO;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->loadMigrationsFrom(__DIR__ . '/../../database'));

it('should return environment context', function () {
    $context = app(EnvironmentContext::class);
    $payload = $context->getContext();

    $pdo       = DB::connection()->getPdo();
    $dbVersion = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . " " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);

    expect($payload)->toBeArray()
        ->and($payload['laravel_version'])->toBe(app()->version())
        ->and($payload['laravel_locale'])->toBe(app()->getLocale())
        ->and($payload['laravel_config_cached'])->toBe(app()->configurationIsCached())
        ->and($payload['app_debug'])->toBe(config('app.debug'))
        ->and($payload['app_env'])->toBe(config('app.env'))
        ->and($payload['environment_date_time'])->toBe(config('app.timezone'))
        ->and($payload['php_version'])->toBe(phpversion())
        ->and($payload['os_version'])->toBe(PHP_OS)
        ->and($payload['server_software'])->toBe('')
        ->and($payload['database_version'])->toBe($dbVersion)
        ->and($payload['browser_version'])->toBe('Symfony')
        ->and($payload['node_version'])->toBe(runExec('node -v'))
        ->and($payload['npm_version'])->toBe(runExec('npm -v'));
});

function runExec($command): string
{
    try {
        return exec($command);
    } catch (Exception $e) {
        return '';
    }
}
