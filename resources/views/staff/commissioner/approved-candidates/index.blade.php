@extends('layouts.staff')

@section('title', 'Approved Candidates Pool')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Approved Candidates Pool
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Candidates whose nominations have received final Commissioner approval.
        </p>
    </div>


    {{-- Summary --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Approved Candidates
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $candidates->total() }}
            </p>
        </div>

    </div>


    {{-- Candidates --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-900">
                Approved Candidates
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Read-only record of nominations approved by the Commissioner.
            </p>
        </div>


        @if($candidates->count())

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
                                Election
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200">

                        @foreach($candidates as $candidate)

                            @php
                                $nomination = $candidate->nomination;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $candidate->full_name ?? $candidate->name ?? 'Candidate' }}
                                    </div>

                                    @if($candidate->nin ?? null)
                                        <div class="mt-1 text-xs text-slate-500">
                                            NIN: {{ $candidate->nin }}
                                        </div>
                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination?->politicalParty?->name ?? '—' }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination?->position?->name ?? '—' }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination?->election?->name ?? '—' }}
                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Approved
                                    </span>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <a
                                        href="{{ route('staff.commissioner.candidates.show', $candidate) }}"
                                        class="text-sm font-medium text-slate-700 hover:text-slate-900"
                                    >
                                        View Candidate
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $candidates->links() }}
            </div>

        @else

            <div class="px-6 py-12 text-center">

                <p class="font-medium text-slate-900">
                    No approved candidates yet.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Candidates will appear here after their nominations are approved by the Commissioner.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
