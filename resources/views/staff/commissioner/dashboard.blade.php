@extends('layouts.staff')

@section('title', 'Commissioner Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Commissioner Dashboard
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Overview of Commissioner decisions, work queues and approved records.
        </p>
    </div>


    {{-- Work Queue --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Nominations --}}
        <a
            href="{{ route('staff.commissioner.nominations.index') }}"
            class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:ring-slate-300"
        >
            <p class="text-sm font-medium text-slate-500">
                Nominations Awaiting Decision
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $awaitingNominations }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Awaiting Commissioner review
            </p>
        </a>


        {{-- Withdrawals --}}
        <a
            href="{{ route('staff.commissioner.withdrawals.index') }}"
            class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:ring-slate-300"
        >
            <p class="text-sm font-medium text-slate-500">
                Withdrawals & Substitutions
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $awaitingWithdrawals }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Awaiting Commissioner decision
            </p>
        </a>


        {{-- Decisions --}}
        <a
            href="{{ route('staff.commissioner.decisions.index') }}"
            class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:ring-slate-300"
        >
            <p class="text-sm font-medium text-slate-500">
                Completed Decisions
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $completedDecisions }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Approved or returned
            </p>
        </a>


        {{-- Approved Candidates --}}
        <a
            href="{{ route('staff.commissioner.approved-candidates.index') }}"
            class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:ring-slate-300"
        >
            <p class="text-sm font-medium text-slate-500">
                Approved Candidates
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $approvedCandidates }}
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Candidates approved
            </p>
        </a>

    </div>


    {{-- Recent Decisions --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex items-center justify-between gap-4">

                <div>
                    <h2 class="font-semibold text-slate-900">
                        Recent Commissioner Decisions
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        The five most recent nomination decisions.
                    </p>
                </div>

                <a
                    href="{{ route('staff.commissioner.decisions.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
                >
                    View all
                </a>

            </div>
        </div>


        @if ($recentDecisions->isNotEmpty())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Candidate
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Party
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Position
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Decision
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach ($recentDecisions as $decision)

                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">
                                        {{ $decision->nomination->candidate_name ?? 'Candidate' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $decision->nomination->politicalParty?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $decision->nomination->position?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($decision->action === \App\Services\Workflow\NominationWorkflowService::ACTION_APPROVED)

                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Approved
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            Returned
                                        </span>

                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $decision->created_at?->format('d M Y, H:i') ?? '—' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <h3 class="text-sm font-semibold text-slate-900">
                    No Commissioner decisions yet
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Completed nomination decisions will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
