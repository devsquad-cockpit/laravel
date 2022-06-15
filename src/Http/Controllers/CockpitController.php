<?php

namespace Cockpit\Http\Controllers;

use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CockpitController extends Controller
{
    public function index()
    {
        $cockpitErrors = Error::query()
            ->when(request()->get('unresolved'), function (Builder $query) {
                $query->whereNull('resolved_at');
            })
            ->when(request()->get('sortBy'), function (Builder $query) {
                $query->orderBy(request()->get('sortBy'), request()->get('sortDirection'));
            })->when(!request()->get('sortBy'), function (Builder $query) {
                $query->orderBy('last_occurrence_at', 'desc');
            })
            ->paginate(request()->get('perPage', 10));

        return view('cockpit::index', compact('cockpitErrors'));
    }

    public function show(Occurrence $occurrence)
    {
        $occurrence->load('error');

        return view('cockpit::show', compact('occurrence'));
    }
}
