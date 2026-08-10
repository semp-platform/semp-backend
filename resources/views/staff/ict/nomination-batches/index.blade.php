@extends('layouts.staff')

@section('title', 'ICT Nomination Batches')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">
        ICT Nomination Batches
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Nomination batches submitted by political parties and currently
        awaiting or undergoing ICT review.
    </p>
</div>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">

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
                    Submitted By
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                    Date Submitted
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                    Received by ICT
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                    Status
                </th>

                <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                    Action
                </th>

            </tr>

        </thead>

        <tbody class="divide-y divide-slate-200">

            @forelse($batches as $batch)

                <tr class="hover:bg-slate-50">

                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">
                            {{ $batch->batch_number }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $batch->politicalParty?->name ?? '—' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $batch->election?->name ?? '—' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $batch->candidate_count ?? $batch->nominations->count() }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $batch->submittedBy?->name ?? '—' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $batch->submitted_at?->format('d M Y H:i') ?? '—' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $batch->received_at?->format('d M Y H:i') ?? 'Not yet received' }}
                    </td>

                    <td class="px-6 py-4">

                        @if($batch->status === \App\Models\Nomination\NominationBatch::STATUS_SUBMITTED)

                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                                Submitted
                            </span>

                        @elseif($batch->status === \App\Models\Nomination\NominationBatch::STATUS_UNDER_REVIEW)

                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                Under Review
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                {{ ucfirst(str_replace('_', ' ', $batch->status)) }}
                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4 text-right">

                        <a
                            href="{{ route('staff.ict.nomination-batches.show', $batch) }}"
                            class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                        >
                            Review
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="9"
                        class="px-6 py-12 text-center text-sm text-slate-500"
                    >
                        No nomination batches are currently awaiting or undergoing ICT review.
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

@endsection
