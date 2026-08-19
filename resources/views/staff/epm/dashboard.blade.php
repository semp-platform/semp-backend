@extends('layouts.staff')

@section('title', 'EPM Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Page heading --}}
    <div>
        <p class="text-sm font-medium text-indigo-600">
            OGSIEC Staff Portal
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-900">
            EPM Dashboard
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Election and Party Monitoring nominations, review activity,
            and retained records.
        </p>
    </div>


    {{-- Statistics --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

        {{-- Pending --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Pending Review
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $pendingReview }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Currently assigned to EPM
            </p>

        </div>


        {{-- Processed --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Processed by EPM
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $processedByEpm }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Historical EPM actions
            </p>

        </div>


        {{-- Legal --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Forwarded to Legal
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $forwardedToLegal }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                EPM → Legal
            </p>

        </div>


        {{-- Completed --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Completed
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $completed }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                EPM-processed nominations approved
            </p>

        </div>


        {{-- Returned --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Returned
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $returned }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                EPM-processed nominations returned
            </p>

        </div>

    </div>


    {{-- Recent EPM activity --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h2 class="font-semibold text-slate-900">
                        Recent EPM Activity
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Recent actions performed by EPM.
                    </p>
                </div>

                <a
                    href="{{ route('staff.epm.records.index') }}"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                >
                    View all records
                </a>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Party
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Destination
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Date
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                    @forelse($recentActivity as $history)

                        @php
                            $nomination = $history->nomination;
                        @endphp

                        <tr>

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $nomination?->candidate?->full_name ?? 'Unknown candidate' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Nomination #{{ $nomination?->id }}
                                </div>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination?->position?->name ?? '—' }}
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination?->politicalParty?->name ?? '—' }}
                            </td>


                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {{ str_replace('_', ' ', ucfirst($history->action)) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $history->to_department
                                    ? ucfirst($history->to_department)
                                    : '—' }}
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $history->created_at?->format('d M Y H:i') ?? '—' }}
                            </td>


                            <td class="px-6 py-4 text-right">

                                @if($nomination)

                                    <a
                                        href="{{ route('staff.epm.nominations.show', $nomination) }}"
                                        class="inline-flex rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700"
                                    >
                                        View
                                    </a>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-12 text-center"
                            >

                                <p class="font-semibold text-slate-900">
                                    No EPM activity yet
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    EPM actions will appear here after nominations
                                    are processed.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
