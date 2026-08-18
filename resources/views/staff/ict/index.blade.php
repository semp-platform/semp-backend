@extends('layouts.staff')

@section('title', 'Results Management')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                ICT Department
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                Results Management
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                Upload, analyse, review and publish official election results.
            </p>
        </div>

        @can('results.manage')
            <a
                href="{{ route('staff.ict.results.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800"
            >
                Upload Result
            </a>
        @endcan

    </div>


    {{-- Status cards --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Draft Results
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ $draftResults }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Imported and awaiting review
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Published
            </p>

            <p class="mt-3 text-3xl font-bold text-emerald-700">
                {{ $publishedResults }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Available to the public
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Archived
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ $archivedResults }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Historical result imports
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Published Votes
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ number_format($publishedVotes) }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Total published votes
            </p>
        </div>

    </div>


    {{-- Imported results --}}
    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-lg font-bold text-slate-950">
                        Election Results
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Result workbooks imported by the ICT Department.
                    </p>
                </div>

                <div class="text-sm font-semibold text-slate-500">
                    {{ $imports->count() }}
                    {{ $imports->count() === 1 ? 'import' : 'imports' }}
                </div>

            </div>

        </div>


        @if($imports->isEmpty())

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">

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
                            d="M9 17v-2m3 2v-4m3 4v-6m2 9H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586A2 2 0 0115 3.586L19.414 8A2 2 0 0120 9.414V19a2 2 0 01-2 2z"
                        />
                    </svg>

                </div>

                <h3 class="mt-5 text-lg font-semibold text-slate-950">
                    No results uploaded yet
                </h3>

                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                    Upload an election results spreadsheet to begin the
                    validation and publication process.
                </p>

                @can('results.manage')

                    <a
                        href="{{ route('staff.ict.results.create') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800"
                    >
                        Upload First Result
                    </a>

                @endcan

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Election
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Position
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Worksheets
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Polling Units
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Entries
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Imported
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($imports as $import)

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm font-semibold text-slate-950">
                                        {{ $import->election->name ?? 'Election #' . $import->election_id }}
                                    </p>

                                    @if($import->election?->election_date)
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $import->election->election_date->format('d M Y') }}
                                        </p>
                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm font-medium text-slate-700">
                                        {{ $import->position->name ?? 'Position #' . $import->position_id }}
                                    </p>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ number_format($import->worksheet_count) }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ number_format($import->polling_unit_count) }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ number_format($import->result_entry_count) }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    @if($import->status === 'published')

                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Published
                                        </span>

                                    @elseif($import->status === 'archived')

                                        <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                            Archived
                                        </span>

                                    @elseif($import->status === 'imported')

                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Draft
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            {{ ucfirst($import->status) }}
                                        </span>

                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <p class="text-sm text-slate-700">
                                        {{ $import->imported_at?->format('d M Y') ?? '—' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $import->imported_at?->format('H:i') ?? '' }}
                                    </p>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection
