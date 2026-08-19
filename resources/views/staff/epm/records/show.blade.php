@extends('layouts.staff')

@section('title', 'EPM Record')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>
            <p class="text-sm font-medium text-indigo-600">
                EPM Records
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-900">
                {{ $nomination->candidate?->full_name ?? 'Unknown Candidate' }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Historical nomination record #{{ $nomination->id }}
            </p>
        </div>

        <a
            href="{{ route('staff.epm.records.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            Back to EPM Records
        </a>

    </div>


    {{-- Historical notice --}}
    <div class="rounded-xl border border-indigo-200 bg-indigo-50 px-6 py-5">

        <div class="flex gap-3">

            <div class="shrink-0 text-indigo-600">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.2 12.5A1.75 1.75 0 004.6 19h14.8a1.75 1.75 0 001.51-2.64l-7.2-12.5a1.75 1.75 0 00-3.02 0z"
                    />
                </svg>
            </div>

            <div>
                <h2 class="font-semibold text-indigo-900">
                    Historical EPM Record
                </h2>

                <p class="mt-1 text-sm leading-6 text-indigo-800">
                    This nomination has previously been processed by EPM.
                    This page is read-only and is retained for audit and
                    historical reference.
                </p>
            </div>

        </div>

    </div>


    {{-- Current status --}}
    <div class="grid gap-4 md:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-sm text-slate-500">
                Current Department
            </p>

            <p class="mt-2 text-lg font-semibold text-slate-900">
                {{ $nomination->current_department
                    ? ucfirst($nomination->current_department)
                    : 'Completed' }}
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-sm text-slate-500">
                Workflow Status
            </p>

            <p class="mt-2 text-lg font-semibold text-slate-900">
                {{ str_replace(
                    '_',
                    ' ',
                    ucfirst($nomination->workflow_status ?? 'Unknown')
                ) }}
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-sm text-slate-500">
                Nomination Status
            </p>

            <p class="mt-2 text-lg font-semibold text-slate-900">
                {{ str_replace(
                    '_',
                    ' ',
                    ucfirst($nomination->status ?? 'Unknown')
                ) }}
            </p>

        </div>

    </div>


    {{-- Nomination information --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Nomination Information
            </h2>

        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Candidate
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->candidate?->full_name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Political Party
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->politicalParty?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Election
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->election?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Position
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->position?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    LGA
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->lga?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    LCDA
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->lcda?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Ward
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->ward?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Nomination Batch
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->batch?->batch_number ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Received
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->received_at?->format('d M Y H:i') ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Candidate information --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Candidate Information
            </h2>

        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Full Name
                </p>

                <p class="mt-2 font-medium text-slate-900">
                    {{ $nomination->candidate?->full_name ?? '—' }}
                </p>
            </div>


            @if($nomination->candidate)

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Candidate ID
                    </p>

                    <p class="mt-2 font-medium text-slate-900">
                        #{{ $nomination->candidate->id }}
                    </p>
                </div>

                @if($nomination->candidate->qualification)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Qualification
                        </p>

                        <p class="mt-2 font-medium text-slate-900">
                            {{ $nomination->candidate->qualification }}
                        </p>
                    </div>
                @endif

            @endif

        </div>

    </div>


    {{-- Supporting documents --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Supporting Documents
            </h2>

        </div>

        <div class="p-6">

            @forelse($nomination->candidate?->documents ?? [] as $document)

                <div class="flex flex-col gap-3 border-b border-slate-100 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="font-medium text-slate-900">
                            {{ $document->document_type?->name
                                ?? $document->documentType?->name
                                ?? 'Candidate Document' }}
                        </p>

                        @if($document->status)
                            <p class="mt-1 text-xs text-slate-500">
                                Status:
                                {{ str_replace('_', ' ', ucfirst($document->status)) }}
                            </p>
                        @endif

                    </div>

                </div>

            @empty

                <p class="text-sm text-slate-500">
                    No candidate documents recorded.
                </p>

            @endforelse

        </div>

    </div>


    {{-- Workflow history --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Workflow History
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Complete movement history for this nomination.
            </p>

        </div>

        <div class="divide-y divide-slate-100">

            @forelse($nomination->workflowHistories->sortBy('created_at') as $history)

                <div class="px-6 py-5">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {{ str_replace(
                                        '_',
                                        ' ',
                                        ucfirst($history->action ?? 'Action')
                                    ) }}
                                </span>

                                <span class="text-sm font-medium text-slate-900">
                                    {{ $history->from_department
                                        ? ucfirst($history->from_department)
                                        : 'System' }}
                                    →
                                    {{ $history->to_department
                                        ? ucfirst($history->to_department)
                                        : '—' }}
                                </span>

                            </div>

                            @if($history->user)
    <p class="mt-3 text-xs text-slate-500">
        Processed by:
        <span class="font-medium text-slate-700">
            {{ $history->user->name }}
        </span>
    </p>
@endif

                        </div>


                        <div class="shrink-0 text-sm text-slate-500">

                            {{ $history->created_at?->format('d M Y H:i') ?? '—' }}

                        </div>

                    </div>

                </div>

            @empty

                <div class="px-6 py-10 text-center">

                    <p class="text-sm text-slate-500">
                        No workflow history recorded.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- Read-only notice --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50 px-6 py-5">

        <p class="text-sm font-medium text-slate-700">
            Read-only historical record
        </p>

        <p class="mt-1 text-sm leading-6 text-slate-500">
            This record is retained for EPM reference and audit purposes.
            Workflow actions cannot be performed from this page.
        </p>

    </div>

</div>

@endsection
