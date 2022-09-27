<?php

namespace Cockpit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCockpitCommand extends Command
{
    protected $signature = 'cockpit:install
        {--C|config : Install the config file}
        {--P|provider : Install service provider}
        {--F|force : Overwrite existing files}';

    protected $description = 'Create the config files for Cockpit.';

    public function handle(): void
    {
        $this->info('Installing Cockpit...');

        $this->publishConfig();
        $this->publishProvider();
        $this->publishEnv();

        $this->info('Installed Cockpit.');
    }

    private function publishConfig(): void
    {
        $configPath = function_exists('config_path')
            ? config_path('cockpit.php')
            : base_path('config/cockpit.php');

        if (!$this->anyDefaultOption() || $this->option('config')) {
            $this->publish('configuration', $configPath);
        }
    }

    private function publishProvider(): void
    {
        $providerPath = app_path('Providers/CockpitServiceProvider.php');

        if (!$this->anyDefaultOption() || $this->option('provider')) {
            $this->publish('provider', $providerPath);
            $this->registerCockpitServiceProvider();
        }
    }

    private function publishEnv(): void
    {
        $env = base_path('.env');

        if (!file_exists($env)) {
            return;
        }

        $envContent = file_get_contents($env);

        if (Str::contains($envContent, 'COCKPIT_DOMAIN')) {
            $this->info('Required env vars already exist');

            return;
        }

        $envContent .= PHP_EOL;
        $envContent .= 'COCKPIT_DOMAIN=http://localhost' . PHP_EOL;
        $envContent .= 'COCKPIT_ENABLED=true' . PHP_EOL;

        file_put_contents($env, $envContent);

        $this->info('Env variables has been set on your .env file');
    }

    private function anyDefaultOption(): bool
    {
        return $this->option('config')
            || $this->option('provider');
    }

    private function publish(string $fileType, string $path): void
    {
        $lowerFileType = Str::lower($fileType);
        $titleFileType = Str::title($fileType);

        if (!$this->fileExists($path)) {
            $this->publishFile($lowerFileType);

            return;
        }

        if ($this->shouldOverwrite($titleFileType)) {
            $this->publishFile($lowerFileType, true);
        }
    }

    private function fileExists(string $path): bool
    {
        return File::exists($path);
    }

    private function shouldOverwrite(string $fileType): bool
    {
        return $this->option('force')
            || $this->confirm("{$fileType} file already exists. Do you want to overwrite it?", false);
    }

    private function publishFile(string $fileType, bool $forcePublish = false): void
    {
        if ($fileType == 'configuration') {
            $fileType = 'config';
        }

        $params = [
            '--provider' => "Cockpit\CockpitServiceProvider",
            '--tag'      => "cockpit-{$fileType}",
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

    private function registerCockpitServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());
        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace . '\\Providers\\CockpitServiceProvider::class')) {
            return;
        }

        file_put_contents(
            config_path('app.php'),
            str_replace(
                "{$namespace}\\Providers\AuthServiceProvider::class," . PHP_EOL,
                "{$namespace}\\Providers\AuthServiceProvider::class," . PHP_EOL . "        {$namespace}\Providers\CockpitServiceProvider::class," . PHP_EOL,
                $appConfig
            )
        );

        file_put_contents(
            app_path('Providers/CockpitServiceProvider.php'),
            str_replace(
                "namespace App\Providers;",
                "namespace {$namespace}\Providers;",
                file_get_contents(app_path('Providers/CockpitServiceProvider.php'))
            )
        );
    }
}
