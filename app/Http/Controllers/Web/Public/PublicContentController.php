<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\PublicContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    /**
     * Display published public content.
     */
    public function index(Request $request): View
    {
        $contents = PublicContent::query()
            ->published()
            ->when(
                $request->filled('type'),
                function ($query) use ($request) {
                    $query->ofType(
                        $request->string('type')->toString()
                    );
                }
            )
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('public.content.index', [
            'contents' => $contents,
        ]);
    }


    /**
     * Display one published public content item.
     */
    public function show(string $slug): View
    {
        $content = PublicContent::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.content.show', [
            'content' => $content,
        ]);
    }
}
