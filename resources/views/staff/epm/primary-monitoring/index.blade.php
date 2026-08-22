@extends('layouts.staff')

@section('title', 'Primary Monitoring')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-indigo-600">
                EPM
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-900">
                Primary Monitoring
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Schedule, monitor and retain records of political party primaries.
            </p>
        </div>

        <a
            href="{{ route('staff.epm.primary-monitoring.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
        >
            Schedule Primary
        </a>

    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <form method="GET" class="grid gap-4 md:grid-cols-4">

            <div class="md:col-span-2">

                <label class="block text-sm font-medium text-slate-700">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Party, election or venue..."
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >

            </div>

            <div>

                <label class="block text-sm font-medium text-slate-700">
                    Type
                </label>

                <select
                    name="primary_type"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >
                    <option value="">All types</option>

                    @foreach([
                        'direct' => 'Direct',
                        'indirect' => 'Indirect',
                        'consensus' => 'Consensus',
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            @selected($primaryType === $value)
                        >
                            {{ $label }}
                        </option>

                    @endforeach
                </select>

            </div>

            <div>

                <label class="block text-sm font-medium text-slate-700">
                    Status
                </label>

                <select
                    name="status"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >
                    <option value="">All statuses</option>

                    @foreach([
                        'scheduled' => 'Scheduled',
                        'monitor_assigned' => 'Monitor Assigned',
                        'monitoring' => 'Monitoring',
                        'report_pending' => 'Report Pending',
                        'completed' => 'Completed',
                        'flagged' => 'Flagged',
                        'closed' => 'Closed',
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            @selected($status === $value)
                        >
                            {{ $label }}
                        </option>

                    @endforeach
                </select>

            </div>

            <div class="md:col-span-4 flex justify-end gap-2">

                <a
                    href="{{ route('staff.epm.primary-monitoring.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Clear
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                >
                    Apply Filters
                </button>

            </div>

        </form>

    </div>


    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-900">
                Primary Events
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Upcoming and historical political party primary events.
            </p>
        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Date
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Political Party
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Election / Position
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Type
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                    @forelse($events as $event)

                        <tr>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $event->scheduled_date?->format('d M Y') }}

                                @if($event->scheduled_time)
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $event->scheduled_time }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $event->politicalParty?->name ?? '—' }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $event->politicalParty?->acronym }}
                                </div>

                            </td>

                            <td class="px-6 py-4">

                                <div class="text-sm font-medium text-slate-900">
                                    {{ $event->election?->name ?? '—' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $event->position?->name ?? '—' }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ ucfirst($event->primary_type) }}
                            </td>

                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ str_replace('_', ' ', ucfirst($event->status)) }}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('staff.epm.primary-monitoring.show', $event) }}"
                                    class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-sm text-slate-500"
                            >
                                No primary monitoring events found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($events->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $events->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
