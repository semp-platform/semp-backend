@extends('layouts.staff')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Legal Review
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Nominations forwarded by Election and Party Monitoring
            for legal review.
        </p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">
                Pending Legal Review
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
                Legal
            </p>
        </div>

    </div>

    {{-- Nominations --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Nominations Awaiting Legal Review
            </h2>
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
                                Electoral Area
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
                                    {{ $nomination->electoral_area ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                    @if ($nomination->received_at)
                                        {{ $nomination->received_at->format('d M Y, H:i') }}
                                    @else
                                        —
                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <a
                                        href="{{ route('staff.legal.nominations.show', $nomination) }}"
                                        class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                                    >
                                        Review
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
                    No nominations awaiting legal review
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    There are currently no nominations assigned to Legal.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
