@extends('layouts.party')

@section('title', 'Candidate Withdrawals | SEMP')

@section('content')

<div class="mb-8">

    <p class="text-sm font-medium text-emerald-700">
        Candidate Changes
    </p>

    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
        Candidate Withdrawals
    </h1>

    <p class="mt-2 text-sm text-slate-500">
        View candidate withdrawal requests submitted by
        {{ $party->name }}.
    </p>

</div>


<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Withdrawal Requests
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Candidates with an initiated or completed withdrawal request
            are shown below.
        </p>

    </div>


    @if ($withdrawals->isEmpty())

        <div class="px-6 py-12 text-center">

            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-slate-500"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

            </div>

            <h3 class="mt-4 text-sm font-semibold text-slate-950">
                No withdrawal requests
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                Candidates will appear here once a withdrawal request has
                been initiated.
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
                            Election
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Electoral Area
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Withdrawal Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200 bg-white">

                    @foreach ($withdrawals as $withdrawal)

                        @php
                            $status = $withdrawal->status;

                            $statusClasses = match ($status) {
                                'approved' => 'bg-emerald-100 text-emerald-800',
                                'submitted' => 'bg-amber-100 text-amber-800',
                                'draft' => 'bg-slate-100 text-slate-700',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp

                        <tr class="hover:bg-slate-50">

                            <td class="whitespace-nowrap px-6 py-4">

                                <p class="text-sm font-semibold text-slate-950">
                                    {{ $withdrawal->nomination?->candidate_name ?? 'Unknown candidate' }}
                                </p>

                            </td>


                            <td class="px-6 py-4">

                                <p class="text-sm text-slate-700">
                                    {{ $withdrawal->nomination?->election?->name ?? '—' }}
                                </p>

                            </td>


                            <td class="px-6 py-4">

                                <p class="text-sm text-slate-700">
                                    {{ $withdrawal->nomination?->position?->name ?? '—' }}
                                </p>

                            </td>


                            <td class="px-6 py-4">

                                <p class="text-sm text-slate-700">
                                    {{ $withdrawal->nomination?->electoral_area ?? '—' }}
                                </p>

                            </td>


                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ $status === 'approved' ? 'Withdrawn' : str_replace('_', ' ', ucfirst($status)) }}
                                </span>

                            </td>


                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <a
                                    href="{{ route('party.withdrawals.show', $withdrawal) }}"
                                    class="inline-flex items-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800"
                                >
                                    View Request
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection
