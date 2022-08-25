<?php

namespace Cockpit\Tests\Feature\Http\Controllers\CockpitController;

use Cockpit\Cockpit;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Tests\InteractsWithCockpitDatabase;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    Cockpit::auth(fn () => true);

    $this->setMemoryDatabaseForCockpit();

    $this->refreshCockpitDatabase();
    $this->seedCockpitDatabase();
});

it('should see the basic cockpit page', function () {
    $this->get('/cockpit')
        ->assertSuccessful()
        ->assertSee('Occurrences per day - Average')
        ->assertSee('Occurrences in the last hour')
        ->assertSee('Unresolved errors')
        ->assertSee('Errors')
        ->assertSee('Reports')
        ->assertViewIs('cockpit::index')
        ->assertViewHas('cockpitErrors')
        ->assertViewHas('totalErrors', 7)
        ->assertViewHas('unresolvedErrors', 7)
        ->assertViewHas('errorsLastHour', 31)
        ->assertViewHas('occurrencesPerDay', 31)
        ->assertViewHas('totalOccurrences', 31);
});

// it('should display error in the main page', function () {
//     $error = Error::with('latestOccurrence')->first();

//     $totalOccurrences = $error->occurrences()->count();
//     $affectedUsers    = $error->occurrences()->where('type', Occurrence::TYPE_WEB)->count();

//     $this->get('/cockpit')
//         ->assertSuccessful()
//         ->assertSee($error->exception)
//         ->assertSee($error->message)
//         ->assertSee($error->latestOccurrence->url)
//         ->assertSee($totalOccurrences)
//         ->assertSee($affectedUsers);
// });

it('should search search occurrence by exception or message', function () {
    $firstError = Error::first();
    $lastError  = Error::orderByDesc('id')->first();

    $this->get(route('cockpit.index', ['search' => $firstError->exception]))
        ->assertSuccessful()
        ->assertSee($firstError->exception)
        ->assertDontSee($lastError->exception);
});

it('should get errors between given dates', function () {
    $errors = Error::inRandomOrder()->limit(2)->get();

    $searchDate = now()->subWeek();

    Error::whereIn('id', $errors->pluck('id')->toArray())->update([
        'last_occurrence_at' => $searchDate,
    ]);

    $response = $this->get(
        route('cockpit.index', [
            'from' => (clone $searchDate)->subDay()->format('y/m/d'),
            'to'   => (clone $searchDate)->addDay()->format('y/m/d'),
        ])
    );

    $errors->each(fn (Error $error) => $response->assertSee($error->id));

    $cockpitErrors = $response->viewData('cockpitErrors');

    expect($cockpitErrors)
        ->toHaveCount(2);
});

it('should bring only unresolved errors', function () {
    $error = Error::inRandomOrder()->first();
    $error->markAsResolved();

    $this->get(route('cockpit.index', ['unresolved' => 1]))
        ->assertDontSee($error->id);
});

it('should order errors by given field', function () {
    $errors = Error::withCount('occurrences')
        ->orderBy('occurrences_count', 'desc')
        ->get();

    $this->get(route('cockpit.index', [
        'sortBy'        => 'occurrences_count',
        'sortDirection' => 'desc'
    ]))->assertSeeInOrder($errors->pluck('id')->toArray());
});
