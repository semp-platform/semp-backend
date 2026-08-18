
@extends('layouts.staff')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Commissioner — Nominations
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Nominations awaiting final decision following ICT, EPM and Legal
            screening and recommendations.
        </p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">
                Awaiting Decision
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $nominations->total() }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">
                Current Page
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $nominations->count() }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">
                Department
            </p>

            <p class="mt-2 text-lg font-bold text-slate-900">
                Commissioner
            </p>
        </div>

    </div>

    {{-- Nominations --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Nominations Awaiting Final Decision
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Review the screening history and recommendations before
                approving or returning a nomination.
            </p>
        </div>

        @if ($nominations->isNotEmpty())

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
                                Election
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Position
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Received
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach ($nominations as $nomination)

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="font-medium text-slate-900">
    {{ $nomination->candidate_name }}
</div>

@if(
    $nomination->workflowHistories
        ->where('action', 'resubmitted')
        ->isNotEmpty()
)

<span class="mt-1 inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">
    Resubmitted
</span>

@endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $nomination->politicalParty?->name ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $nomination->election_type ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $nomination->position?->name ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $nomination->received_at?->format('d M Y, H:i') ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <a
                                        href="{{ route('staff.commissioner.nominations.show', $nomination) }}"
                                        class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                                    >
                                        Review & Decide
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            @if ($nominations->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $nominations->links() }}
                </div>
            @endif

        @else

            <div class="px-6 py-16 text-center">

                <h3 class="text-lg font-semibold text-slate-900">
                    No nominations awaiting decision
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    There are currently no nominations assigned to the
                    Commissioner for final decision.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection

