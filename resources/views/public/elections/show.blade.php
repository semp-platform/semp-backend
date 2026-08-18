@extends('layouts.public')

@section('title', $election->name)

@section('meta_description')
    Public information for {{ $election->name }} conducted by the Ogun State Independent Electoral Commission.
@endsection

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Back --}}
    <div class="mb-6">

        <a
            href="{{ route('public.elections.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800 hover:text-emerald-950"
        >
            <span aria-hidden="true">←</span>
            Back to Elections
        </a>

    </div>


    {{-- ==========================================================
         ELECTION HEADER
         ========================================================== --}}

    <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        {{-- Green accent --}}
        <div class="h-1 bg-emerald-700"></div>

        <div class="px-6 py-7 sm:px-8 sm:py-8">

            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                <div class="max-w-3xl">

                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-800">
                        {{ $election->electionType?->name ?? 'Election' }}
                    </p>

                    <h1 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-slate-950 sm:text-4xl">
                        {{ $election->name }}
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Ogun State Independent Electoral Commission
                    </p>

                </div>


                <div>

                    <span
                        class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold capitalize text-emerald-800"
                    >
                        {{ str_replace('_', ' ', $election->status) }}
                    </span>

                </div>

            </div>

        </div>


        {{-- Key information --}}
        <div class="border-t border-slate-200 bg-slate-50">

            <div class="grid divide-y divide-slate-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">

                <div class="px-6 py-5 sm:px-8">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Election Date
                    </p>

                    <p class="mt-2 text-base font-semibold text-slate-950">
                        {{ $election->election_date?->format('d M Y') ?? 'Not announced' }}
                    </p>

                </div>


                <div class="px-6 py-5 sm:px-8">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Election Type
                    </p>

                    <p class="mt-2 text-base font-semibold text-slate-950">
                        {{ $election->electionType?->name ?? 'Not specified' }}
                    </p>

                </div>


                <div class="px-6 py-5 sm:px-8">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        State
                    </p>

                    <p class="mt-2 text-base font-semibold text-slate-950">
                        {{ $election->state?->name ?? 'Ogun State' }}
                    </p>

                </div>


                <div class="px-6 py-5 sm:px-8">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Status
                    </p>

                    <p class="mt-2 text-base font-semibold capitalize text-slate-950">
                        {{ str_replace('_', ' ', $election->status) }}
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- ==========================================================
         ELECTION TIMETABLE
         ========================================================== --}}

    <section class="mt-8 overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

            <h2 class="text-xl font-semibold text-slate-950">
                Election Timetable
            </h2>

            <p class="mt-1 text-sm leading-6 text-slate-600">
                Important dates relating to this election.
            </p>

        </div>


        <div class="divide-y divide-slate-200">

            {{-- Nomination opens --}}
            <div class="flex flex-col gap-2 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <span class="text-sm font-semibold text-slate-800">
                    Nomination Period Opens
                </span>

                <span class="text-sm text-slate-700">
                    {{ $election->nomination_open_date?->format('d M Y') ?? 'Not announced' }}
                </span>

            </div>


            {{-- Nomination closes --}}
            <div class="flex flex-col gap-2 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <span class="text-sm font-semibold text-slate-800">
                    Nomination Period Closes
                </span>

                <span class="text-sm text-slate-700">
                    {{ $election->nomination_close_date?->format('d M Y') ?? 'Not announced' }}
                </span>

            </div>


            {{-- Screening --}}
            <div class="flex flex-col gap-2 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <span class="text-sm font-semibold text-slate-800">
                    Screening
                </span>

                <span class="text-sm text-slate-700">
                    {{ $election->screening_date?->format('d M Y') ?? 'Not announced' }}
                </span>

            </div>


            {{-- Appeal --}}
            <div class="flex flex-col gap-2 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <span class="text-sm font-semibold text-slate-800">
                    Appeal Deadline
                </span>

                <span class="text-sm text-slate-700">
                    {{ $election->appeal_deadline?->format('d M Y') ?? 'Not announced' }}
                </span>

            </div>


            {{-- Election day --}}
            <div class="flex flex-col gap-2 bg-emerald-50/50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <span class="text-sm font-semibold text-slate-950">
                    Election Day
                </span>

                <span class="text-sm font-bold text-emerald-900">
                    {{ $election->election_date?->format('d M Y') ?? 'Not announced' }}
                </span>

            </div>


            {{-- Result declaration --}}
            <div class="flex flex-col gap-2 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <span class="text-sm font-semibold text-slate-800">
                    Result Declaration
                </span>

                <span class="text-sm text-slate-700">
                    {{ $election->result_declaration_date?->format('d M Y') ?? 'Not announced' }}
                </span>

            </div>

        </div>

    </section>


    {{-- ==========================================================
     ELECTORAL AREA
     ========================================================== --}}

