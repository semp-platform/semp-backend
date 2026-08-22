@extends('layouts.staff')

@section('title', 'Legal & Compliance — Workflow History')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Workflow History & Activity
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Read-only audit trail of nomination workflow activity.
        </p>
    </div>

    <form method="GET" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

        <div class="grid gap-4 md:grid-cols-3">

            <div>
                <label class="text-sm font-medium text-slate-700">
                    Candidate
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Candidate name"
                    class="mt-1 w-full rounded-lg border-slate-300"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">
                    Action
                </label>

                <input
                    type="text"
                    name="action"
                    value="{{ request('action') }}"
                    placeholder="e.g. approved, returned"
                    class="mt-1 w-full rounded-lg border-slate-300"
                >
            </div>

            <div class="flex items-end">
                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white"
                >
                    Filter
                </button>
            </div>

        </div>

    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Action
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            From
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            To
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Officer
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Details
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse ($histories as $history)

                        <tr class="align-top hover:bg-slate-50">

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $history->created_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $history->nomination?->candidate?->full_name
                                        ?? $history->nomination?->candidate_name
                                        ?? '—' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $history->nomination?->politicalParty?->name ?? '—' }}
                                </div>

                            </td>

                            <td class="px-6 py-4">

                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ ucfirst(str_replace('_', ' ', $history->action)) }}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $history->from_department ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $history->to_department ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $history->user?->name ?? 'System / Unknown' }}
                            </td>

                            <td class="max-w-sm px-6 py-4 text-sm text-slate-600">

                                @if ($history->reason)
                                    <div>
                                        <span class="font-medium text-slate-700">
                                            Reason:
                                        </span>

                                        {{ $history->reason }}
                                    </div>
                                @endif

                                @if ($history->comment)
                                    <div class="mt-1">
                                        <span class="font-medium text-slate-700">
                                            Comment:
                                        </span>

                                        {{ $history->comment }}
                                    </div>
                                @endif

                                @if (! $history->reason && ! $history->comment)
                                    —
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                No workflow history found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($histories->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $histories->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
