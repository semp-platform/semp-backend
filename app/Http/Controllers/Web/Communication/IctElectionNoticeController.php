<?php

namespace App\Http\Controllers\Web\Communication;

use App\Http\Controllers\Controller;
use App\Models\Communication\ElectionNotice;
use App\Models\Election\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IctElectionNoticeController extends Controller
{
    public function index(): View
    {
        $notices = ElectionNotice::query()
            ->with('election')
            ->latest()
            ->get();

        return view('ict.election-notices.index', compact('notices'));
    }

    public function create(): View
    {
        $elections = Election::query()
            ->where('is_active', true)
            ->orderByDesc('election_date')
            ->get();

        return view('ict.election-notices.create', compact('elections'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'election_id' => [
                'required',
                'integer',
                'exists:elections,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
            ],

            'attachment_path' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        ElectionNotice::create([
            'election_id' => $validated['election_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'attachment_path' => $validated['attachment_path'] ?? null,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('staff.ict.election-notices.index')
            ->with('success', 'Election notice saved as draft.');
    }
}
