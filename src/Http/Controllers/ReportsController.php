<?php

namespace Cockpit\Http\Controllers;

use Carbon\Carbon;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Reports\OccurrencesReport;
use Cockpit\Traits\InteractsWithDates;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    use InteractsWithDates;

    public function index(Request $request)
    {
        $from = $request->date('from', 'y/m/d') ?? Carbon::now()->subDays(6);
        $to   = $request->date('to', 'y/m/d')   ?? Carbon::now();

        $this->interactWithExceededDates($from, $to, function () use (&$from, &$to) {
            $from = Carbon::now()->subDays(6);
            $to   = Carbon::now();
        });

        $report = new OccurrencesReport($from, $to);

        $errors = Error::query()
            ->whereBetween('last_occurrence_at', [$from->startOfDay(), $to->endOfDay()])
            ->withCount('occurrences')
            ->orderBy('occurrences_count', 'desc')
            ->paginate(request()->get('perPage', 10))
            ->withQueryString();

        $occurrences = Occurrence::query()->whereIn('error_id', collect($errors->items())->pluck('id'))->count();

        return view('cockpit::reports.index', [
            'unresolvedErrors' => $report->getUnsolvedErrors(),
            'totalErrors'      => $report->getTotalErrors(),
            'labels'           => $report->getLabels(),
            'errors'           => $errors,
            'occurrences'      => $occurrences,
            'from'             => $from->format('y/m/d'),
            'to'               => $to->format('y/m/d'),
        ]);
    }
}
