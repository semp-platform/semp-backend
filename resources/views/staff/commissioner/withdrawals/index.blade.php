@extends('layouts.staff')

@section('title', 'Commissioner — Candidate Withdrawals')

@section('content')

<div class="mb-8">

    <div>
        <p class="text-sm font-medium text-emerald-700">
            Commissioner
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Candidate Withdrawals
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Review candidate withdrawal requests submitted by political parties.
        </p>
    </div>

</div>

@if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    @if ($withdrawals->isEmpty())

        <div class="px-6 py-16 text-center">

            <h2 class="text-lg font-semibold text-slate-900">
                No withdrawal requests
            </h2>

            <p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">
                There are currently no candidate withdrawal requests awaiting Commissioner review.
            </p>

        </div>

    @else

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Political Party
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Election
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Reason
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Submitted
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($withdrawals as $withdrawal)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $withdrawal->nomination->candidate_name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Candidate ID:
                                    {{ $withdrawal->nomination->candidate->id }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $withdrawal->nomination->politicalParty->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $withdrawal->nomination->election->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $withdrawal->nomination->position->name }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="text-sm font-medium text-slate-900">
                                    {{ $withdrawal->candidateChangeReason?->name ?? '—' }}
                                </div>

                                @if ($withdrawal->remarks)
                                    <div class="mt-1 max-w-xs truncate text-xs text-slate-500">
                                        {{ $withdrawal->remarks }}
                                    </div>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ optional($withdrawal->submitted_at)->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('staff.commissioner.withdrawals.show', $withdrawal) }}"
                                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @if ($withdrawals->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $withdrawals->links() }}
            </div>
        @endif

    @endif

</div>

@endsection
