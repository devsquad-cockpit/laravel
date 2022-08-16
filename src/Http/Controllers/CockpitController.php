<?php

namespace Cockpit\Http\Controllers;

use Cockpit\Events\ErrorReport;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Notifications\ErrorNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class CockpitController extends Controller
{
    public function index()
    {
        $error = Error::query()->first();
        // event(new ErrorReport($error));
        Notification::route('mail', 'taylor@example.com')->notify(new ErrorNotification($error));

        return;

        $cockpitErrors = Error::select('*')
            ->selectSub(function ($query) {
                $query->select('url')
                    ->from('occurrences')
                    ->whereColumn('occurrences.error_id', '=', 'errors.id')
                    ->latest()
                    ->limit(1);
            }, 'url')
            ->withCount([
                'occurrences',
                'occurrences as affected_users_count' => fn (Builder $query) => $query->errorsFromWeb(),
            ])
            ->search(request()->get('search'))
            ->betweenDates(request()->get('from'), request()->get('to'))
            ->when(request()->get('unresolved'), fn (Builder $query) => $query->unresolved())
            ->when(
                request()->get('sortBy'),
                function (Builder $query) {
                    $query->orderBy(request()->get('sortBy'), request()->get('sortDirection'));
                },
                function (Builder $query) {
                    $query->orderBy('last_occurrence_at', 'desc');
                }
            )
            ->paginate(request()->get('perPage', 10));

        $occurrencesPerDay = Occurrence::averageOccurrencesPerDay();
        $totalErrors       = Error::count();
        $totalOccurrences  = Occurrence::count();
        $unresolvedErrors  = Error::unresolved()->count();
        $errorsLastHour    = Occurrence::onLastHour()->count();

        return view('cockpit::index', [
            'cockpitErrors'     => $cockpitErrors,
            'totalErrors'       => $totalErrors,
            'unresolvedErrors'  => $unresolvedErrors,
            'errorsLastHour'    => $errorsLastHour,
            'occurrencesPerDay' => $occurrencesPerDay,
            'totalOccurrences'  => $totalOccurrences,
        ]);
    }

    public function show(Error $cockpitError)
    {
        $cockpitError->loadMissing('latestOccurrence')
            ->loadCount([
                'occurrences',
                'occurrences as affected_users_count' => fn (Builder $query) => $query->errorsFromWeb()
            ]);

        return view('cockpit::show', compact('cockpitError'));
    }

    public function resolve(Error $cockpitError)
    {
        $redirector = redirect()
            ->route('cockpit.show', $cockpitError->id)
            ->with('toast', true);

        if ($cockpitError->markAsResolved()) {
            return $redirector
                ->with('type', 'success')
                ->with('message', 'The error has been resolved');
        }

        return $redirector
            ->with('type', 'error')
            ->with('message', 'An error occurs while performing this operation. Please try again');
    }
}
