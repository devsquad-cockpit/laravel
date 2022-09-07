<?php

namespace Cockpit\Tests;

use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use function Pest\Faker\faker;

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
        $errors = json_decode(file_get_contents(__DIR__ . '/database/errors.json'), true);

        $occurrences = collect(json_decode(file_get_contents(__DIR__ . '/database/occurrences.json'), true))
            ->map(function ($occurrence) {
                $occurrence['url'] = str($occurrence['url'])->replace([':8000', '?tab=request'], '');

                return $occurrence;
            })->toArray();

        Error::query()->insert($errors);
        Occurrence::query()->insert($occurrences);

        Error::query()->update(['created_at' => now(), 'updated_at' => now(), 'last_occurrence_at' => now()]);
        Occurrence::query()->update(['created_at' => now(), 'updated_at' => now()]);
    }

    public function createError($props = []): Error
    {
        $value = [
            "id"                 => faker()->uuid(),
            "exception"          => faker()->text,
            "message"            => faker()->text,
            "code"               => 0,
            "file"               => faker()->text,
            "resolved_at"        => null,
            "last_occurrence_at" => now()
        ];

        return Error::create(array_merge($value, $props));
    }

    public function createOccurrence(Error $error, $quantity = 1, $props = []): void
    {
        collect(range(1, $quantity))->each(fn () => [
            $error->occurrences()->create(
                array_merge(
                    [
                        "id"       => faker()->uuid(),
                        "type"     => faker()->text,
                        "url"      => 0,
                        "trace"    => faker()->text,
                        "debug"    => faker()->text,
                        "app"      => faker()->text,
                        "user"     => faker()->text,
                        "context"  => faker()->text,
                        "request"  => faker()->text,
                        "command"  => faker()->text,
                        "job"      => faker()->text,
                        "livewire" => faker()->text,
                    ],
                    $props
                )
            )
        ]);
    }
}
