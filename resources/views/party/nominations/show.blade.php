@extends('layouts.party')

@section('title', 'Nomination Details | SEMP')

@section('content')

<div class="mb-8">

    <a
        href="{{ route('party.nominations.index') }}"
        class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Candidate Nominations
    </a>

    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-sm font-medium text-emerald-700">
                Candidate Nomination
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
    {{ $nomination->candidate_name }}
</h1>

            <p class="mt-2 text-sm text-slate-500">
                Nomination #{{ $nomination->id }}
                · {{ $nomination->position->name }}
            </p>
        </div>


        <div>
            @if ($nomination->status === 'draft')

                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-sm font-semibold text-amber-800">
                    Draft
                </span>

            @elseif ($nomination->status === 'ready')

    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1.5 text-sm font-semibold text-blue-800">
        Ready
    </span>

            @elseif ($nomination->status === 'approved')

                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                    Approved
                </span>

            @elseif ($nomination->status === 'rejected')

                <span class="inline-flex rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-800">
                    Rejected
                </span>

            @else

                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                    {{ ucfirst($nomination->status) }}
                </span>

            @endif
        </div>

    </div>

</div>


@if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif

{{-- Summary --}}
<div class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Summary
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            A quick overview of this nomination.
        </p>

    </div>

    <div class="grid gap-x-8 gap-y-6 px-6 py-6 sm:grid-cols-2 lg:grid-cols-4">

        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Election
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
               {{ $nomination->election->name }}
            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Election Type
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->election->electionType->name }}
            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Position
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->position->name }}
            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Status
            </p>

            <p class="mt-2">
                @if ($nomination->status === 'draft')

                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                        Draft
                    </span>

                @elseif ($nomination->status === 'ready')

    @if ($nomination->hasPendingWithdrawal())

        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1.5 text-sm font-semibold text-yellow-800">
            Withdrawal Pending
        </span>

    @else

        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1.5 text-sm font-semibold text-blue-800">
            Ready
        </span>

    @endif

                @elseif ($nomination->status === 'approved')

                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                        Approved
                    </span>

                @elseif ($nomination->status === 'rejected')

                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800">
                        Rejected
                    </span>

                @endif

            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Electoral Area
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
{{ $nomination->electoral_area }}

            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Nomination Fee
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">

                ₦{{ number_format($nomination->nomination_fee, 2) }}

            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Created
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->created_at->format('d M Y, H:i') }}
            </p>

        </div>


        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Last Updated
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->updated_at->format('d M Y, H:i') }}
            </p>

        </div>

    </div>

</div>


{{-- Candidate Information --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Candidate Information
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Identity information associated with this nomination.
        </p>

    </div>


    <div class="grid gap-x-8 gap-y-6 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Full Name
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
    {{ $nomination->candidate_name }}
</p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Candidate ID
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->candidate->id }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                NIN Verification
            </p>

            @if ($nomination->candidate->nin_verified_at)

                <div class="mt-2">
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                        Verified
                    </span>
                </div>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $nomination->candidate->nin_verified_at->format('d M Y, H:i') }}
                </p>

            @else

                <div class="mt-2">
                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                        Not Verified
                    </span>
                </div>

            @endif
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Gender
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->candidate->gender }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Date of Birth
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->candidate->date_of_birth->format('d M Y') }}
            </p>
        </div>

    </div>

</div>


{{-- Nomination Information --}}
<div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Nomination Information
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Election, office and electoral area for this nomination.
        </p>

    </div>


    <div class="grid gap-x-8 gap-y-6 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Election
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->election->name }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Position
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->position->name }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Status
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ ucfirst($nomination->status) }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Local Government Area
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->lga?->name ?? 'Not applicable' }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Ward
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->ward?->name ?? 'Not applicable' }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Election Date
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->election->election_date->format('d M Y') }}
            </p>
        </div>

    </div>

</div>


{{-- Political Party --}}
<div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
@if ($nomination->withdrawal)

<div class="rounded-xl border border-slate-200 bg-white mt-6">

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="text-lg font-semibold text-slate-900">
            Candidate Change
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Current candidate change request associated with this nomination.
        </p>

    </div>

    <div class="grid gap-6 px-6 py-5 md:grid-cols-2">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Type
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                Candidate Withdrawal
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Status
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ ucfirst(str_replace('_', ' ', $nomination->withdrawal->status)) }}
            </p>
        </div>

        <div class="md:col-span-2">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Reason
            </p>

            <p class="mt-2 text-sm text-slate-900">
                {{ $nomination->withdrawal->reason }}
            </p>
        </div>

        <div>
            <a
                href="{{ route('party.withdrawals.show', $nomination->withdrawal) }}"
                class="inline-flex rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                View Withdrawal Request
            </a>
        </div>

    </div>

</div>

@endif
        <h2 class="text-lg font-semibold text-slate-950">
            Political Party
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Political party responsible for this nomination.
        </p>

    </div>


    <div class="grid gap-x-8 gap-y-6 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Party
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $party->name }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Acronym
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $party->acronym }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Nomination Created
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->created_at->format('d M Y, H:i') }}
            </p>
        </div>

    </div>

</div>


{{-- Nomination Workflow --}}
<div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Nomination Requirements
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Complete the nomination requirements before submission to OGSIEC.
        </p>

    </div>


    <div class="divide-y divide-slate-100">

        <div class="flex items-center justify-between gap-4 px-6 py-5">

            <div>
                <p class="text-sm font-semibold text-slate-900">
                    Candidate Details
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Candidate identity has been recorded and verified.
                </p>
            </div>

            @if ($nomination->candidate->nin_verified_at)

                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                    Complete
                </span>

            @else

                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                    Required
                </span>

            @endif

        </div>


       <div class="flex items-center justify-between gap-4 px-6 py-5">

    <div>

        <p class="text-sm font-semibold text-slate-900">
            Supporting Documents
        </p>

        <p class="mt-1 text-xs text-slate-500">
            Uploaded {{ $uploadedDocuments }} of {{ $requiredDocuments }} required documents.
        </p>

    </div>

    @if($documentsComplete)

        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
            Complete
        </span>

    @else

        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
            Incomplete
        </span>

    @endif

</div>

        <div class="flex items-center justify-between gap-4 px-6 py-5">

            <div>
                <p class="text-sm font-semibold text-slate-900">
                    Nomination Payment
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Nomination fee payment will be associated with this nomination.
                </p>
            </div>

            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                Not Yet Configured
            </span>

        </div>

    </div>

</div>

{{-- Actions --}}
<div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-between">

    <a
        href="{{ route('party.nominations.index') }}"
        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
    >
        Back to Nominations
    </a>

    <div class="flex flex-col gap-3 sm:flex-row">

        @if ($nomination->canBeEdited())

            <a
                href="{{ route('party.nominations.edit', $nomination) }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Edit Nomination
            </a>

        @endif

        @if ($nomination->canBeMarkedReady())

            <form
                method="POST"
                action="{{ route('party.nominations.submit', $nomination->id) }}"
                onsubmit="return confirm('Mark this nomination as ready for batching? Once marked, it can no longer be edited.');"`
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                >
                    Mark as Ready
                </button>

            </form>

        @endif

      @if ($nomination->isReady())

            <a
                href="{{ route('party.withdrawals.create', $nomination) }}"
                class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
            >
                Withdraw Candidate
            </a>

        @endif

    </div>

</div>
@endsection
