@extends('layouts.staff')

@section('title', 'ICT - Incoming Nominations')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">
        ICT — Incoming Nomination Batches
    </h1>

    <p class="mt-1 text-sm text-slate-600">
        Review nomination batches submitted by political parties.
    </p>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Batch
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Political Party
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Election
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Candidates
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                        Submitted
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                        Action
                    </th>
                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

                @forelse($batches as $batch)

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $batch->batch_number }}
                        </td>

                        <td class="px-6 py-4 text-slate-700">
                            {{ $batch->politicalParty?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-slate-700">
                            {{ $batch->election?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-slate-700">
                            {{ $batch->candidate_count }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $batch->submitted_at?->format('d M Y H:i') ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-right">

                            <form
                                method="POST"
                                action="{{ route('staff.ict.receive', $batch) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                                >
                                    Receive at ICT
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-sm text-slate-500"
                        >
                            No nomination batches are currently waiting for ICT.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($batches->hasPages())

        <div class="border-t border-slate-200 px-6 py-4">
            {{ $batches->links() }}
        </div>

    @endif

</div>

@endsection
