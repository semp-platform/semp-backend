@extends('layouts.party')

@section('title', 'Candidate Replacements | SEMP')

@section('content')

<div class="mx-auto max-w-7xl">

    <div class="mb-8">

        <p class="text-sm font-medium text-emerald-700">
            Political Party Portal
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Candidate Replacements
        </h1>

        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
            Manage approved candidate replacements and retain a record of
            replacement candidates already registered by the party.
        </p>

    </div>


    {{-- ============================================================= --}}
    {{-- CANDIDATES AWAITING REPLACEMENT --}}
    {{-- ============================================================= --}}

    <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Candidates Eligible for Replacement
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Approved withdrawals that have not yet received a replacement candidate.
            </p>

        </div>

        @forelse($withdrawals as $withdrawal)

            @php
                $nomination = $withdrawal->nomination;
                $candidate = $nomination?->candidate;
            @endphp

            <div class="border-b border-slate-100 px-6 py-5 last:border-b-0">

                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">

                            <h3 class="text-base font-semibold text-slate-950">
                                {{ $candidate?->full_name ?? 'Unknown candidate' }}
                            </h3>

                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                Withdrawn
                            </span>

                        </div>

                        <div class="mt-2 grid gap-1 text-sm text-slate-500 sm:grid-cols-2">

                            <p>
                                <span class="font-medium text-slate-700">
                                    Election:
                                </span>
                                {{ $nomination?->election?->name ?? '—' }}
                            </p>

                            <p>
                                <span class="font-medium text-slate-700">
                                    Position:
                                </span>
                                {{ $nomination?->position?->name ?? '—' }}
                            </p>

                            <p>
                                <span class="font-medium text-slate-700">
                                    Electoral Area:
                                </span>
                                {{ $nomination?->electoral_area ?? '—' }}
                            </p>

                            <p>
                                <span class="font-medium text-slate-700">
                                    Withdrawal approved:
                                </span>
                                {{ optional($withdrawal->approved_at)->format('d M Y') ?? '—' }}
                            </p>

                        </div>

                    </div>

                    <div class="shrink-0">

                        <a
                            href="{{ route('party.replacements.create', $withdrawal) }}"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
                        >
                            Replace Candidate
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="px-6 py-10 text-center">

                <h3 class="text-base font-semibold text-slate-950">
                    No candidates currently require replacement
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Approved withdrawals awaiting replacement will appear here.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ============================================================= --}}
    {{-- COMPLETED REPLACEMENTS --}}
    {{-- ============================================================= --}}

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Replacement Records
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Candidates already registered as replacements for withdrawn nominations.
            </p>

        </div>

        @forelse($completedReplacements as $withdrawal)

            @php
                $original = $withdrawal->nomination;
                $replacement = $withdrawal->replacementNomination;

                $originalCandidate = $original?->candidate;
                $replacementCandidate = $replacement?->candidate;
            @endphp

            <div class="border-b border-slate-100 px-6 py-6 last:border-b-0">

                <div class="grid gap-6 lg:grid-cols-3">

                    {{-- Original candidate --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Withdrawn Candidate
                        </p>

                        <h3 class="mt-2 text-base font-semibold text-slate-950">
                            {{ $originalCandidate?->full_name ?? 'Unknown candidate' }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Withdrawn
                        </p>

                    </div>


                    {{-- Replacement candidate --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Replacement Candidate
                        </p>

                        <h3 class="mt-2 text-base font-semibold text-slate-950">
                            {{ $replacementCandidate?->full_name ?? 'Unknown candidate' }}
                        </h3>

                        <span class="mt-2 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            Registered
                        </span>

                    </div>


                    {{-- Nomination information --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Nomination
                        </p>

                        <p class="mt-2 text-sm text-slate-700">
                            <span class="font-medium">
                                Election:
                            </span>
                            {{ $replacement?->election?->name ?? $original?->election?->name ?? '—' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-700">
                            <span class="font-medium">
                                Position:
                            </span>
                            {{ $replacement?->position?->name ?? $original?->position?->name ?? '—' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-700">
                            <span class="font-medium">
                                Electoral Area:
                            </span>
                            {{ $replacement?->electoral_area ?? $original?->electoral_area ?? '—' }}
                        </p>

                    </div>

                </div>


                {{-- Replacement metadata --}}
                <div class="mt-5 flex flex-wrap gap-x-8 gap-y-2 border-t border-slate-100 pt-4 text-sm text-slate-500">

                    <p>
                        <span class="font-medium text-slate-700">
                            Withdrawal approved:
                        </span>
                        {{ optional($withdrawal->approved_at)->format('d M Y') ?? '—' }}
                    </p>

                    <p>
                        <span class="font-medium text-slate-700">
                            Replacement nomination:
                        </span>
                        #{{ $replacement?->id ?? '—' }}
                    </p>

                    <p>
                        <span class="font-medium text-slate-700">
                            Payment:
                        </span>
                        Inherited from original nomination
                    </p>

                </div>

                @if($replacement)

                    <div class="mt-5">

                        <a
                            href="{{ route('party.nominations.show', $replacement) }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            View Replacement Nomination
                        </a>

                    </div>

                @endif

            </div>

        @empty

            <div class="px-6 py-12 text-center">

                <h3 class="text-base font-semibold text-slate-950">
                    No replacement records yet
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Completed candidate replacements will be retained here for reference.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
