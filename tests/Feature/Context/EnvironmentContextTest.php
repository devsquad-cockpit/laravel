<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\EnvironmentContext;
use Cockpit\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use PDO;

class EnvironmentContextTest extends TestCase
{
    private function runExec(string $alias, string $arguments): string
    {
        $binary = trim(@exec('which '.$alias));

        if (!empty($binary)) {
            $command = "$binary $arguments";

            if (($result = @exec($command)) !== '') {
                return $result;
            }
        }

        return 'Not Captured';
    }

    /** @test */
    public function it_should_return_environment_context(): void
    {
        $context = app(EnvironmentContext::class);
        $payload = $context->getContext();

        $pdo       = DB::connection()->getPdo();
        $dbVersion = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . " " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);

        $this->assertSame(app()->version(), $payload['laravel_version']);
        $this->assertSame(app()->getLocale(), $payload['laravel_locale']);
        $this->assertSame(app()->configurationIsCached(), $payload['laravel_config_cached']);
        $this->assertSame(config('app.debug'), $payload['app_debug']);
        $this->assertSame(config('app.env'), $payload['app_env']);
        $this->assertSame(config('app.timezone'), $payload['environment_date_time']);
        $this->assertSame(phpversion(), $payload['php_version']);
        $this->assertSame(PHP_OS, $payload['os_version']);
        $this->assertSame('', $payload['server_software']);
        $this->assertSame($dbVersion, $payload['database_version']);
        $this->assertSame('Symfony', $payload['browser_version']);
        $this->assertSame($this->runExec('node' , '-v'), $payload['node_version']);
        $this->assertSame($this->runExec('npm' , '-v'), $payload['npm_version']);
    }
}
