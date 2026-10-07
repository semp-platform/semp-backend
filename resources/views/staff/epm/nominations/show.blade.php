@extends('layouts.staff')

@section('title', 'EPM Nomination Review')

@section('content')

<div class="max-w-6xl mx-auto py-8 px-6">

    {{-- Header --}}

    <div class="mb-8 flex items-start justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                EPM Nomination Review
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Review the candidate, nomination details and supporting documents before forwarding to Legal.
            </p>
        </div>

        <a
            href="{{ route('staff.epm.nominations.index') }}"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
        >
            ← Back
        </a>

    </div>


    {{-- Nomination & Candidate --}}

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Nomination details --}}

        <div class="lg:col-span-2 rounded-xl bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="font-semibold text-slate-900">
                    Nomination & Candidate Information
                </h2>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Candidate
                    </div>

                    <div class="mt-1 font-semibold text-slate-900">
                        {{ $nomination->candidate?->full_name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Political Party
                    </div>

                    <div class="mt-1 font-semibold text-slate-900">
                        {{ $nomination->politicalParty?->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Election
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->election?->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Position
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->position?->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Electoral Area
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->electoral_area }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Batch
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->batch?->batch_number ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Gender
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->candidate?->gender ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Date of Birth
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ optional($nomination->candidate?->date_of_birth)->format('d M Y') ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Qualification
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->candidate?->qualification ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium uppercase text-slate-500">
                        Qualification Details
                    </div>

                    <div class="mt-1 text-slate-900">
                        {{ $nomination->candidate?->qualification_details ?? '—' }}
                    </div>
                </div>

                @if($nomination->candidate?->has_disability)

                    <div class="md:col-span-2">
                        <div class="text-xs font-medium uppercase text-slate-500">
                            Disability
                        </div>

                        <div class="mt-1 text-slate-900">
                            Yes
                            @if($nomination->candidate?->disability_description)
                                — {{ $nomination->candidate->disability_description }}
                            @endif
                        </div>
                    </div>

                @endif

            </div>

        </div>


        {{-- Workflow status --}}

        <div class="rounded-xl bg-white p-6 shadow-sm">

            <div class="text-xs font-medium uppercase text-slate-500">
                Nomination ID
            </div>

            <div class="mt-2 text-lg font-semibold text-slate-900">
                #{{ $nomination->id }}
            </div>

            <div class="mt-6 text-xs font-medium uppercase text-slate-500">
                Current Department
            </div>

            <div class="mt-2 text-lg font-semibold text-indigo-700">
                {{ strtoupper($nomination->current_department ?? '—') }}
            </div>

            <div class="mt-6 text-xs font-medium uppercase text-slate-500">
                Workflow Status
            </div>

            <div class="mt-2">
                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                    {{ str_replace('_', ' ', ucfirst($nomination->workflow_status ?? '—')) }}
                </span>
            </div>

            <div class="mt-6 text-xs font-medium uppercase text-slate-500">
                Nomination Status
            </div>

            <div class="mt-2 text-sm text-slate-700">
                {{ str_replace('_', ' ', ucfirst($nomination->status ?? '—')) }}
            </div>

            @if($nomination->received_at)

                <div class="mt-6 text-xs font-medium uppercase text-slate-500">
                    Received
                </div>

                <div class="mt-2 text-sm text-slate-700">
                    {{ $nomination->received_at->format('d M Y H:i') }}
                </div>

            @endif

        </div>

    </div>


    {{-- Primary Monitoring --}}

    <div class="mt-6 rounded-xl bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Party Primary Monitoring
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                EPM primary monitoring records can be reviewed separately for the party and position.
            </p>

        </div>

        <div class="p-6">

            <a
                href="{{ route('staff.epm.primary-monitoring.index') }}"
                class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                View Primary Monitoring Records
            </a>

            <p class="mt-3 text-xs text-slate-500">
                The current nomination data does not contain a direct link to a monitored primary winner,
                so no automatic winner-to-nominee comparison is shown here.
            </p>

        </div>

    </div>


    {{-- Submitted Documents --}}

    <div class="mt-6 rounded-xl bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Submitted Candidate Documents
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Review the supporting documents submitted for this candidate.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">

            @forelse($nomination->candidate?->documents ?? [] as $document)

                <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 p-4">

                    <div>

                        <div class="font-medium text-slate-900">
                            {{ $document->documentType?->name ?? 'Document' }}
                        </div>

                        <div class="mt-1 text-sm text-slate-500">
                            {{ $document->original_name ?? 'Uploaded document' }}
                        </div>

                        @if($document->uploaded_at)

                            <div class="mt-1 text-xs text-slate-400">
                                Uploaded:
                                {{ $document->uploaded_at->format('d M Y H:i') }}
                            </div>

                        @endif



                    </div>

                    <a
                        href="{{ route('staff.ict.candidate-documents.view', $document) }}"
                        target="_blank"
                        class="shrink-0 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        View
                    </a>

                </div>

            @empty

                <div class="col-span-full py-4 text-sm text-slate-500">
                    No candidate documents have been uploaded.
                </div>

            @endforelse

        </div>

    </div>


    {{-- EPM Review --}}

    @if ($nomination->current_department === \App\Services\Workflow\NominationWorkflowService::DEPARTMENT_EPM)

        <div class="mt-6 rounded-xl bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                EPM Review
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Add any relevant EPM observations before forwarding the nomination to Legal.
            </p>

            <form
                method="POST"
                action="{{ route('staff.epm.nominations.forward', $nomination) }}"
                class="mt-6"
            >

                @csrf

                <label
                    for="comment"
                    class="block text-sm font-medium text-slate-700"
                >
                    EPM Review Comment
                </label>

                <textarea
                    id="comment"
                    name="comment"
                    rows="4"
                    class="mt-2 w-full rounded-lg border-slate-300"
                    placeholder="Enter EPM review comments..."
                ></textarea>

                <button
                    type="submit"
                    class="mt-4 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Forward to Legal
                </button>

            </form>

        </div>

    @else

        <div class="mt-6 rounded-xl bg-white p-6 shadow-sm">

            <p class="text-sm text-slate-600">
                This nomination is currently assigned to
                <strong>
                    {{ strtoupper($nomination->current_department ?? 'NO DEPARTMENT') }}
                </strong>.
                It cannot be forwarded by EPM at this stage.
            </p>

        </div>

    @endif


    {{-- Workflow History --}}

    <div class="mt-6 rounded-xl bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Workflow History
            </h2>

        </div>

        <div class="divide-y divide-slate-200">

            @forelse($nomination->workflowHistories as $history)

                <div class="px-6 py-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <div class="font-medium text-slate-900">
                                {{ ucwords(str_replace('_', ' ', $history->action)) }}
                            </div>

                            <div class="mt-1 text-sm text-slate-500">

                                {{ $history->from_department
                                    ? strtoupper($history->from_department)
                                    : 'Party' }}

                                →

                                {{ $history->to_department
                                    ? strtoupper($history->to_department)
                                    : 'Completed' }}

                            </div>

                        </div>

                        <div class="text-right text-xs text-slate-500">
                            {{ optional($history->created_at)->format('d M Y H:i') }}
                        </div>

                    </div>

                    @if($history->reason)

                        <div class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-800">

                            <span class="font-semibold">
                                Reason:
                            </span>

                            {{ $history->reason }}

                        </div>

                    @endif

                    @if($history->comment)

                        <p class="mt-3 text-sm text-slate-700">
                            {{ $history->comment }}
                        </p>

                    @endif

                </div>

            @empty

                <div class="px-6 py-8 text-sm text-slate-500">
                    No workflow history available.
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
