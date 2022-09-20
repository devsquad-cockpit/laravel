<?php

use Cockpit\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InstallCockpitCommandTest extends TestCase
{
    private function removeFiles(): void
    {
        $skeletonFiles = __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel';

        File::delete([
            $skeletonFiles . '/app/Providers/CockpitServiceProvider.php',
            $skeletonFiles . '/config/cockpit.php',
            $skeletonFiles . '/.env',
        ]);

        File::deleteDirectory($skeletonFiles . '/public/vendor/cockpit');
    }

    /** @test */
    public function it_should_install_cockpit_and_run_migrations(): void
    {
        $this->removeFiles();

        file_put_contents(base_path('.env'), '');

        $this->artisan('cockpit:install')
            ->expectsOutput('Env variables has been set on your .env file')
            ->expectsOutput('Installed Cockpit.')
            ->assertSuccessful();

        $this->assertFileExists(app_path('Providers/CockpitServiceProvider.php'));
        $this->assertFileExists(config_path('cockpit.php'));

        $env = file_get_contents(base_path('.env'));

        $this->assertStringContainsString('COCKPIT_ROUTE=', $env);
        $this->assertStringContainsString('COCKPIT_ENABLED=', $env);
    }

    /** @test */
    public function it_should_force_cockpit_installation(): void
    {
        $this->artisan('cockpit:install', ['--force' => true])
            ->expectsOutput('Installed Cockpit.');
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_should_ask_user_if_he_wants_to_overwrite_config_file(string $type, string $flag): void
    {
        $this->artisan('cockpit:install', [$flag => true])
            ->expectsConfirmation($type . ' file already exists. Do you want to overwrite it?', 'yes')
            ->expectsOutput('Installed Cockpit.')
            ->assertSuccessful();
    }

    public function data(): array
    {
        return [
            ['Configuration', '--config'],
            ['Provider', '--provider'],
        ];
    }

    /** @test */
    public function it_should_not_write_cockpit_service_provider_on_app_php_twice(): void
    {
        $content = file_get_contents(config_path('app.php'));

        file_put_contents(
            config_path('app.php'),
            str_replace(
                "App\Providers\AuthServiceProvider::class," . PHP_EOL,
                "App\Providers\AuthServiceProvider::class," . PHP_EOL . "        App\Providers\CockpitServiceProvider::class," . PHP_EOL,
                $content
            )
        );

        $content = file_get_contents(config_path('app.php'));

        $this->assertSame(1, substr_count($content, "App\Providers\CockpitServiceProvider::class"));

        $this->artisan('cockpit:install', ['--provider' => true])
            ->expectsConfirmation('Provider file already exists. Do you want to overwrite it?', 'yes')
            ->expectsOutput('Installed Cockpit.')
            ->assertSuccessful();

        $content = file_get_contents(config_path('app.php'));

        $this->assertSame(1, substr_count($content, "App\Providers\CockpitServiceProvider::class"));
    }

    /** @test */
    public function it_should_not_display_any_driver_configuration_message_if_env_file_exists(): void
    {
        if (file_exists(base_path('.env'))) {
            unlink(base_path('.env'));
        }

        $this->artisan('cockpit:install', ['--force' => true])
            ->doesntExpectOutput('Which database driver do you want to use with cockpit?')
            ->doesntExpectOutput('Env variables has been set on your .env file')
            ->assertSuccessful();
    }
}
