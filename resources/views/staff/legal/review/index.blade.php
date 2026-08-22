@extends('layouts.staff')

@section('title', 'Legal Review')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Legal Review
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Historical Legal review activity and recommendations.
            This page is read-only.
        </p>
    </div>

    {{-- Filters --}}
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

        <form method="GET" class="grid gap-4 md:grid-cols-5">

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700">
                    Candidate
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search candidate..."
                    class="mt-1 w-full rounded-lg border-slate-300 text-sm"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">
                    Action
                </label>

                <select
                    name="action"
                    class="mt-1 w-full rounded-lg border-slate-300 text-sm"
                >
                    <option value="">All actions</option>

                    <option
                        value="forwarded"
                        @selected(request('action') === 'forwarded')
                    >
                        Forwarded
                    </option>

                    <option
                        value="returned"
                        @selected(request('action') === 'returned')
                    >
                        Returned
                    </option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">
                    From
                </label>

                <input
                    type="date"
                    name="date_from"
                    value="{{ request('date_from') }}"
                    class="mt-1 w-full rounded-lg border-slate-300 text-sm"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">
                    To
                </label>

                <input
                    type="date"
                    name="date_to"
                    value="{{ request('date_to') }}"
                    class="mt-1 w-full rounded-lg border-slate-300 text-sm"
                >
            </div>

            <div class="md:col-span-5 flex gap-2">

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                >
                    Apply Filters
                </button>

                <a
                    href="{{ route('staff.legal.review.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Clear
                </a>

            </div>

        </form>

    </div>

    {{-- Review History --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-900">
                Legal Review History
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Completed Legal actions recorded in the nomination workflow.
            </p>
        </div>

        @if ($reviews->isNotEmpty())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Candidate
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Party
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Position
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Action
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Destination
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Officer
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @foreach ($reviews as $review)

                            @php
                                $nomination = $review->nomination;
                            @endphp

                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $nomination?->candidate?->full_name
                                            ?? $nomination?->candidate_name
                                            ?? 'Candidate' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        Nomination #{{ $nomination?->id ?? '—' }}
                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $nomination?->politicalParty?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $nomination?->position?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($review->action === 'returned')

                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            Returned
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            {{ ucfirst($review->action) }}
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $review->to_department ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $review->user?->name ?? 'System / Former User' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                                    {{ $review->created_at?->format('d M Y, H:i') ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-right">

                                    @if ($nomination)

                                        <a
                                            href="{{ route(
                                                'staff.legal.nominations.show',
                                                $nomination
                                            ) }}"
                                            class="inline-flex rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                                        >
                                            View
                                        </a>

                                    @endif

                                </td>

                            </tr>

                            @if ($review->reason || $review->comment)

                                <tr class="bg-slate-50">

                                    <td colspan="8" class="px-6 py-4">

                                        <div class="grid gap-4 md:grid-cols-2">

                                            @if ($review->reason)

                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                        Reason
                                                    </p>

                                                    <p class="mt-1 text-sm text-slate-700">
                                                        {{ $review->reason }}
                                                    </p>
                                                </div>

                                            @endif

                                            @if ($review->comment)

                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                        Comment
                                                    </p>

                                                    <p class="mt-1 text-sm text-slate-700">
                                                        {{ $review->comment }}
                                                    </p>
                                                </div>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $reviews->links() }}
            </div>

        @else

            <div class="px-6 py-16 text-center">

                <h3 class="text-lg font-semibold text-slate-900">
                    No Legal review records found
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Completed Legal workflow activity will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
