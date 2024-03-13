<?php

namespace Cockpit\Tests\Feature\Console;

use Cockpit\Tests\TestCase;
use Illuminate\Support\Facades\File;

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
    public function it_should_install_cockpit(): void
    {
        $this->removeFiles();

        file_put_contents(base_path('.env'), '');

        $this->artisan('cockpit:install')
            ->expectsOutput('Env variables has been set on your .env file')
            ->expectsOutput('Installed Cockpit.')
            ->assertSuccessful();

        $this->assertFileExists(app_path('Providers/CockpitServiceProvider.php'));
        $this->assertFileExists(config_path('cockpit.php'));

        $this->assertStringContainsString(
            'CockpitServiceProvider::class',
            file_get_contents(base_path('bootstrap/providers.php'))
        );

        $env = file_get_contents(base_path('.env'));

        $this->assertStringContainsString('COCKPIT_DOMAIN=', $env);
        $this->assertStringContainsString('COCKPIT_ENABLED=', $env);
        $this->assertStringContainsString('COCKPIT_TOKEN=', $env);
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

    public static function data(): array
    {
        return [
            ['Configuration', '--config'],
            ['Provider', '--provider'],
        ];
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
