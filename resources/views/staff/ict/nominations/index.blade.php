@extends('layouts.staff')

@section('title', 'ICT Nominations')

@section('content')

<div class="mb-8">

    <h1 class="text-2xl font-bold text-slate-900">
        ICT Nominations
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Candidates currently undergoing ICT vetting.
    </p>

</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Candidate
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Party
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Position
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Received
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

                @forelse($nominations as $nomination)

                    <tr>

                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $nomination->candidate_name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $nomination->politicalParty?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $nomination->position?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ optional($nomination->received_at)->format('d M Y H:i') }}
                        </td>

                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route('staff.ict.nominations.show', $nomination) }}"
                                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white"
                            >
                                Review
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-sm text-slate-500"
                        >
                            No nominations are currently awaiting ICT review.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($nominations->hasPages())

        <div class="border-t border-slate-200 px-6 py-4">
            {{ $nominations->links() }}
        </div>

    @endif

</div>

@endsection
