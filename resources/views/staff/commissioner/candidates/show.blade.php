@extends('layouts.staff')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Candidate Record
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Read-only candidate information and supporting records.
            </p>
        </div>

        <a
            href="{{ url()->previous() }}"
            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
        >
            Back
        </a>

    </div>


    {{-- Candidate Identity --}}
    <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-300 bg-slate-50/50 px-6 py-5">
            <h2 class="font-semibold text-slate-900">
                Candidate Identity
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Identity information verified through the candidate's NIN.
            </p>
        </div>

        <div class="p-6">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $candidate->full_name }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Candidate ID #{{ $candidate->id }}
                    </p>
                </div>

                @if($candidate->nin_verified_at)

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        NIN Verified
                    </span>

                @else

                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        Verification Pending
                    </span>

                @endif

            </div>


            <dl class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        First Name
                    </dt>

                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $candidate->first_name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Middle Name
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $candidate->middle_name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Last Name
                    </dt>

                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $candidate->last_name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Gender
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $candidate->gender ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Date of Birth
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $candidate->date_of_birth?->format('d M Y') ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        NIN Verification Date
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $candidate->nin_verified_at?->format('d M Y, H:i') ?? '—' }}
                    </dd>
                </div>

            </dl>

        </div>

    </section>


    {{-- Qualification --}}
    <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-300 bg-slate-50/50 px-6 py-5">
            <h2 class="font-semibold text-slate-900">
                Qualification
            </h2>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Qualification
                </dt>

                <dd class="mt-1 text-sm text-slate-900">
                    {{ $candidate->qualification ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Qualification Details
                </dt>

                <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">
                    {{ $candidate->qualification_details ?? '—' }}
                </dd>
            </div>

        </div>

    </section>


    {{-- Disability / PWD --}}
    <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-300 bg-slate-50/50 px-6 py-5">
            <h2 class="font-semibold text-slate-900">
                Disability / PWD Information
            </h2>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Has Disability
                </dt>

                <dd class="mt-1 text-sm font-medium text-slate-900">
                    {{ $candidate->has_disability ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Description
                </dt>

                <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">
                    {{ $candidate->disability_description ?? '—' }}
                </dd>
            </div>

        </div>

    </section>


    {{-- Nomination --}}
    @if($nomination)

        <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

            <div class="border-b border-slate-300 bg-slate-50/50 px-6 py-5">
                <h2 class="font-semibold text-slate-900">
                    Nomination
                </h2>
            </div>

            <div class="grid gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Political Party
                    </dt>

                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $nomination->politicalParty?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Election
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $nomination->election_type ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Position
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $nomination->position?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Electoral Area
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $nomination->electoral_area }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Nomination Status
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ ucfirst(str_replace('_', ' ', $nomination->status)) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Workflow Status
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ ucfirst(str_replace('_', ' ', $nomination->workflow_status)) }}
                    </dd>
                </div>

            </div>

        </section>

    @endif


    {{-- Candidate Documents --}}
    <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-300 bg-slate-50/50 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Candidate Documents
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Documents available for Commissioner review.
            </p>

        </div>


        @if($candidate->documents->isNotEmpty())

            <div class="divide-y divide-slate-200">

                @foreach($candidate->documents as $document)

                    <div class="flex items-center justify-between gap-4 px-6 py-4">

    <div class="min-w-0">

        <p class="text-sm font-medium text-slate-900">
            {{ $document->documentType?->name ?? 'Document' }}
        </p>

        <p class="mt-1 text-xs text-slate-500">
            {{ $document->original_name }}
        </p>

        @if($document->remarks)
            <p class="mt-1 text-xs text-slate-500">
                {{ $document->remarks }}
            </p>
        @endif

    </div>

    <a
        href="{{ route(
            'staff.commissioner.candidates.documents.view',
            [
                'candidate' => $candidate,
                'document' => $document,
            ]
        ) }}"
        target="_blank"
        rel="noopener"
        class="inline-flex shrink-0 items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
    >
        View Document
    </a>

</div>

                @endforeach

            </div>

        @else

            <div class="p-6 text-sm text-slate-500">
                No candidate documents available.
            </div>

        @endif

    </section>

</div>

@endsection
