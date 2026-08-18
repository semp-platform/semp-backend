@extends('layouts.public')

@section('title', $nomination->candidate?->full_name ?? 'Candidate')

@section('meta_description')
    Public candidate information published by the Ogun State Independent Electoral Commission.
@endsection

@section('content')

<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Back --}}
    <div class="mb-6">

        <a
            href="{{ route('public.candidates.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800 hover:text-emerald-950"
        >
            <span aria-hidden="true">
                ←
            </span>

            Back to Candidates
        </a>

    </div>


    {{-- Candidate header --}}
    <article class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="h-1 bg-emerald-700"></div>

        <div class="px-6 py-7 sm:px-8 sm:py-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-800">
                        Approved Candidate
                    </p>

                    <h1 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-slate-950 sm:text-4xl">
                        {{ $nomination->candidate?->full_name ?? $nomination->candidate_name }}
                    </h1>

                    <p class="mt-3 text-sm text-slate-600">
                        Ogun State Independent Electoral Commission
                    </p>

                </div>


                <span class="inline-flex w-fit rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800">
                    Approved
                </span>

            </div>

        </div>


        {{-- Candidate information --}}
        <div class="border-t border-slate-200">

            <div class="grid divide-y divide-slate-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0">

                <div class="px-6 py-5 sm:px-8">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Political Party
                    </p>

                    <p class="mt-2 text-base font-semibold text-slate-950">
                        {{ $nomination->politicalParty?->name ?? 'Not specified' }}

                        @if($nomination->politicalParty?->acronym)

                            <span class="font-normal text-slate-600">
                                ({{ $nomination->politicalParty->acronym }})
                            </span>

                        @endif

                    </p>

                </div>


                <div class="px-6 py-5 sm:px-8">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Position
                    </p>

                    <p class="mt-2 text-base font-semibold text-slate-950">
                        {{ $nomination->position?->name ?? 'Not specified' }}
                    </p>

                </div>

            </div>

        </div>

    </article>


    {{-- Election information --}}
    <section class="mt-8 overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

            <h2 class="text-xl font-semibold text-slate-950">
                Election Information
            </h2>

        </div>


        <div class="grid gap-4 p-6 sm:grid-cols-2 sm:p-8">

            <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Election
                </p>

                <p class="mt-2 text-sm font-semibold text-slate-950">
                    {{ $nomination->election?->name ?? 'Not specified' }}
                </p>

            </div>


            <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Election Type
                </p>

                <p class="mt-2 text-sm font-semibold text-slate-950">
                    {{ $nomination->election?->electionType?->name ?? 'Election' }}
                </p>

            </div>


            <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Election Date
                </p>

                <p class="mt-2 text-sm font-semibold text-slate-950">
                    {{ $nomination->election?->election_date?->format('d M Y') ?? 'Not announced' }}
                </p>

            </div>


            <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    State
                </p>

                <p class="mt-2 text-sm font-semibold text-slate-950">
                    {{ $nomination->election?->state?->name ?? 'Ogun State' }}
                </p>

            </div>

        </div>

    </section>


    {{-- Electoral area --}}
    <section class="mt-8 overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

            <h2 class="text-xl font-semibold text-slate-950">
                Electoral Area
            </h2>

        </div>


        <div class="p-6 sm:p-8">

            @if($nomination->lcda)

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        LCDA
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-950">
                        {{ $nomination->lcda->name }}
                    </p>

                </div>

            @elseif($nomination->ward)

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Ward
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-950">
                        {{ $nomination->ward->name }}
                    </p>

                </div>

            @elseif($nomination->lga)

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Local Government Area
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-950">
                        {{ $nomination->lga->name }}
                    </p>

                </div>

            @else

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Electoral Scope
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-950">
                        Statewide
                    </p>

                </div>

            @endif

        </div>

    </section>


    {{-- Public notice --}}
    <div class="mt-8 rounded-xl border border-emerald-200 bg-emerald-50 px-6 py-5 sm:px-8">

        <p class="text-sm leading-6 text-emerald-950">
            This candidate has been approved for publication by the
            Ogun State Independent Electoral Commission.
        </p>

    </div>


    {{-- Back --}}
    <div class="mt-8 border-t border-slate-200 pt-6">

        <a
            href="{{ route('public.candidates.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800 hover:text-emerald-950"
        >
            <span aria-hidden="true">
                ←
            </span>

            Back to all candidates
        </a>

    </div>

</div>

@endsection
