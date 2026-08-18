<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use Illuminate\View\View;

class ElectionController extends Controller
{
    /**
     * Display elections available to the public.
     */
    public function index(): View
    {
        $elections = Election::query()
            ->with([
                'electionType',
                'state',
            ])
            ->where('is_active', true)
            ->latest('election_date')
            ->get();

        return view('public.elections.index', [
            'elections' => $elections,
        ]);
    }

    /**
     * Display a public election overview.
     */
    public function show(Election $election): View
    {
        abort_unless($election->is_active, 404);

        $election->load([
            'electionType',
            'state',
            'lga',
            'ward',
            'lcda',
            'electionPositions',
        ]);

        return view('public.elections.show', [
            'election' => $election,
        ]);
    }
}
