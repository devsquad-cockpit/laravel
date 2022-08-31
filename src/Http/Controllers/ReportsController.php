<?php

namespace Cockpit\Http\Controllers;

use Carbon\Carbon;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Reports\OccurrencesReport;

class ReportsController extends Controller
{
    public function index()
    {
        $from = request()->date('from', 'y/m/d') ?? Carbon::now()->subDays(6);
        $to   = request()->date('to', 'y/m/d')   ?? Carbon::now();

        $report = new OccurrencesReport($from, $to);

        $labels = $report->getLabels();

        $unresolvedErrors = $report->getUnsolvedErrors();
        $totalErrors      = $report->getTotalErrors();

        $errors = Error::query()
            ->withCount('occurrences')
            ->orderBy('occurrences_count', 'desc')
            ->paginate(request()->get('perPage', 10))
            ->withQueryString();

        $occurrences = Occurrence::count();

        return view(
            'cockpit::reports.index',
            compact('unresolvedErrors', 'totalErrors', 'labels', 'from', 'errors', 'occurrences')
        );
    }
}
