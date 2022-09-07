<?php

use Cockpit\Cockpit;
use Cockpit\Models\Error;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Contracts\Database\Query\Builder;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    Cockpit::auth(fn () => true);

    $this->setMemoryDatabaseForCockpit();

    $this->refreshCockpitDatabase();
    $this->seedCockpitDatabase();
});

it('should retrieve an error by its id', function () {
    $error = Error::withCount([
        'occurrences',
        'occurrences as affected_users' => fn (Builder $query) => $query->where('type', 'web'),
    ])->first();

    $this->get(route('cockpit.show', $error))
        ->assertSuccessful()
        ->assertSee($error->exception)
        ->assertSee($error->message)
        ->assertSee($error->occurrences_count)
        ->assertSee($error->affected_users)
        ->assertSee('Mark as Resolved');
});

it('should not display the button to solve the issue if error is already solved', function () {
    $error = Error::inRandomOrder()->first();
    $error->markAsResolved();

    $this->get(route('cockpit.show', $error))
        ->assertDontSee('Mark as Resolved');
});

it('should not display stacktrace data if its not available', function ($column) {
    $error = Error::with('latestOccurrence')->first();
    $error->latestOccurrence->update([$column => collect([])]);

    $this->get(route('cockpit.show', $error))
        ->assertDontSee('link-' . $column);
})->with([
    'trace',
    'debug',
    'app',
    'user',
    'context',
    'request',
    'command',
    'job',
    'livewire',
]);
