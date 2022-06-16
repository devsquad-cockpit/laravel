<?php

namespace Cockpit\Http\Controllers;

use Cockpit\Models\Error;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CockpitController extends Controller
{
    public function index()
    {
        $cockpitErrors = Error::query()
            ->when(request()->get('search'), function (Builder $query) {
                $query->where(function (Builder $query) {
                    $search = request()->get('search');

                    $query->where('exception', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })
            ->when(request()->get('unresolved'), function (Builder $query) {
                $query->whereNull('resolved_at');
            })
            ->when(request()->get('from') && request()->get('to'), function (Builder $query) {
                $from = Carbon::createFromFormat('y/m/d', request()->get('from'))->startOfDay();
                $to   = Carbon::createFromFormat('y/m/d', request()->get('to'))->endOfDay();

                $query->whereBetween('last_occurrence_at', [$from, $to]);
            })
            ->when(request()->get('sortBy'), function (Builder $query) {
                $query->orderBy(request()->get('sortBy'), request()->get('sortDirection'));
            })->when(!request()->get('sortBy'), function (Builder $query) {
                $query->orderBy('last_occurrence_at', 'desc');
            })
            ->paginate(request()->get('perPage', 10));

        return view('cockpit::index', compact('cockpitErrors'));
    }

    public function show(Error $cockpitError)
    {
        return view('cockpit::show', compact('cockpitError'));
    }

    public function resolve(Error $cockpitError)
    {
        if ($cockpitError->markAsResolved()) {
            return redirect()
                ->route('cockpit.show', $cockpitError->uuid)
                ->with('success', 'The error has been resolved');
        }

        return redirect()
            ->route('cockpit.show', $cockpitError->uuid)
            ->with('error', 'An error occurs while performing this operation. Please try again');
    }
}
