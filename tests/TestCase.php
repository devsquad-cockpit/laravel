<?php

namespace Cockpit\Tests;

use Cockpit\CockpitServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // additional setup
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
        parent::tearDown();

        $app = file_get_contents(config_path('app.php'));
        $app = str_replace('        App\Providers\CockpitServiceProvider::class,' . PHP_EOL, '', $app);

        file_put_contents(config_path('app.php'), $app);
    }
}
