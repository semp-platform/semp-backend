<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\ResultImport;
use App\Models\ResultEntry;
use App\Models\Reference\Ward;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Public election results index.
     */
    public function index(): View
    {
        $results = ResultImport::query()
            ->with([
                'election',
                'position',
            ])
            ->where('status', 'published')
            ->latest('published_at')
            ->get();

        return view('public.results.index', [
            'results' => $results,
        ]);
    }

    /**
     * Public result details.
     */
    public function show(ResultImport $resultImport): View
    {
        abort_unless(
            $resultImport->status === 'published',
            404
        );

        $resultImport->load([
            'election',
            'position',
        ]);

        $entries = ResultEntry::query()
            ->with([
                'lga',
                'ward',
                'politicalParty',
            ])
            ->where('result_import_id', $resultImport->id)
            ->orderBy('lga_id')
            ->orderBy('ward_id')
            ->orderBy('polling_unit_code')
            ->orderBy('political_party_id')
            ->get();

        $totalVotes = $entries->sum('votes');

        /*
        |--------------------------------------------------------------------------
        | Party totals
        |--------------------------------------------------------------------------
        */

        $parties = $entries
            ->groupBy('political_party_id')
            ->map(function ($partyEntries) {
                return [
                    'party' => $partyEntries->first()->politicalParty,
                    'votes' => $partyEntries->sum('votes'),
                ];
            })
            ->filter(function ($item) {
                return ($item['votes'] ?? 0) > 0;
            })
            ->sortByDesc('votes')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | LGA / Ward grouping
        |--------------------------------------------------------------------------
        */

        $lgas = $entries
            ->groupBy('lga_id')
            ->map(function ($lgaEntries) {

                $wards = $lgaEntries
                    ->groupBy('ward_id')
                    ->map(function ($wardEntries) {

                        $partyTotals = $wardEntries
                            ->groupBy('political_party_id')
                            ->map(function ($partyEntries) {
                                return [
                                    'party' => $partyEntries->first()->politicalParty,
                                    'votes' => $partyEntries->sum('votes'),
                                ];
                            })
                            ->filter(function ($item) {
                                return ($item['votes'] ?? 0) > 0;
                            })
                            ->sortByDesc('votes')
                            ->values();

                        return [
                            'ward' => $wardEntries->first()->ward,

                            'polling_units' => $wardEntries
                                ->groupBy(function ($entry) {
                                    return $entry->polling_unit_code;
                                })
                                ->count(),

                            'votes' => $wardEntries->sum('votes'),

                            'winner' => $partyTotals->first(),

                            'parties' => $partyTotals,
                        ];
                    })
                    ->sortBy(function ($item) {
                        return $item['ward']->name ?? '';
                    })
                    ->values();

                $lgaPartyTotals = $lgaEntries
                    ->groupBy('political_party_id')
                    ->map(function ($partyEntries) {
                        return [
                            'party' => $partyEntries->first()->politicalParty,
                            'votes' => $partyEntries->sum('votes'),
                        ];
                    })
                    ->sortByDesc('votes')
                    ->values();

                return [
                    'lga' => $lgaEntries->first()->lga,

                    'polling_units' => $lgaEntries
                        ->groupBy(function ($entry) {
                            return $entry->ward_id . ':' .
                                $entry->polling_unit_code;
                        })
                        ->count(),

                    'votes' => $lgaEntries->sum('votes'),

                    'winner' => $lgaPartyTotals->first(),

                    'wards' => $wards,
                ];
            })
            ->sortBy(function ($item) {
                return $item['lga']->name ?? '';
            })
            ->values();

        return view('public.results.show', [
            'resultImport' => $resultImport,
            'entries' => $entries,
            'totalVotes' => $totalVotes,
            'parties' => $parties,
            'lgas' => $lgas,
        ]);
    }

    /**
     * Public ward results.
     */
    public function ward(
        ResultImport $resultImport,
        Ward $ward
    ): View {
        abort_unless(
            $resultImport->status === 'published',
            404
        );

        $entries = ResultEntry::query()
            ->with([
                'lga',
                'ward',
                'politicalParty',
            ])
            ->where('result_import_id', $resultImport->id)
            ->where('ward_id', $ward->id)
            ->orderBy('polling_unit_code')
            ->orderBy('political_party_id')
            ->get();

        abort_if(
            $entries->isEmpty(),
            404
        );

        $resultImport->load([
            'election',
            'position',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Party totals in this ward
        |--------------------------------------------------------------------------
        */

        $parties = $entries
            ->groupBy('political_party_id')
            ->map(function ($partyEntries) {
                return [
                    'party' => $partyEntries->first()->politicalParty,
                    'votes' => $partyEntries->sum('votes'),
                ];
            })
            ->filter(function ($item) {
                return ($item['votes'] ?? 0) > 0;
            })
            ->sortByDesc('votes')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Polling-unit results
        |--------------------------------------------------------------------------
        */

        $pollingUnits = $entries
            ->groupBy('polling_unit_code')
            ->map(function ($unitEntries) {

                $partyTotals = $unitEntries
                    ->groupBy('political_party_id')
                    ->map(function ($partyEntries) {
                        return [
                            'party' => $partyEntries->first()->politicalParty,
                            'votes' => $partyEntries->sum('votes'),
                        ];
                    })
                    ->sortByDesc('votes')
                    ->values();

                return [
                    'code' => $unitEntries->first()->polling_unit_code,

                    'name' => $unitEntries->first()->polling_unit_name,

                    'votes' => $unitEntries->sum('votes'),

                    'winner' => $partyTotals->first(),

                    'parties' => $partyTotals,
                ];
            })
            ->sortBy('code')
            ->values();

        return view('public.results.ward', [
            'resultImport' => $resultImport,
            'ward' => $ward,
            'lga' => $entries->first()->lga,
            'parties' => $parties,
            'pollingUnits' => $pollingUnits,
            'totalVotes' => $entries->sum('votes'),
        ]);
    }
}
