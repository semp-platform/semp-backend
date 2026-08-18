<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidate\CandidateWithdrawal;
use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Nomination\Nomination;
use App\Models\Party\PoliticalParty;
use App\Models\Reference\Lga;
use App\Models\Reference\State;use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateController extends Controller
{
    /**
     * Display the public candidate register.
     */
    public function index(Request $request): View
    {
        $elections = Election::query()
            ->where('is_active', true)
            ->orderByDesc('election_date')
            ->get();

        $parties = PoliticalParty::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $positions = Position::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $lgas = Lga::query()
    ->where('is_active', true)
    ->whereHas('state', function (Builder $query) {
        $query
            ->where('name', 'Ogun')
            ->where('is_active', true);
    })
    ->orderBy('name')
    ->get();

        $nominations = Nomination::query()
            ->with([
                'election.electionType',
                'politicalParty',
                'candidate',
                'position',
                'lga',
                'lcda',
                'ward',
            ])

            /*
             * Only approved nominations are public.
             */
            ->where(
                'status',
                Nomination::STATUS_APPROVED
            )

            /*
             * Do not publish a candidate whose withdrawal
             * has subsequently been approved.
             */
            ->whereDoesntHave(
                'withdrawal',
                function (Builder $query) {
                    $query->where(
                        'status',
                        CandidateWithdrawal::STATUS_APPROVED
                    );
                }
            )

            /*
             * Only candidates belonging to active elections
             * are visible on the public website.
             */
            ->whereHas(
                'election',
                function (Builder $query) {
                    $query->where('is_active', true);
                }
            )

            /*
             * Election filter.
             */
            ->when(
                $request->filled('election'),
                function (Builder $query) use ($request) {
                    $query->where(
                        'election_id',
                        $request->integer('election')
                    );
                }
            )

            /*
             * Political party filter.
             */
            ->when(
                $request->filled('party'),
                function (Builder $query) use ($request) {
                    $query->where(
                        'political_party_id',
                        $request->integer('party')
                    );
                }
            )

            /*
             * Position filter.
             */
            ->when(
                $request->filled('position'),
                function (Builder $query) use ($request) {
                    $query->where(
                        'position_id',
                        $request->integer('position')
                    );
                }
            )

            /*
             * LGA filter.
             */
            ->when(
    $request->filled('lga'),
    function (Builder $query) use ($request) {
        $query
            ->where('lga_id', $request->integer('lga'))
            ->whereHas('lga.state', function (Builder $stateQuery) {
                $stateQuery
                    ->where('name', 'Ogun')
                    ->where('is_active', true);
            });
    }
)
            /*
             * Candidate / party search.
             */
            ->when(
                $request->filled('search'),
                function (Builder $query) use ($request) {

                    $search = trim(
                        (string) $request->input('search')
                    );

                    $query->where(function (Builder $query) use ($search) {

                        $query->whereHas(
                            'candidate',
                            function (Builder $candidateQuery) use ($search) {

                                $candidateQuery
                                    ->where(
                                        'first_name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );

                        $query->orWhereHas(
                            'politicalParty',
                            function (Builder $partyQuery) use ($search) {

                                $partyQuery
                                    ->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'acronym',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    });
                }
            )

            /*
             * Stable public ordering.
             */
            ->orderByDesc('id')

            /*
             * Only retrieve one page at a time.
             */
            ->paginate(25)

            /*
             * Preserve filters when moving between pages.
             */
            ->withQueryString();

        return view('public.candidates.index', [
            'nominations' => $nominations,
            'elections' => $elections,
            'parties' => $parties,
            'positions' => $positions,
            'lgas' => $lgas,
        ]);
    }


    /**
     * Display one approved candidate.
     */
    public function show(Nomination $nomination): View
    {
        abort_unless(
            $nomination->status === Nomination::STATUS_APPROVED,
            404
        );

        abort_if(
            $nomination->withdrawal?->status
                === CandidateWithdrawal::STATUS_APPROVED,
            404
        );

        $nomination->load([
            'election.electionType',
            'politicalParty',
            'candidate',
            'position',
            'lga',
            'lcda',
            'ward',
            'withdrawal',
        ]);

        abort_unless(
            $nomination->election?->is_active,
            404
        );

        abort_unless(
            $nomination->candidate?->is_active,
            404
        );

        return view('public.candidates.show', [
            'nomination' => $nomination,
        ]);
    }
}
