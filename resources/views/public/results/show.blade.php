@extends('layouts.app')

@section('title', 'Election Result')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Official Election Results
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                {{ $resultImport->election->name ?? 'Election Result' }}
            </h1>

            <p class="mt-2 text-sm text-slate-600">
                {{ $resultImport->position->name ?? 'Election Position' }}
            </p>

        </div>

        <a
            href="{{ route('public.results.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            ← Back to Results
        </a>

    </div>


    {{-- Summary --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Local Government Areas
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ $lgas->count() }}
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Polling Units
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ $entries->groupBy(function ($entry) {
                    return $entry->lga_id . ':' .
                        $entry->ward_id . ':' .
                        $entry->polling_unit_code;
                })->count() }}
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
                Total votes recorded for each political party.
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
                        {{ number_format($party['votes'] ?? 0) }}
                    </p>

                </div>

            @empty

                <div class="px-6 py-8 text-sm text-slate-500">
                    No party totals available.
                </div>

            @endforelse

        </div>

    </div>


    {{-- LGA results --}}
    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Local Government Areas
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Results grouped by local government and ward.
            </p>

        </div>


        <div class="divide-y divide-slate-100">

            @forelse($lgas as $lga)

                <div>

                    {{-- LGA header --}}
                    <div class="bg-slate-50 px-6 py-5">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <h3 class="font-bold text-slate-950">
                                    {{ $lga['lga']->name ?? 'Unknown LGA' }}
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $lga['polling_units'] }} polling units
                                </p>

                            </div>

                            <div class="text-left sm:text-right">

                                @if($lga['winner']['party'] ?? null)

                                    <p class="text-xs text-slate-500">
                                        LGA Leader
                                    </p>

                                    <p class="font-bold text-emerald-700">
                                        {{ $lga['winner']['party']->acronym }}
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        {{ number_format($lga['winner']['votes']) }} votes
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Wards --}}
                    <div class="divide-y divide-slate-100">

                        @forelse($lga['wards'] as $ward)

                            <div class="px-6 py-5">

                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                    <div>

                                        <a
    href="{{ route(
        'public.results.ward',
        [
            'resultImport' => $resultImport,
            'ward' => $ward['ward'],
        ]
    ) }}"
    class="group block"
>
    <h4 class="font-semibold text-slate-900 group-hover:text-emerald-700">
        {{ $ward['ward']->name ?? 'Unknown Ward' }}
    </h4>

    <p class="mt-1 text-xs text-slate-500">
        {{ $ward['polling_units'] }} polling units
    </p>
</a>
                                    </div>


                                    <div class="text-left sm:text-right">

    @if($ward['winner']['party'] ?? null)

        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
            {{ $ward['winner']['party']->acronym }}
        </span>

        <p class="mt-1 text-xs text-slate-500">
            {{ number_format($ward['winner']['votes']) }} winning votes
        </p>

    @endif

    <a
        href="{{ route(
            'public.results.ward',
            [
                'resultImport' => $resultImport,
                'ward' => $ward['ward'],
            ]
        ) }}"
        class="mt-2 inline-block text-xs font-semibold text-emerald-700 hover:text-emerald-900"
    >
        View Results →
    </a>

</div>

                                </div>


                                {{-- Ward party totals --}}
                                <div class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">

                                    @foreach($ward['parties'] as $party)

                                        <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">

                                            <span class="text-xs font-semibold text-slate-700">
                                                {{ $party['party']->acronym ?? 'Unknown' }}
                                            </span>

                                            <span class="text-sm font-bold text-slate-950">
                                                {{ number_format($party['votes']) }}
                                            </span>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @empty

                            <div class="px-6 py-8 text-sm text-slate-500">
                                No ward results available.
                            </div>

                        @endforelse

                    </div>

                </div>

            @empty

                <div class="px-6 py-10 text-sm text-slate-500">
                    No LGA results available.
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
