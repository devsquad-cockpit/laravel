<?php

namespace Cockpit\Tests\Feature\InstallCockpitCommand;

use Illuminate\Database\Events\MigrationStarted;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Str;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertMatchesRegularExpression;
use function PHPUnit\Framework\assertTrue;

it('should not migrate database automatically if cockpit db driver is different from sqlite', function () {
    if (file_exists(base_path('.env'))) {
        unlink(base_path('.env'));
    }

    if (file_exists(database_path('cockpit.sqlite'))) {
        unlink(database_path('cockpit.sqlite'));
    }

    file_put_contents(base_path('.env'), '');

    $this->loadEnvironmentVariables = true;

    Event::fake();

    $this->artisan('cockpit:install')
        ->expectsChoice('Which database driver do you want to use with cockpit?', 'mysql', [
            'mysql',
            'pgsql',
            'sqlite',
            'sqlsrv',
        ])
        ->expectsConfirmation('Configuration file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Migrations file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Assets file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Provider file already exists. Do you want to overwrite it?', 'yes')
        ->expectsOutput('Installed Cockpit.')
        ->assertSuccessful();

    Event::assertNotDispatched(MigrationStarted::class);

    assertFalse(file_exists(
        database_path('cockpit.sqlite')
    ));

    $env = file_get_contents(base_path('.env'));

    assertTrue(Str::contains($env, 'COCKPIT_CONNECTION=mysql'));
    assertTrue(Str::contains($env, 'COCKPIT_DB_PORT'));
    assertTrue(Str::contains($env, 'COCKPIT_DB_HOST'));
    assertTrue(Str::contains($env, 'COCKPIT_DB_DATABASE'));
    assertTrue(Str::contains($env, 'COCKPIT_DB_USERNAME'));
    assertTrue(Str::contains($env, 'COCKPIT_DB_PASSWORD'));
});
