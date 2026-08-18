<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use App\Models\PublicContent;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the public OGSIEC homepage.
     */
    public function index(): View
    {
        $elections = Election::query()
            ->with([
                'electionType',
                'state',
                'lga',
                'ward',
                'lcda',
            ])
            ->where('is_active', true)
            ->orderBy('election_date')
            ->get();

        $currentElection = $elections->first();

        $ticker = PublicContent::query()
            ->published()
            ->ticker()
            ->orderBy('sort_order')
            ->latest('published_at')
            ->get();

       $featured = PublicContent::query()
    ->published()
    ->featured()
    ->orderBy('sort_order')
    ->latest('published_at')
    ->take(5)
    ->get();

        $latestNews = PublicContent::query()
            ->published()
            ->ofType(PublicContent::TYPE_NEWS)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->take(6)
            ->get();

        $latestNotices = PublicContent::query()
            ->published()
            ->ofType(PublicContent::TYPE_NOTICE)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->take(4)
            ->get();

        $announcements = PublicContent::query()
            ->published()
            ->ofType(PublicContent::TYPE_ANNOUNCEMENT)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->take(4)
            ->get();

            $lgaCount = Lga::query()
    ->where('state_id', 28)
    ->count();

$wardCount = 236;

$pollingUnitCount = 5042;


            $homeServices = PublicContent::query()
    ->published()
    ->where('is_home_service', true)
    ->orderBy('sort_order')
    ->latest('published_at')
    ->take(4)
    ->get();

        return view('public.home', [
            'currentElection' => $currentElection,
            'elections' => $elections,
            'ticker' => $ticker,
            'featured' => $featured,
            'latestNews' => $latestNews,
            'latestNotices' => $latestNotices,
            'announcements' => $announcements,
            'homeServices' => $homeServices,
            'lgaCount' => $lgaCount,
'wardCount' => $wardCount,
'pollingUnitCount' => $pollingUnitCount,
        ]);
    }
}
