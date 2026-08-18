@extends('layouts.staff')

@section('title', 'Final Publication Approval')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Final Publication Approval
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Review the Commissioner-approved candidates before final publication.
        </p>
    </div>


    {{-- Filters --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Candidate List Filters
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Filter the approved candidate list by party, position, LGA, gender or election.
            </p>

        </div>


        <form
            method="GET"
            action="{{ route('staff.commissioner.final-publication.index') }}"
            class="grid gap-4 p-6 md:grid-cols-2 lg:grid-cols-3"
        >

            {{-- Political Party --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Political Party
                </label>

                <select
                    name="political_party_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm"
                >
                    <option value="">
                        All Parties
                    </option>

                    @foreach($politicalParties as $party)

                        <option
                            value="{{ $party->id }}"
                            @selected(request('political_party_id') == $party->id)
                        >
                            {{ $party->name }}
                        </option>

                    @endforeach

                </select>
            </div>


            {{-- Position --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Position
                </label>

                <select
                    name="position_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm"
                >
                    <option value="">
                        All Positions
                    </option>

                    @foreach($positions as $position)

                        <option
                            value="{{ $position->id }}"
                            @selected(request('position_id') == $position->id)
                        >
                            {{ $position->name }}
                        </option>

                    @endforeach

                </select>
            </div>


            {{-- LGA --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    LGA
                </label>

                <select
                    name="lga_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm"
                >
                    <option value="">
                        All LGAs
                    </option>

                    @foreach($lgas as $lga)

                        <option
                            value="{{ $lga->id }}"
                            @selected(request('lga_id') == $lga->id)
                        >
                            {{ $lga->name }}
                        </option>

                    @endforeach

                </select>
            </div>


            {{-- Gender --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Gender
                </label>

                <select
                    name="gender"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm"
                >
                    <option value="">
                        All Genders
                    </option>

                    <option
                        value="male"
                        @selected(request('gender') === 'male')
                    >
                        Male
                    </option>

                    <option
                        value="female"
                        @selected(request('gender') === 'female')
                    >
                        Female
                    </option>

                </select>
            </div>

{{-- Qualification --}}
<div>
    <label class="mb-2 block text-sm font-medium text-slate-700">
        Qualification
    </label>

    <select
        name="qualification"
        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm"
    >
        <option value="">
            All Qualifications
        </option>

        @foreach(
            $nominations
                ->getCollection()
                ->pluck('candidate.qualification')
                ->filter()
                ->unique()
                ->sort()
            as $qualification
        )
            <option
                value="{{ $qualification }}"
                @selected(request('qualification') === $qualification)
            >
                {{ $qualification }}
            </option>
        @endforeach

    </select>
</div>

            {{-- Election --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Election
                </label>

                <select
                    name="election_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm"
                >
                    <option value="">
                        All Elections
                    </option>

                    @foreach($elections as $election)

                        <option
                            value="{{ $election->id }}"
                            @selected(request('election_id') == $election->id)
                        >
                            {{ $election->name }}
                        </option>

                    @endforeach

                </select>
            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800"
                >
                    Apply Filters
                </button>

                <a
                    href="{{ route('staff.commissioner.final-publication.index') }}"
                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Summary --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Candidates in Current View
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $nominations->total() }}
            </p>

        </div>

    </div>

{{-- Export Actions --}}
<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h2 class="font-semibold text-slate-900">
                Final Publication Export
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Export the currently filtered approved candidate list for final publication.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('staff.commissioner.final-publication.export.excel', request()->query()) }}"
                class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700"
            >
                Export Excel
            </a>

            <a
                href="{{ route('staff.commissioner.final-publication.export.pdf', request()->query()) }}"
                class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700"
            >
                Export PDF
            </a>

        </div>

    </div>

</div>
    {{-- Candidate List --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Final Candidate List
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Only nominations already approved by the Commissioner are shown.
            </p>

        </div>


        @if($nominations->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Candidate
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Political Party
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Position
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                LGA
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Gender
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Qualification
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200">

                        @foreach($nominations as $nomination)

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $nomination->candidate?->full_name ?? '—' }}
                                    </div>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination->politicalParty?->name ?? '—' }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination->position?->name ?? '—' }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination->lga?->name ?? '—' }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-slate-700">
                                    {{ $nomination->candidate?->gender ?? '—' }}
                                </td>


                                <td class="px-6 py-4 text-sm text-slate-700">

                                    <div>
                                        {{ $nomination->candidate?->qualification ?? '—' }}
                                    </div>

                                    @if($nomination->candidate?->qualification_details)

                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $nomination->candidate->qualification_details }}
                                        </div>

                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Approved
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="border-t border-slate-200 px-6 py-4">
                {{ $nominations->links() }}
            </div>

        @else

            <div class="px-6 py-12 text-center">

                <p class="font-medium text-slate-900">
                    No approved candidates match the selected filters.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Try changing or clearing the filters.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