@php
    $electoralAreas = collect([
        [
            'label' => 'State',
            'value' => $election->state?->name,
        ],
        [
            'label' => 'Local Government Area',
            'value' => $election->lga?->name,
        ],
        [
            'label' => 'LCDA',
            'value' => $election->lcda?->name,
        ],
        [
            'label' => 'Ward',
            'value' => $election->ward?->name,
        ],
    ])->filter(fn ($area) => filled($area['value']))->values();
@endphp

@if($electoralAreas->isNotEmpty())

    <section class="mt-8 overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

            <h2 class="text-xl font-semibold text-slate-950">
                Electoral Area
            </h2>

            <p class="mt-1 text-sm leading-6 text-slate-600">
                Electoral area information associated with this election.
            </p>

        </div>

        <div class="grid gap-4 p-6 sm:p-8
            @if($electoralAreas->count() === 1)
                grid-cols-1
            @elseif($electoralAreas->count() === 2)
                grid-cols-1 sm:grid-cols-2
            @elseif($electoralAreas->count() === 3)
                grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
            @else
                grid-cols-1 sm:grid-cols-2 lg:grid-cols-4
            @endif
        ">

            @foreach($electoralAreas as $area)

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ $area['label'] }}
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-950">
                        {{ $area['value'] }}
                    </p>

                </div>

            @endforeach

        </div>

    </section>

@endif
    {{-- ==========================================================
         ELECTIVE POSITIONS
         ========================================================== --}}

    @if($election->electionPositions->count())

        <section class="mt-8 overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

                <h2 class="text-xl font-semibold text-slate-950">
                    Elective Positions
                </h2>

                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Positions being contested in this election.
                </p>

            </div>


            <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3 sm:p-8">

                @foreach($election->electionPositions as $electionPosition)

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">

                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-800">
                                E
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-slate-950">
                                    {{ $electionPosition->position?->name ?? 'Position' }}
                                </p>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>

    @endif


    {{-- ==========================================================
         PUBLIC INFORMATION MODULES
         ========================================================== --}}

    <section class="mt-8">

        <div class="mb-5">

            <h2 class="text-xl font-semibold text-slate-950">
                Election Information
            </h2>

            <p class="mt-1 text-sm leading-6 text-slate-600">
                Additional information will be published here as the
                Commission releases it.
            </p>

        </div>


        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Candidates --}}
            <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-sm font-bold text-emerald-800">
                    C
                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-950">
                    Candidates
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Approved candidates will be published here when
                    the Commission makes them available.
                </p>

            </div>


            {{-- Notices --}}
            <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-sm font-bold text-emerald-800">
                    N
                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-950">
                    Notices
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Official notices and announcements relating to
                    this election will appear here.
                </p>

            </div>


            {{-- Publications --}}
            <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-sm font-bold text-emerald-800">
                    P
                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-950">
                    Publications
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Official election documents and publications
                    will be made available here.
                </p>

            </div>


            {{-- Results --}}
            <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-sm font-bold text-emerald-800">
                    R
                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-950">
                    Results
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Election results will appear here when officially
                    published by the Commission.
                </p>

            </div>

        </div>

    </section>


    {{-- Back to elections --}}
    <div class="mt-8 border-t border-slate-200 pt-6">

        <a
            href="{{ route('public.elections.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800 hover:text-emerald-950"
        >
            <span aria-hidden="true">←</span>
            Back to all elections
        </a>

    </div>

</div>

@endsection
