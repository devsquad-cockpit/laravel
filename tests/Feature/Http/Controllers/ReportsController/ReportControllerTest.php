<?php

namespace Cockpit\Tests\Feature\Http\Controllers\ReportsController;

use Carbon\CarbonPeriod;
use Cockpit\Cockpit;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Support\Carbon;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    Cockpit::auth(fn () => true);

    $this->setMemoryDatabaseForCockpit();

    $this->refreshCockpitDatabase();
});

it('should be mount errors occurrences', function () {
    $now    = Carbon::now();
    $labels = createLabels(Carbon::now()->subDays(6), $now);

    $firstError = $this->createError();
    $this->createOccurrence($firstError, 2);

    $secondError = $this->createError([
        'last_occurrence_at' => $now->subDays(1),
        'resolved_at'        => $now,
    ]);
    $this->createOccurrence($secondError);

    $this->get('cockpit/reports')
        ->assertSuccessful()
        ->assertViewIs('cockpit::reports.index')
        ->assertViewHas('totalErrors', [
            0,
            0,
            0,
            0,
            0,
            1,
            2,
        ])
        ->assertViewHas('unresolvedErrors', [
            0,
            0,
            0,
            0,
            0,
            0,
            2,
        ])
        ->assertViewHas('labels', $labels);
});

it('should be mount errors occurrences with to and from params', function () {
    $now    = Carbon::now();
    $labels = createLabels(Carbon::now()->subDays(6), $now);

    $firstError = $this->createError();
    $this->createOccurrence($firstError, 2);

    $secondError = $this->createError([
        'last_occurrence_at' => $now->subDays(1),
        'resolved_at'        => $now,
    ]);
    $this->createOccurrence($secondError);

    $this->get('cockpit/reports')
        ->assertSuccessful()
        ->assertViewIs('cockpit::reports.index')
        ->assertViewHas('totalErrors', [
            0,
            0,
            0,
            0,
            0,
            1,
            2,
        ])
        ->assertViewHas('unresolvedErrors', [
            0,
            0,
            0,
            0,
            0,
            0,
            2,
        ])
        ->assertViewHas('labels', $labels);
});

function createLabels($to, $from): array
{
    $period = CarbonPeriod::create($to, $from);

    foreach ($period as $value) {
        $labels[] = $value->format('y/m/d');
    }
    $labels[] = $from->format('y/m/d');

    return $labels;
}
