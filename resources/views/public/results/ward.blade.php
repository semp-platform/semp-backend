@extends('layouts.app')

@section('title', 'Ward Results')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Official Election Results
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                {{ $ward->name }}
            </h1>

            <p class="mt-2 text-sm text-slate-600">
                {{ $lga->name ?? 'Local Government Area' }}
                —
                {{ $resultImport->election->name ?? 'Election' }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                {{ $resultImport->position->name ?? 'Election Position' }}
            </p>

        </div>

        <a
            href="{{ route('public.results.show', $resultImport) }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            ← Back to Results
        </a>

    </div>


    {{-- Summary --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Ward
            </p>

            <p class="mt-3 text-xl font-bold text-slate-950">
                {{ $ward->name }}
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Polling Units
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ $pollingUnits->count() }}
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Total Votes
            </p>

            <p class="mt-3 text-3xl font-bold text-emerald-700">
                {{ number_format($totalVotes) }}
            </p>

        </div>

    </div>


    {{-- Party totals --}}
    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Party Totals
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Total votes recorded in this ward.
            </p>

        </div>


        <div class="divide-y divide-slate-100">

            @forelse($parties as $party)

                <div class="flex items-center justify-between px-6 py-4">

                    <div>

                        <p class="font-semibold text-slate-900">
                            {{ $party['party']->acronym ?? 'Unknown' }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ $party['party']->name ?? 'Unknown party' }}
                        </p>

                    </div>

                    <p class="text-lg font-bold text-slate-950">
                        {{ number_format($party['votes']) }}
                    </p>

                </div>

            @empty

                <div class="px-6 py-8 text-sm text-slate-500">
                    No party totals available.
                </div>

            @endforelse

        </div>

    </div>


    {{-- Polling-unit results --}}
    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Polling Unit Results
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Votes recorded at each polling unit.
            </p>

        </div>


        <div class="divide-y divide-slate-100">

            @forelse($pollingUnits as $unit)

                <div class="px-6 py-5">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-sm font-bold text-slate-950">
                                {{ $unit['code'] }}
                            </p>

                            @if($unit['name'])
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $unit['name'] }}
                                </p>
                            @endif

                        </div>


                        <div class="text-left sm:text-right">

                            @if($unit['winner']['party'] ?? null)

                                <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    {{ $unit['winner']['party']->acronym }}
                                </span>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ number_format($unit['winner']['votes']) }} winning votes
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- Party votes --}}
                    <div class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach($unit['parties'] as $party)

                            <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">

                                <div>

                                    <span class="text-xs font-semibold text-slate-700">
                                        {{ $party['party']->acronym ?? 'Unknown' }}
                                    </span>

                                </div>

                                <span class="text-sm font-bold text-slate-950">
                                    {{ number_format($party['votes']) }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                </div>

            @empty

                <div class="px-6 py-10 text-sm text-slate-500">
                    No polling-unit results available.
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
