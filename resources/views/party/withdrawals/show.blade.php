@extends('layouts.party')

@section('title', 'Candidate Withdrawal | SEMP')

@section('content')

<div class="mb-8">

    <a
        href="{{ route('party.nominations.show', $withdrawal->nomination) }}"
        class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Nomination
    </a>

    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>

            <p class="text-sm font-medium text-emerald-700">
                Candidate Withdrawal
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                {{ $withdrawal->nomination->candidate_name }}
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Withdrawal Request #{{ $withdrawal->id }}
            </p>

        </div>

        <div>

            @if ($withdrawal->isDraft())

                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-sm font-semibold text-amber-800">
                    Draft
                </span>

            @elseif ($withdrawal->isSubmitted())

                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1.5 text-sm font-semibold text-blue-800">
                    Submitted
                </span>

            @elseif ($withdrawal->isApproved())

                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                    Approved
                </span>

            @elseif ($withdrawal->isRejected())

                <span class="inline-flex rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-800">
                    Rejected
                </span>

            @endif

        </div>

    </div>

</div>


@if(session('success'))

    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>

@endif


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Withdrawal Details
        </h2>

    </div>

    <div class="px-6 py-6">

        <dl class="space-y-6">

            <div>

                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Candidate
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->candidate_name }}
                </dd>

            </div>

            <div>

                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Election
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->election->name }}
                </dd>

            </div>

            <div>

                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Position
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->position->name }}
                </dd>

            </div>

            <div>

    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
        Withdrawal Reason
    </dt>

    <dd class="mt-2 text-sm font-medium text-slate-900">
        {{ $withdrawal->candidateChangeReason?->name ?? 'N/A' }}
    </dd>

</div>

<div>

    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
        Additional Remarks
    </dt>

    <dd class="mt-2 whitespace-pre-line text-sm text-slate-700">
        {{ $withdrawal->remarks }}
    </dd>

</div>

@if ($withdrawal->supporting_evidence_path)

<div>

    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
        Supporting Evidence
    </dt>

    <dd class="mt-2">

        <a
            href="{{ asset('storage/' . $withdrawal->supporting_evidence_path) }}"
            target="_blank"
            class="inline-flex rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-emerald-700 hover:bg-slate-50"
        >
            View Supporting Evidence
        </a>

    </dd>

</div>

@endif

        </dl>

    </div>

</div>

@endsection
