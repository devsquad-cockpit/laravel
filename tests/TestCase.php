<?php

namespace Cockpit\Tests;

use Cockpit\CockpitServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected $loadEnvironmentVariables = false;

    protected function setUp(): void
    {
        $this->removeServiceProviderFromList();

        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            CockpitServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

    protected function tearDown(): void
    {
        $this->removeServiceProviderFromList();

        parent::tearDown();
    }

    protected function removeServiceProviderFromList(): void
    {
        $appPath = __DIR__ . '/../vendor/orchestra/testbench-core/laravel/config/app.php';

        $app = file_get_contents($appPath);
        $app = str_replace('        App\Providers\CockpitServiceProvider::class,' . PHP_EOL, '', $app);

        file_put_contents($appPath, $app);
    }
}
