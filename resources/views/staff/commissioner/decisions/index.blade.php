@extends('layouts.staff')

@section('title', 'Commissioner Decisions')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Approved / Returned Decisions
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            History of nominations previously reviewed and decided by the Commissioner.
        </p>
    </div>


    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-medium text-slate-500">
                Total Decisions
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $decisions->total() }}
            </p>
        </div>


        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-medium text-slate-500">
                Decisions on This Page
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $decisions->count() }}
            </p>
        </div>


        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-medium text-slate-500">
                Department
            </p>

            <p class="mt-2 text-lg font-bold text-slate-900">
                Commissioner
            </p>
        </div>

    </div>


    {{-- Decision History --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Decision History
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Previously completed Commissioner decisions and their recorded details.
            </p>
        </div>


        @if ($decisions->isNotEmpty())

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
                                Decision
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Decided By
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach ($decisions as $decision)

                            @php
                                $nomination = $decision->nomination;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                {{-- Candidate --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $nomination?->candidate_name ?? 'Candidate' }}
                                    </div>

                                    @if ($nomination?->candidate)
                                        <div class="mt-1 text-xs text-slate-500">
                                            Candidate ID:
                                            {{ $nomination->candidate->id }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Political Party --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $nomination?->politicalParty?->name ?? '—' }}

                                </td>


                                {{-- Position --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $nomination?->position?->name ?? '—' }}

                                </td>


                                {{-- Decision --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @if ($decision->action === 'approved')

                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Approved
                                        </span>

                                    @elseif ($decision->action === 'returned')

                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Returned
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ ucfirst($decision->action) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Decided By --}}
                                <td class="px-6 py-4">

                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $decision->user?->name ?? '—' }}
                                    </div>

                                    @if ($decision->user?->email)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $decision->user->email }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Date --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    {{ $decision->created_at?->format('d M Y') ?? '—' }}

                                    @if ($decision->created_at)
                                        <div class="mt-1 text-xs text-slate-400">
                                            {{ $decision->created_at->format('H:i') }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    @if ($nomination)

                                        <a
    href="{{ route('staff.commissioner.candidates.show', $decision->nomination->candidate) }}"
    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50"
>
    View Candidate
</a>

                                    @else

                                        <span class="text-sm text-slate-400">
                                            Unavailable
                                        </span>

                                    @endif

                                </td>

                            </tr>


                            {{-- Decision comment / reason --}}
                            @if (!empty($decision->comment) || !empty($decision->reason))

                                <tr class="bg-slate-50">

                                    <td colspan="7" class="px-6 py-3">

                                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Decision Notes
                                        </div>

                                        <div class="mt-1 text-sm text-slate-700">
                                            {{ $decision->comment ?? $decision->reason }}
                                        </div>

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if ($decisions->hasPages())

                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $decisions->links() }}
                </div>

            @endif


        @else

            <div class="px-6 py-16 text-center">

                <h3 class="text-lg font-semibold text-slate-900">
                    No decisions recorded yet
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Commissioner-approved and returned nominations will appear here
                    after a decision has been recorded.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
