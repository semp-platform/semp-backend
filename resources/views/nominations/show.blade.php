@extends('layouts.app')

@section('title', 'Nomination Details | SEMP')

@section('content')

<div class="mb-8">
    <a
        href="{{ route('nominations.index') }}"
        class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Nominations
    </a>

    <div class="mt-5">
        <p class="text-sm font-medium text-emerald-700">
            Nomination Details
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            {{ $nomination->candidate->first_name }}
            {{ $nomination->candidate->middle_name }}
            {{ $nomination->candidate->last_name }}
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Candidate nomination record and election information.
        </p>
    </div>
</div>


{{-- Status --}}
<div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Nomination Status
            </p>

            <p class="mt-2 text-sm text-slate-500">
                Current processing status for this nomination.
            </p>
        </div>

        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1.5 text-sm font-semibold capitalize text-amber-700">
            {{ $nomination->status }}
        </span>
    </div>

</div>


<div class="grid gap-8 xl:grid-cols-2">

    {{-- Candidate --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-950">
                Candidate Information
            </h2>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    First Name
                </p>

                <p class="mt-2 text-sm font-medium text-slate-900">
                    {{ $nomination->candidate->first_name }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Middle Name
                </p>

                <p class="mt-2 text-sm font-medium text-slate-900">
                    {{ $nomination->candidate->middle_name ?: '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Last Name
                </p>

                <p class="mt-2 text-sm font-medium text-slate-900">
                    {{ $nomination->candidate->last_name }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Gender
                </p>

                <p class="mt-2 text-sm font-medium text-slate-900">
                    {{ $nomination->candidate->gender }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Date of Birth
                </p>

                <p class="mt-2 text-sm font-medium text-slate-900">
                    {{ $nomination->candidate->date_of_birth->format('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    NIN Verification
                </p>

                <p class="mt-2 text-sm font-medium text-emerald-700">
                    {{ $nomination->candidate->nin_verified_at ? 'Verified' : 'Not verified' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Political party --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-950">
                Political Party
            </h2>
        </div>

        <div class="p-6">

            <p class="text-2xl font-bold text-slate-950">
                {{ $nomination->politicalParty->acronym }}
            </p>

            <p class="mt-2 text-sm text-slate-600">
                {{ $nomination->politicalParty->name }}
            </p>

        </div>

    </div>

</div>


{{-- Election information --}}
<div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Election & Position
        </h2>
    </div>

    <div class="grid gap-6 p-6 sm:grid-cols-2 xl:grid-cols-4">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Election
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->election->name }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Position
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->position->name }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                {{ $nomination->position->code }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                LGA
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->lga?->name ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Ward
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $nomination->ward?->name ?? 'Not applicable' }}
            </p>
        </div>

    </div>

</div>

@endsection
