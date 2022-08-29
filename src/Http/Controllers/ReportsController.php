<?php

namespace Cockpit\Http\Controllers;

use Carbon\CarbonPeriod;
use Cockpit\Models\Error;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $from = request()->get('from', Carbon::now()->subDays(6)->format('y/m/d'));
        $to   = request()->get('to', Carbon::now()->format('y/m/d'));

        $period = CarbonPeriod::create(Carbon::createFromFormat('y/m/d', $from), Carbon::createFromFormat('y/m/d', $to));

        foreach ($period as $value) {
            $labels[] = $value->format('y/m/d');

            $unresolvedErrors[] = Error::whereNull('resolved_at')
                ->whereDate('errors.last_occurrence_at', $value)
                ->join('occurrences', 'errors.id', '=', 'occurrences.error_id')
                ->select(DB::raw('COUNT(occurrences.id) as occurrences_count'))
                ->groupBy('day')
                ->count('occurrences_count');

            $totalErros[] = Error::whereDate('errors.last_occurrence_at', $value)
            ->join('occurrences', 'errors.id', '=', 'occurrences.error_id')
            ->select(DB::raw('COUNT(occurrences.id) as occurrences_count'))
            ->groupBy('day')
            ->count('occurrences_count');
        }

        return view(
            'cockpit::reports.index',
            compact('unresolvedErrors', 'totalErros', 'labels', 'from')
        );
    }
}
