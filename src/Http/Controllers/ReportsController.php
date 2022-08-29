<?php

namespace Cockpit\Http\Controllers;

use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;

class ReportsController extends Controller
{
    public function index()
    {
        $ocurrences = Occurrence::count();
        $errors     = Error::query()
                           ->withCount('occurrences')
                           ->orderBy('occurrences_count', 'desc')
                           ->paginate(request()->get('perPage', 10))
                           ->withQueryString();

        return view('cockpit::reports.index', [
            'errors'     => $errors,
            'ocurrences' => $ocurrences,
        ]);
    }
}
