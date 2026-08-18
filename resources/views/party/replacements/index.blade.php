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
            Replace candidates whose withdrawals have been approved by OGSIEC.
            Replacement candidates inherit the payment already made for the
            withdrawn nomination.
        </p>

    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Candidates Eligible for Replacement
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Only approved withdrawals without an existing replacement are shown.
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
                                {{ $candidate?->full_name
                                    ?? trim(implode(' ', array_filter([
                                        $candidate?->first_name,
                                        $candidate?->middle_name,
                                        $candidate?->last_name,
                                    ])))
                                    ?: 'Unknown candidate' }}
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

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-8a4 4 0 100-8 4 4 0 000 8zm7-4v6m3-3h-6"
                        />
                    </svg>

                </div>

                <h3 class="mt-5 text-lg font-semibold text-slate-950">
                    No candidates require replacement
                </h3>

                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                    Approved candidate withdrawals that are eligible for replacement
                    will appear here.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
