@extends('layouts.public')

@section('title', 'Candidates')

@section('meta_description')
    Approved candidates for elections conducted by the Ogun State Independent Electoral Commission.
@endsection

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- ==========================================================
         PAGE HEADER
         ========================================================== --}}

    <div class="mb-8">

        <div class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">
            OGSIEC Candidates
        </div>

        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
            Candidates
        </h1>

        <p class="mt-2 max-w-3xl text-base leading-7 text-slate-600">
            Search and view candidates approved by the Ogun State Independent
            Electoral Commission for active elections.
        </p>

    </div>


    {{-- ==========================================================
         FILTERS
         ========================================================== --}}

    <section class="mb-8 rounded-xl border border-slate-300 bg-white p-5 shadow-sm sm:p-6">

        <form
            method="GET"
            action="{{ route('public.candidates.index') }}"
        >

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">

                {{-- Election --}}
                <div>

                    <label
                        for="election"
                        class="mb-2 block text-sm font-semibold text-slate-800"
                    >
                        Election
                    </label>

                    <select
                        name="election"
                        id="election"
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            All Active Elections
                        </option>

                        @foreach($elections as $election)

                            <option
                                value="{{ $election->id }}"
                                @selected(
                                    (string) request('election')
                                    ===
                                    (string) $election->id
                                )
                            >
                                {{ $election->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Political Party --}}
                <div>

                    <label
                        for="party"
                        class="mb-2 block text-sm font-semibold text-slate-800"
                    >
                        Political Party
                    </label>

                    <select
                        name="party"
                        id="party"
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            All Political Parties
                        </option>

                        @foreach($parties as $party)

                            <option
                                value="{{ $party->id }}"
                                @selected(
                                    (string) request('party')
                                    ===
                                    (string) $party->id
                                )
                            >
                                {{ $party->acronym
                                    ? $party->name . ' (' . $party->acronym . ')'
                                    : $party->name
                                }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Position --}}
                <div>

                    <label
                        for="position"
                        class="mb-2 block text-sm font-semibold text-slate-800"
                    >
                        Position
                    </label>

                    <select
                        name="position"
                        id="position"
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            All Positions
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(
                                    (string) request('position')
                                    ===
                                    (string) $position->id
                                )
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- LGA --}}
                <div>

                    <label
                        for="lga"
                        class="mb-2 block text-sm font-semibold text-slate-800"
                    >
                        Local Government Area
                    </label>

                    <select
                        name="lga"
                        id="lga"
                        class="w-full rounded-lg border-slate-300 bg-white text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            All LGAs
                        </option>

                        @foreach($lgas as $lga)

                            <option
                                value="{{ $lga->id }}"
                                @selected(
                                    (string) request('lga')
                                    ===
                                    (string) $lga->id
                                )
                            >
                                {{ $lga->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- Search --}}
            <div class="mt-5">

                <label
                    for="search"
                    class="mb-2 block text-sm font-semibold text-slate-800"
                >
                    Search Candidate or Political Party
                </label>

                <div class="flex flex-col gap-3 sm:flex-row">

                    <input
                        type="search"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Enter candidate name, party name or acronym"
                        class="w-full rounded-lg border-slate-300 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:ring-emerald-600"
                    >

                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('public.candidates.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </section>


    {{-- ==========================================================
         REGISTER SUMMARY
         ========================================================== --}}

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

        <div>

            @if($nominations->total() > 0)

                <p class="text-sm text-slate-600">

                    Showing

                    <span class="font-semibold text-slate-950">
                        {{ $nominations->firstItem() }}
                    </span>

                    to

                    <span class="font-semibold text-slate-950">
                        {{ $nominations->lastItem() }}
                    </span>

                    of

                    <span class="font-semibold text-slate-950">
                        {{ number_format($nominations->total()) }}
                    </span>

                    approved candidates.

                </p>

            @else

                <p class="text-sm text-slate-600">
                    No approved candidates match your search criteria.
                </p>

            @endif

        </div>

    </div>


    {{-- ==========================================================
         CANDIDATE REGISTER
         ========================================================== --}}

    @if($nominations->isNotEmpty())

        <div class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                scope="col"
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-600"
                            >
                                Candidate
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-600"
                            >
                                Political Party
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-600"
                            >
                                Position
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-600"
                            >
                                Electoral Area
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-600"
                            >
                                Election
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-slate-600"
                            >
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach($nominations as $nomination)

                            <tr class="transition hover:bg-emerald-50/40">

                                {{-- Candidate --}}
                                <td class="whitespace-nowrap px-5 py-5">

                                    <div class="font-semibold text-slate-950">
                                        {{ $nomination->candidate?->full_name
                                            ?? $nomination->candidate_name
                                        }}
                                    </div>

                                    <div class="mt-1 text-xs text-emerald-800">
                                        Approved Candidate
                                    </div>

                                </td>


                                {{-- Party --}}
                                <td class="px-5 py-5">

                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ $nomination->politicalParty?->acronym
                                            ?? $nomination->politicalParty?->name
                                            ?? 'Not specified'
                                        }}
                                    </div>

                                    @if(
                                        $nomination->politicalParty?->acronym &&
                                        $nomination->politicalParty?->name
                                    )

                                        <div class="mt-1 max-w-xs text-xs text-slate-500">
                                            {{ $nomination->politicalParty->name }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Position --}}
                                <td class="whitespace-nowrap px-5 py-5">

                                    <span class="text-sm font-medium text-slate-800">
                                        {{ $nomination->position?->name
                                            ?? 'Not specified'
                                        }}
                                    </span>

                                </td>


                                {{-- Electoral Area --}}
                                <td class="px-5 py-5">

                                    <span class="text-sm text-slate-700">

                                        @if($nomination->ward)

                                            Ward: {{ $nomination->ward->name }}

                                        @elseif($nomination->lcda)

                                            LCDA: {{ $nomination->lcda->name }}

                                        @elseif($nomination->lga)

                                            LGA: {{ $nomination->lga->name }}

                                        @else

                                            Statewide

                                        @endif

                                    </span>

                                </td>


                                {{-- Election --}}
                                <td class="px-5 py-5">

                                    <div class="max-w-xs text-sm font-medium text-slate-800">
                                        {{ $nomination->election?->name
                                            ?? 'Not specified'
                                        }}
                                    </div>

                                </td>


                                {{-- Action --}}
                                <td class="whitespace-nowrap px-5 py-5 text-right">

                                    <a
                                        href="{{ route('public.candidates.show', $nomination) }}"
                                        class="inline-flex items-center rounded-lg border border-emerald-300 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ======================================================
             PAGINATION
             ====================================================== --}}

        @if($nominations->hasPages())

            <div class="mt-6">

                {{ $nominations->links() }}

            </div>

        @endif

    @else

        {{-- Empty state --}}
        <div class="rounded-xl border border-slate-300 bg-white px-6 py-12 text-center shadow-sm">

            <h2 class="text-xl font-semibold text-slate-950">
                No approved candidates found
            </h2>

            <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-600">
                Try changing your filters or search terms.
            </p>

            <a
                href="{{ route('public.candidates.index') }}"
                class="mt-5 inline-flex items-center rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
            >
                Clear Filters
            </a>

        </div>

    @endif

</div>

@endsection
