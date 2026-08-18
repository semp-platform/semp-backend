@extends('layouts.staff')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Candidates
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            View candidate records and supporting information for Commissioner review.
        </p>
    </div>


    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Total Candidates
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $candidates->total() }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Current Page
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $candidates->count() }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Access
            </p>

            <p class="mt-2 text-lg font-bold text-slate-900">
                Read Only
            </p>
        </div>

    </div>


    {{-- Candidate List --}}
    <div class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">

        <div class="border-b border-slate-300 bg-slate-50/50 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Candidate Records
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Select a candidate to view the complete record and supporting documents.
            </p>

        </div>


        @if($candidates->isNotEmpty())

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
                                Electoral Area
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach($candidates as $candidate)

                            @php
                                $nomination = $candidate->nomination;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                {{-- Candidate --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $candidate->full_name }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        Candidate #{{ $candidate->id }}
                                    </div>

                                </td>


                                {{-- Political Party --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $nomination?->politicalParty?->name ?? '—' }}

                                </td>


                                {{-- Position --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $nomination?->position?->name ?? '—' }}

                                </td>


                                {{-- Electoral Area --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $nomination?->electoral_area ?? '—' }}

                                </td>


                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @if($nomination)

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ ucfirst(str_replace('_', ' ', $nomination->workflow_status ?? $nomination->status)) }}
                                        </span>

                                    @else

                                        <span class="text-sm text-slate-500">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <a
                                        href="{{ route(
                                            'staff.commissioner.candidates.show',
                                            $candidate
                                        ) }}"
                                        class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                                    >
                                        View Record
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($candidates->hasPages())

                <div class="border-t border-slate-300 px-6 py-4">

                    {{ $candidates->links() }}

                </div>

            @endif

        @else

            <div class="p-10 text-center">

                <p class="text-sm font-medium text-slate-900">
                    No candidate records found.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Candidates will appear here once they have associated nominations.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
