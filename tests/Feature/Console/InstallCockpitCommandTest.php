<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

function removeFiles()
{
    $skeletonFiles = __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel';

    File::delete([
        $skeletonFiles . '/app/Providers/CockpitServiceProvider.php',
        $skeletonFiles . '/config/cockpit.php',
        $skeletonFiles . '/.env',
    ]);

    File::deleteDirectory($skeletonFiles . '/public/vendor/cockpit');
}

it('should force cockpit installation', function () {
    $this->artisan('cockpit:install', ['--force' => true])
        ->expectsOutput('Installed Cockpit.');
});

it('should ask user if he wants to overwrite config file', function ($type, $flag) {
    $this->artisan('cockpit:install', [$flag => true])
        ->expectsConfirmation($type . ' file already exists. Do you want to overwrite it?', 'yes')
        ->expectsOutput('Installed Cockpit.')
        ->assertSuccessful();
})->with([
    ['Configuration', '--config'],
    ['Provider', '--provider'],
]);

it('should not write cockpit service provider on app.php twice', function () {
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

    expect(substr_count($content, "App\Providers\CockpitServiceProvider::class"))
        ->toBe(1);

    $this->artisan('cockpit:install', ['--provider' => true])
        ->expectsConfirmation('Provider file already exists. Do you want to overwrite it?', 'yes')
        ->expectsOutput('Installed Cockpit.')
        ->assertSuccessful();

    $content = file_get_contents(config_path('app.php'));

    expect(substr_count($content, "App\Providers\CockpitServiceProvider::class"))
        ->not->toBe(2)
        ->toBe(1);
});

it('should not display any driver configuration message if env file exists ', function () {
    if (file_exists(base_path('.env'))) {
        unlink(base_path('.env'));
    }

    $this->artisan('cockpit:install', ['--force' => true])
        ->doesntExpectOutputToContain('Which database driver do you want to use with cockpit?')
        ->doesntExpectOutput('Env variables has been set on your .env file')
        ->assertSuccessful();
});
