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
        $skeletonFiles . '/database/cockpit.sqlite',
        $skeletonFiles . '/.env',
    ]);

    File::deleteDirectory($skeletonFiles . '/database/migrations/cockpit');
    File::deleteDirectory($skeletonFiles . '/public/vendor/cockpit');
}

it('should install cockpit and run migrations', function () {
    removeFiles();

    file_put_contents(base_path('.env'), '');

    $this->artisan('cockpit:install')
        ->expectsChoice('Which database driver do you want to use with cockpit?', 'sqlite', [
            'mysql',
            'pgsql',
            'sqlite',
            'sqlsrv',
        ])
        ->expectsOutput('Env variables has been set on your .env file')
        ->expectsOutput('Installed Cockpit.')
        ->assertSuccessful();

    expect(file_exists(app_path('Providers/CockpitServiceProvider.php')))
        ->toBeTruthy()
        ->and(file_exists(config_path('cockpit.php')))->toBeTruthy()
        ->and(file_exists(public_path('vendor/cockpit/js/app.js')))->toBeTruthy()
        ->and(file_exists(public_path('vendor/cockpit/css/app.css')))->toBeTruthy()
        ->and(file_exists(database_path('cockpit.sqlite')))->toBeTruthy()
        ->and(Schema::connection('cockpit')->hasTable('errors'))->toBeTruthy()
        ->and(Schema::connection('cockpit')->hasTable('occurrences'))->toBeTruthy()
        ->and(
            file_exists(database_path('migrations/cockpit/2022_06_14_130836_create_cockpit_errors_table.php'))
        )->toBeTruthy()
        ->and(
            file_exists(database_path('migrations/cockpit/2022_07_12_165034_create_occurrences_table.php'))
        )->toBeTruthy();

    $env = file_get_contents(base_path('.env'));

    expect(Str::contains($env, 'COCKPIT_CONNECTION=sqlite'))
        ->toBeTruthy()
        ->and(Str::contains($env, 'COCKPIT_DB_PORT'))->toBeFalsy()
        ->and(Str::contains($env, 'COCKPIT_DB_HOST'))->toBeFalsy()
        ->and(Str::contains($env, 'COCKPIT_DB_DATABASE'))->toBeFalsy()
        ->and(Str::contains($env, 'COCKPIT_DB_USERNAME'))->toBeFalsy()
        ->and(Str::contains($env, 'COCKPIT_DB_PASSWORD'))->toBeFalsy();
});

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
    ['Migrations', '--migrations'],
    ['Assets', '--assets'],
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