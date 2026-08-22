@extends('layouts.staff')

@section('title', 'Monitoring Reports')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Primary Monitoring Reports
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Submitted monitoring reports from primary events.
        </p>
    </div>

    {{-- Reports --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        @if($reports->isEmpty())

            <div class="p-8 text-center">

                <p class="font-medium text-slate-700">
                    No monitoring reports submitted yet.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Submitted primary event monitoring reports will appear here.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Party
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Primary Event
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Venue
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Monitor
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

                        @foreach($reports as $report)

                            @php
                                $event = $report->primaryEvent;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                {{-- Party --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $event?->politicalParty?->name ?? '—' }}
                                    </div>

                                </td>

                                {{-- Event --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $event?->position?->name ?? 'Primary Event' }}
                                    </div>

                                    @if($event?->election)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $event->election->name }}
                                        </div>
                                    @endif

                                </td>

                                {{-- Date --}}
<td class="px-6 py-4 text-sm text-slate-700">

    @if($event?->scheduled_date)
        {{ \Carbon\Carbon::parse($event->scheduled_date)->format('d M Y') }}
    @else
        —
    @endif

</td>

                                {{-- Venue --}}
                                <td class="px-6 py-4">

                                    <div class="text-sm text-slate-700">
                                        {{ $event?->venue ?? '—' }}
                                    </div>

                                    @if($event?->lga)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $event->lga->name }}
                                        </div>
                                    @endif

                                </td>

                                {{-- Monitor --}}
                                <td class="px-6 py-4 text-sm text-slate-700">

                                    {{ $report->monitor?->name ?? '—' }}

                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Submitted
                                    </span>

                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route(
                                            'staff.epm.primary-monitoring.reports.show',
                                            $report
                                        ) }}"
                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                                    >
                                        View Report
                                    </a>

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
