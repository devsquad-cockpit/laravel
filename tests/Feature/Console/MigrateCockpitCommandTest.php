<?php

use Illuminate\Database\Events\MigrationStarted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;

const DATABASE_PATH = __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel/database/cockpit.sqlite';

it('should migrate the cockpit database', function () {
    file_put_contents(DATABASE_PATH, '');

    Event::fake();

    $this->artisan('cockpit:migrate')
        ->expectsOutput('Cockpit database has been migrated!')
        ->assertSuccessful();

    Event::assertDispatched(MigrationStarted::class);

    $schema = Schema::connection('cockpit');

    expect($schema->hasTable('errors'))
        ->toBeTruthy()
        ->and($schema->hasTable('occurrences'))->toBeTruthy();
});

it('should force laravel to run cockpit migrations', function () {
    file_put_contents(DATABASE_PATH, '');

    Event::fake();

    $this->artisan('cockpit:migrate', ['--force' => true])
        ->expectsOutput('Cockpit database has been migrated!')
        ->assertSuccessful();

    Event::assertDispatched(MigrationStarted::class);
});

it('should not refresh database without user agreement', function () {
    Event::fake();

    $this->artisan('cockpit:migrate', ['--refresh' => true])
        ->expectsConfirmation('This operation will wipe out all cockpit data. Do you want to continue?')
        ->expectsOutput('The operation has been cancelled.')
        ->assertFailed();

    Event::assertNotDispatched(MigrationStarted::class);
});

it('should refresh database with user agreement', function () {
    Event::fake();

    $this->artisan('cockpit:migrate', ['--refresh' => true])
        ->expectsConfirmation('This operation will wipe out all cockpit data. Do you want to continue?', 'yes')
        ->expectsOutput('Cockpit database has been migrated!')
        ->assertSuccessful();

    Event::assertDispatched(MigrationStarted::class);
});
