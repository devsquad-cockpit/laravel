<?php

namespace Cockpit\Tests\Feature\InstallCockpitCommand;

use Illuminate\Support\Facades\Schema;

use function PHPUnit\Framework\assertTrue;

it('should install cockpit and run migrations', function () {
    file_put_contents(base_path('.env'), '');

    if (!file_exists(database_path('cockpit.sqlite'))) {
        file_put_contents(database_path('cockpit.sqlite'), '');
    }

    $this->artisan('cockpit:install')
        ->expectsChoice('Which database driver do you want to use with cockpit?', 'sqlite', [
            'mysql',
            'pgsql',
            'sqlite',
            'sqlsrv',
        ])
        ->expectsConfirmation('Configuration file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Database file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Migrations file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Assets file already exists. Do you want to overwrite it?', 'yes')
        ->expectsConfirmation('Provider file already exists. Do you want to overwrite it?', 'yes')
        ->expectsOutput('Installed Cockpit.')
        ->assertSuccessful();

    assertTrue(file_exists(app_path('Providers/CockpitServiceProvider.php')));
    assertTrue(file_exists(config_path('cockpit.php')));

    assertTrue(file_exists(
        database_path('migrations/cockpit/2022_06_14_130836_create_cockpit_errors_table.php')
    ));

    assertTrue(file_exists(
        database_path('migrations/cockpit/2022_07_12_165034_create_occurrences_table.php')
    ));

    assertTrue(file_exists(
        public_path('vendor/cockpit/css/app.css')
    ));

    assertTrue(file_exists(
        public_path('vendor/cockpit/js/app.js')
    ));

    assertTrue(file_exists(
        database_path('cockpit.sqlite')
    ));

    assertTrue(Schema::connection('cockpit')->hasTable('errors'));
    assertTrue(Schema::connection('cockpit')->hasTable('occurrences'));
});
