<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePoliticalPartyRequest;
use App\Http\Requests\Admin\UpdatePoliticalPartyRequest;
use App\Models\Party\PoliticalParty;
use App\Services\Party\PoliticalPartyService;

class PoliticalPartyController extends Controller
{
    public function __construct(
        protected PoliticalPartyService $service
    ) {}

    public function index()
    {
        return view('admin.political-parties.index', [
            'parties' => $this->service->all(),
        ]);
    }

    public function create()
    {
        return view('admin.political-parties.create');
    }

    public function store(StorePoliticalPartyRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('admin.political-parties.index')
            ->with('success', 'Political Party created successfully.');
    }

    public function edit(PoliticalParty $politicalParty)
    {
        return view('admin.political-parties.edit', compact('politicalParty'));
    }

    public function update(
        UpdatePoliticalPartyRequest $request,
        PoliticalParty $politicalParty
    ) {
        $this->service->update(
            $politicalParty,
            $request->validated()
        );

        return redirect()
            ->route('admin.political-parties.index')
            ->with('success', 'Political Party updated successfully.');
    }
    public function activate(PoliticalParty $politicalParty)
{
    $this->service->activate($politicalParty);

    return back()->with('success', 'Political Party activated successfully.');
}

public function deactivate(PoliticalParty $politicalParty)
{
    $this->service->deactivate($politicalParty);

    return back()->with('success', 'Political Party deactivated successfully.');
}
}
