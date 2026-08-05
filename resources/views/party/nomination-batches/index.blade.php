@extends('layouts.party')

@section('title', 'Nomination Batches | SEMP')

@section('content')

<div class="mb-8 flex items-end justify-between">

    <div>

        <p class="text-sm font-medium text-emerald-700">
            Candidate Nominations
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Nomination Batches
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Review, pay for and submit nomination batches to OGSIEC.
        </p>

    </div>

    <a
        href="{{ route('party.nomination-batches.create') }}"
        class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
    >
        New Batch
    </a>

</div>


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Batch No.
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Election
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Candidates
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Total Fee
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Payment
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-3"></th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">

                @forelse($batches as $batch)

                    <tr>

                        <td class="px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $batch->batch_number }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $batch->election->name }}
                        </td>

                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            {{ $batch->candidate_count }}
                        </td>

                        <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">
                            ₦{{ number_format($batch->total_nomination_fee, 2) }}
                        </td>

                        <td class="px-6 py-4 text-center">

                            @if($batch->payment_status === 'paid')

                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                    Paid
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                    Pending
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center">

                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ ucfirst(str_replace('_', ' ', $batch->status)) }}
                            </span>

                        </td>

                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route('party.nomination-batches.show', $batch) }}"
                                class="font-medium text-emerald-700 hover:text-emerald-900"
                            >
                                View
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="px-6 py-12 text-center">

                            <p class="text-sm font-medium text-slate-700">
                                No nomination batches have been created.
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Create your first batch to prepare nominations for submission to OGSIEC.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@if($batches->hasPages())

    <div class="mt-6">
        {{ $batches->links() }}
    </div>

@endif

@endsection
