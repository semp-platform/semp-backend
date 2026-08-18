@extends('layouts.staff')

@section('title', 'Ward Results')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Election Results
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                {{ $ward->name }}
            </h1>

            <p class="mt-2 text-sm text-slate-600">
                Ward-level result for
                {{ $entries->first()->lga->name ?? 'Unknown LGA' }}.
            </p>

        </div>

        <a
            href="{{ route('staff.ict.results.show', $resultImport) }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            ← Back to Results
        </a>

    </div>


    {{-- Summary --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-3">

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


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Ward Winner
            </p>

            @if($parties->first())

                <p class="mt-3 text-xl font-bold text-slate-950">
                    {{ $parties->first()['party']->acronym }}
                </p>

                <p class="text-sm text-slate-500">
                    {{ number_format($parties->first()['votes']) }} votes
                </p>

            @else

                <p class="mt-3 text-xl font-bold text-slate-400">
                    —
                </p>

            @endif

        </div>

    </div>


    {{-- Party Totals --}}
    <div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

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
                            {{ $party['party']->acronym }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ $party['party']->name }}
                        </p>

                    </div>

                    <p class="text-lg font-bold text-slate-950">
                        {{ number_format($party['votes']) }}
                    </p>

                </div>

            @empty

                <div class="px-6 py-8 text-sm text-slate-500">
                    No votes recorded.
                </div>

            @endforelse

        </div>

    </div>


    {{-- Polling Unit Results --}}
    <div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Polling Unit Results
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Votes recorded at each polling unit.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr class="border-b border-slate-200">

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Code
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Polling Unit
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Winner
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Votes
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($pollingUnits as $unit)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                {{ $unit['code'] }}
                            </td>

                            <td class="px-6 py-4">

                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $unit['name'] ?? 'Unknown polling unit' }}
                                </p>

                            </td>

                            <td class="px-6 py-4">

                                @if($unit['winner'])

                                    <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                        {{ $unit['winner']['party']->acronym }}
                                    </span>

                                @else

                                    <span class="text-xs text-slate-400">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                <p class="font-bold text-slate-950">
                                    {{ number_format($unit['votes']) }}
                                </p>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-8 text-center text-sm text-slate-500"
                            >
                                No polling unit results available.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
