@extends('layouts.staff')

@section('content')

<div class="space-y-6">

    {{-- Back --}}
    <div>
        <a
            href="{{ route('staff.legal.nominations.index') }}"
            class="text-sm font-medium text-blue-600 hover:underline"
        >
            ← Back to Legal Review
        </a>
    </div>

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Legal Review
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Review the nomination and supporting documentation before
                forwarding it to the Commissioner.
            </p>
        </div>

        <div class="rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-800">
            Under Legal Review
        </div>

    </div>

    {{-- Candidate / nomination details --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="text-lg font-semibold text-slate-900">
                Candidate Information
            </h2>

            <dl class="mt-5 space-y-4">

                <div>
                    <dt class="text-sm text-slate-500">
                        Candidate
                    </dt>

                    <dd class="mt-1 font-medium text-slate-900">
                        {{ $nomination->candidate_name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Political Party
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ $nomination->politicalParty?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Election
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ $nomination->election_type ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Position
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ $nomination->position?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Electoral Area
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ $nomination->electoral_area ?? '—' }}
                    </dd>
                </div>

            </dl>

        </div>

        {{-- Workflow status --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="text-lg font-semibold text-slate-900">
                Workflow Status
            </h2>

            <dl class="mt-5 space-y-4">

                <div>
                    <dt class="text-sm text-slate-500">
                        Current Department
                    </dt>

                    <dd class="mt-1 font-medium text-slate-900">
                        Legal
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Status
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ str_replace('_', ' ', ucfirst($nomination->status)) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Workflow Status
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ str_replace('_', ' ', ucfirst($nomination->workflow_status)) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Received
                    </dt>

                    <dd class="mt-1 text-slate-900">
                        {{ $nomination->received_at?->format('d M Y, H:i') ?? '—' }}
                    </dd>
                </div>

            </dl>

        </div>

    </div>

    {{-- Documents --}}
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <h2 class="text-lg font-semibold text-slate-900">
            Candidate Documents
        </h2>

        @if ($nomination->candidate?->documents?->isNotEmpty())

            <div class="mt-4 divide-y divide-slate-200">

                @foreach ($nomination->candidate->documents as $document)

                    <div class="flex items-center justify-between gap-4 py-4">

                        <div>
                            <p class="font-medium text-slate-900">
                                {{ $document->document_type ?? $document->name ?? 'Document' }}
                            </p>
                        </div>

                        @if (Route::has('staff.ict.candidate-documents.view'))

                            <a
                                href="{{ route('staff.ict.candidate-documents.view', $document) }}"
                                target="_blank"
                                class="text-sm font-medium text-blue-600 hover:underline"
                            >
                                View
                            </a>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <p class="mt-4 text-sm text-slate-500">
                No candidate documents were found.
            </p>

        @endif

    </div>

    {{-- Workflow history --}}
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <h2 class="text-lg font-semibold text-slate-900">
            Workflow History
        </h2>

        <div class="mt-5 space-y-4">

            @forelse ($nomination->workflowHistories->sortBy('id') as $history)

                <div class="border-l-2 border-slate-200 pl-4">

                    <div class="flex flex-wrap items-center gap-2">

                        <span class="font-medium text-slate-900">
                            {{ ucfirst($history->action) }}
                        </span>

                        @if ($history->from_department)
                            <span class="text-sm text-slate-500">
                                {{ ucfirst($history->from_department) }}
                            </span>
                        @endif

                        @if ($history->to_department)
                            <span class="text-sm text-slate-500">
                                →
                                {{ ucfirst($history->to_department) }}
                            </span>
                        @endif

                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $history->created_at?->format('d M Y, H:i') }}
                    </p>

                    @if ($history->comment)
                        <p class="mt-2 text-sm text-slate-600">
                            {{ $history->comment }}
                        </p>
                    @endif

                </div>

            @empty

                <p class="text-sm text-slate-500">
                    No workflow history available.
                </p>

            @endforelse

        </div>

    </div>

    {{-- Forward --}}
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <h2 class="text-lg font-semibold text-slate-900">
            Legal Review Decision
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            After completing the legal review, forward the nomination
            to the Commissioner.
        </p>

        <form
            method="POST"
            action="{{ route('staff.legal.nominations.forward', $nomination) }}"
            class="mt-5"
        >

            @csrf

            <div>
                <label
                    for="comment"
                    class="block text-sm font-medium text-slate-700"
                >
                    Legal Review Comment
                </label>

                <textarea
                    id="comment"
                    name="comment"
                    rows="5"
                    maxlength="2000"
                    class="mt-2 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    placeholder="Enter any legal review observations or recommendations..."
                >{{ old('comment') }}</textarea>

                @error('comment')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mt-5 flex justify-end">

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-700"
                >
                    Forward to Commissioner
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
