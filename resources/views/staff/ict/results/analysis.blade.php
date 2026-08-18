@extends('layouts.staff')

@section('title', 'Result Import Analysis')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div>

        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
            ICT Department
        </p>

        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
            Result Import Analysis
        </h1>

        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
            The uploaded workbook has been analysed without importing
            any election result into the SEMP database.
        </p>

    </div>


    {{-- =========================================================
         ELECTION / FILE
         ========================================================= --}}

    <div class="mt-8 grid gap-4 md:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Election
            </p>

            <p class="mt-2 text-lg font-bold text-slate-950">
                {{ $election->name }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                {{ $election->electionType?->name ?? 'Election' }}

                @if($election->election_date)
                    — {{ $election->election_date->format('d M Y') }}
                @endif
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Uploaded Workbook
            </p>

            <p class="mt-2 break-all text-lg font-bold text-slate-950">
                {{ $fileName }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                {{ number_format($fileSize / 1024, 1) }} KB
            </p>

        </div>

    </div>


    {{-- =========================================================
         SUMMARY
         ========================================================= --}}

    <div class="mt-8">

        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">
            Workbook Summary
        </p>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-2xl font-black text-slate-950">
                    {{ $worksheetCount }}
                </p>

                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Worksheets
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-2xl font-black text-slate-950">
                    {{ number_format($totalRows) }}
                </p>

                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Result Rows
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-2xl font-black text-slate-950">
                    {{ number_format($totalPollingUnits) }}
                </p>

                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Polling Units
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-2xl font-black text-slate-950">
                    {{ $totalPartyColumns }}
                </p>

                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Party Columns
                </p>
            </div>

            <div
    class="rounded-xl border p-5 shadow-sm
    {{ $hasErrors
        ? 'border-red-200 bg-red-50'
        : ($hasWarnings
            ? 'border-amber-200 bg-amber-50'
            : 'border-emerald-200 bg-emerald-50') }}"
>

    <p
        class="text-2xl font-black
        {{ $hasErrors
            ? 'text-red-800'
            : ($hasWarnings
                ? 'text-amber-800'
                : 'text-emerald-800') }}"
    >
        {{ $totalIssues }}
    </p>

    <p
        class="mt-1 text-xs font-bold uppercase tracking-wider
        {{ $hasErrors
            ? 'text-red-700'
            : ($hasWarnings
                ? 'text-amber-700'
                : 'text-emerald-700') }}"
    >
        Issues Found
    </p>

</div>
        </div>
        <div class="mt-3 flex flex-wrap gap-4 text-xs font-semibold">

    <span class="text-red-700">
        {{ $totalErrors }} blocking {{ $totalErrors === 1 ? 'error' : 'errors' }}
    </span>

    <span class="text-amber-700">
        {{ $totalWarnings }} {{ $totalWarnings === 1 ? 'warning' : 'warnings' }}
    </span>

</div>

    </div>


    {{-- =========================================================
     RESULT STATUS
     ========================================================= --}}

@if($hasErrors)

    <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-5">

        <div class="flex items-start gap-3">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                !
            </div>

            <div>

                <p class="font-bold text-red-950">
                    Import blocked
                </p>

                <p class="mt-1 text-sm leading-6 text-red-800">
                    {{ $totalErrors }}
                    {{ $totalErrors === 1 ? 'blocking error was' : 'blocking errors were' }}
                    detected and must be resolved before this result can be imported.
                </p>

                @if($hasWarnings)

                    <p class="mt-1 text-sm text-amber-800">
                        {{ $totalWarnings }}
                        {{ $totalWarnings === 1 ? 'warning was' : 'warnings were' }}
                        also detected and should be reviewed.
                    </p>

                @endif

            </div>

        </div>

    </div>

@elseif($hasWarnings)

    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-5">

        <div class="flex items-start gap-3">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-bold text-white">
                !
            </div>

            <div>

                <p class="font-bold text-amber-950">
                    Ready for import — review warnings
                </p>

                <p class="mt-1 text-sm leading-6 text-amber-800">
                    No blocking errors were detected.
                    {{ $totalWarnings }}
                    {{ $totalWarnings === 1 ? 'warning requires' : 'warnings require' }}
                    review before continuing.
                </p>

                <p class="mt-1 text-sm text-amber-800">
                    No result data has been saved.
                </p>

            </div>

        </div>

    </div>

@else

    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-5">

        <div class="flex items-start gap-3">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white">
                ✓
            </div>

            <div>

                <p class="font-bold text-emerald-950">
                    Ready for import
                </p>

                <p class="mt-1 text-sm leading-6 text-emerald-800">
                    The workbook passed the initial validation checks.
                    No blocking errors or warnings were detected.
                </p>

                <p class="mt-1 text-sm text-emerald-800">
                    No result data has been saved.
                </p>

            </div>

        </div>

    </div>

@endif

    {{-- =========================================================
         WORKSHEETS
         ========================================================= --}}

    <div class="mt-10">

        <div class="mb-5">

            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">
                Worksheet Analysis
            </p>

            <h2 class="mt-1 text-xl font-bold text-slate-950">
                Wards
            </h2>

        </div>


        <div class="space-y-5">

            @foreach($worksheets as $worksheet)

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                    {{-- Sheet header --}}
                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-5">

                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                                    Worksheet
                                </p>

                                <h3 class="mt-1 text-lg font-bold text-slate-950">
                                    {{ $worksheet['name'] }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">

                                    @if($worksheet['ward_name'])
                                        {{ $worksheet['ward_name'] }}
                                    @else
                                        Ward name not detected
                                    @endif

                                    @if($worksheet['ward_code'])
                                        · Code {{ $worksheet['ward_code'] }}
                                    @endif

                                    @if($worksheet['lga'])
                                        · {{ $worksheet['lga'] }}
                                    @endif

                                </p>

                            </div>


                            <div class="flex flex-wrap gap-2">

                                <span class="rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                                    {{ $worksheet['polling_units'] }} polling units
                                </span>

                                <span class="rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                                    {{ count($worksheet['parties']) }} parties
                                </span>

                                @if($worksheet['missing_names'] > 0)

                                    <span class="rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                        {{ $worksheet['missing_names'] }} missing names
                                    </span>

                                @endif

                                @if($worksheet['duplicate_codes'] > 0)

                                    <span class="rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-800">
                                        {{ $worksheet['duplicate_codes'] }} duplicate codes
                                    </span>

                                @endif

                                @if($worksheet['invalid_votes'] > 0)

                                    <span class="rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-800">
                                        {{ $worksheet['invalid_votes'] }} invalid votes
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Parties --}}
                    <div class="border-b border-slate-100 px-5 py-4">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Party Columns Detected
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">

                            @foreach($worksheet['parties'] as $party)

                                <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $party }}
                                </span>

                            @endforeach

                        </div>

                    </div>


                    {{-- Issues --}}
                    @if(count($worksheet['issues']) > 0)

                        <div class="border-b border-slate-100 px-5 py-4">

                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Issues
                            </p>

                            <div class="mt-3 space-y-2">

                                @foreach($worksheet['issues'] as $issue)

                                    <div
                                        class="rounded-lg px-3 py-2 text-sm
                                        {{ $issue['severity'] === 'error'
                                            ? 'bg-red-50 text-red-800'
                                            : 'bg-amber-50 text-amber-800' }}"
                                    >

                                        <span class="font-semibold">
                                            {{ ucfirst($issue['severity']) }}
                                        </span>

                                        @if(isset($issue['row']))
                                            <span class="text-xs opacity-75">
                                                — Row {{ $issue['row'] }}
                                            </span>
                                        @endif

                                        <span>
                                            {{ $issue['message'] }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- Preview --}}
                    @if(count($worksheet['preview']) > 0)

                        <div class="overflow-x-auto">

                            <div class="border-b border-slate-100 px-5 py-4">

                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Result Preview
                                </p>

                            </div>

                            <table class="min-w-full text-left text-sm">

                                <thead class="bg-slate-50">

                                    <tr>

                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            S/N
                                        </th>

                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Code
                                        </th>

                                        <th class="min-w-[240px] px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Polling Station
                                        </th>

                                        @foreach($worksheet['parties'] as $party)

                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                                {{ $party }}
                                            </th>

                                        @endforeach

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-100">

                                    @foreach($worksheet['preview'] as $row)

                                        <tr>

                                            <td class="px-4 py-3 font-medium text-slate-700">
                                                {{ $row['sn'] }}
                                            </td>

                                            <td class="px-4 py-3 font-mono text-xs text-slate-600">
                                                {{ $row['code'] }}
                                            </td>

                                            <td class="px-4 py-3 text-slate-800">
                                                {{ $row['name'] ?: '—' }}
                                            </td>

                                            @foreach($worksheet['parties'] as $party)

                                                <td class="px-4 py-3 text-center text-slate-700">
                                                    {{ $row['votes'][$party] ?? '' }}
                                                </td>

                                            @endforeach

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    </div>


    {{-- =========================================================
         ACTIONS
         ========================================================= --}}

    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

        <a
            href="{{ route('staff.ict.results.create') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Upload Another File
        </a>

        @if($canImport)

    @if($canImport)

    <form
        method="POST"
        action="{{ route('staff.ict.results.import') }}"
    >
        @csrf

        <input
            type="hidden"
            name="pending_file"
            value="{{ $pendingFile }}"
        >

        <input
            type="hidden"
            name="election_id"
            value="{{ $election->id }}"
        >

        <input
            type="hidden"
            name="position_id"
            value="{{ $position->id }}"
        >

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800"
        >
            Continue to Import
        </button>
    </form>

@else

    <button
        type="button"
        disabled
        class="inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-slate-300 px-5 py-3 text-sm font-semibold text-white"
    >
        Import Blocked
    </button>

@endif

@else

    <button
        type="button"
        disabled
        class="inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-slate-300 px-5 py-3 text-sm font-semibold text-white"
    >
        Import Blocked
    </button>

@endif
    </div>

</div>

@endsection
