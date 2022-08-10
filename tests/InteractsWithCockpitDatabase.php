<?php

namespace Cockpit\Tests;

use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;

/**
 * @mixin TestCase
 */
trait InteractsWithCockpitDatabase
{
    protected function setMemoryDatabaseForCockpit()
    {
        app()->config->set('cockpit.database.default', 'sqlite');
        app()->config->set('database.connections.cockpit', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function refreshCockpitDatabase(): void
    {
        $this->artisan('migrate:fresh', [
            '--database' => 'cockpit',
            '--path'     => 'database/migrations/cockpit',
        ]);
    }

    public function seedCockpitDatabase(): void
    {
        $errors      = json_decode(file_get_contents(__DIR__ . '/database/errors.json'), true);
        $occurrences = json_decode(file_get_contents(__DIR__ . '/database/occurrences.json'), true);

        Error::query()->insert($errors);
        Occurrence::query()->insert($occurrences);

        Error::query()->update(['created_at' => now(), 'updated_at' => now(), 'last_occurrence_at' => now()]);
        Occurrence::query()->update(['created_at' => now(), 'updated_at' => now()]);
    }
}
