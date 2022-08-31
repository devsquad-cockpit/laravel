<?php

namespace Cockpit\Tests\Unit\Reports;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Cockpit\Reports\OccurrencesReport;
use Cockpit\Tests\InteractsWithCockpitDatabase;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});

function getLabels(Carbon $from, Carbon $to): array
{
    $period = [];

    foreach (CarbonPeriod::create($from, $to) as $day) {
        $period[] = $day->format('y/m/d');
    }

    return $period;
}

it('should count errors on database', function () {
    $firstError = $this->createError();
    $this->createOccurrence($firstError);

    $lastError = $this->createError(['last_occurrence_at' => now()->subDay(), 'resolved_at' => now()->subDay()]);
    $this->createOccurrence($lastError, 2, ['created_at' => now()->subDay()]);

    $from = now()->subDays(2);
    $to   = now();

    $report = new OccurrencesReport($from, $to);

    expect($report->getLabels())
        ->toBe(getLabels($from, $to))
        ->and($report->getUnsolvedErrors())
        ->toBe([
            0,
            0,
            1
        ])
        ->and($report->getTotalErrors())
        ->toBe([
            0,
            2,
            1,
        ]);
});
