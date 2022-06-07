<?php

namespace Cockpit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCockpitCommand extends Command
{
    protected $signature = 'cockpit:install
    {--C|config : Install the config file}
    {--D|database : Install the database file}
    {--M|migrations : Install the migrations}
    ';

    protected $description = 'Create the config and the database files for Cockpit.';

    public function handle()
    {
        $this->info('Installing Cockpit...');

        $configPath = function_exists('config_path') ? config_path('cockpit.php') : base_path('config/cockpit.php');
        $databasePath = function_exists('database_path') ? database_path() : base_path('database');

        if (!$this->hasDefaultOptions() || $this->option('config')) {
            $this->publish('configuration', $configPath);
        }

        if (!$this->hasDefaultOptions() || $this->option('database')) {
            $this->publish('database', $databasePath . '/cockpit.sqlite');
        }

        if (!$this->hasDefaultOptions() || $this->option('migrations')) {
            $this->publish('migrations', $databasePath . '/migrations/cockpit');
        }

        $this->info('Installed Cockpit');
    }

    private function hasDefaultOptions()
    {
        return $this->hasOption('config') || $this->hasOption('database') || $this->hasOption('migrations');
    }

    private function publish(string $fileType, string $path)
    {
        $lowerFileType = Str::lower($fileType);
        $titleFileType = Str::title($fileType);

        if (!$this->fileExists($path)) {
            $this->publishFile($lowerFileType);
        } else {
            if ($this->shouldOverwrite($titleFileType)) {
                $this->publishFile($lowerFileType, $force = true);
            }
        }
    }

    private function fileExists(string $path)
    {
        return File::exists($path);
    }

    private function shouldOverwrite(string $fileType)
    {
        return $this->confirm(
            "{$fileType} file already exists. Do you want to overwrite it?",
            false
        );
    }

    private function publishFile(string $fileType, bool $forcePublish = false)
    {
        if ($fileType == 'configuration') {
            $fileType = 'config';
        }

        $params = [
            '--provider' => "Cockpit\CockpitServiceProvider",
            '--tag' => "cockpit-{$fileType}"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
