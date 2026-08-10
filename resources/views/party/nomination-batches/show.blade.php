@extends('layouts.party')

@section('title', 'Nomination Batch')

@section('content')

<div class="max-w-7xl mx-auto">

    <a
        href="{{ route('party.nomination-batches.index') }}"
        class="text-sm text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Nomination Batches
    </a>

    <div class="mt-4 flex items-center justify-between">

        <div>

            <p class="text-sm font-semibold text-emerald-700">
                Candidate Nominations
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                {{ $batch->batch_number }}
            </h1>

            <p class="mt-2 text-slate-600">
                {{ $batch->election->name }}
            </p>

        </div>

        <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
            {{ ucfirst($batch->status) }}
        </span>

    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-3">

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase tracking-wide text-slate-500">
                Candidates
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $batch->candidate_count }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase tracking-wide text-slate-500">
                Total Fee
            </p>

            <p class="mt-2 text-3xl font-bold text-emerald-700">
                ₦{{ number_format($batch->total_nomination_fee,2) }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase tracking-wide text-slate-500">
                Payment Status
            </p>

            <p class="mt-2 text-xl font-semibold">
                {{ ucfirst($batch->payment_status) }}
            </p>

        </div>

    </div>

    <div class="mt-8 overflow-hidden rounded-xl border bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Candidate
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Position
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase">
                        Fee
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

                @foreach($batch->nominations as $nomination)

                    <tr>

                        <td class="px-6 py-4">

                            {{ $nomination->candidate_name }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $nomination->position->name }}

                        </td>

                        <td class="px-6 py-4 text-right">

                            ₦{{ number_format($nomination->nomination_fee,2) }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="mt-8 flex justify-end">

    <div class="mt-8 flex justify-end">

    @if(!$batch->hasPayment())

        <div class="rounded-lg border border-amber-200 bg-amber-50 px-6 py-4 text-amber-700">

            No payment has been created for this batch.

        </div>

    @elseif($batch->canProceedToPayment())

        <a
            href="{{ route('party.payments.show', $batch->payment) }}"
            class="rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700"
        >
            Proceed to Payment
        </a>

    @elseif($batch->isAwaitingFinanceConfirmation())

        <div class="rounded-lg border border-blue-200 bg-blue-50 px-6 py-4 text-blue-700">

            <p class="font-semibold">
                Payment Received
            </p>

            <p class="mt-1 text-sm">
                Awaiting Finance confirmation.
            </p>

        </div>

    @elseif($batch->canSubmit())

        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700">

            <p class="font-semibold">
                Payment Confirmed
            </p>

            <p class="mt-1 text-sm">
                You may now submit this batch to OGSIEC.
            </p>
<form
    method="POST"
    action="{{ route('party.nomination-batches.submit', $batch) }}"
    class="mt-4"
>
    @csrf

    @if($batch->canSubmit())
    <form method="POST"
          action="{{ route('party.nomination-batches.submit', $batch) }}">
        @csrf

        <button type="submit">
            Submit to OGSIEC
        </button>
    </form>
@endif
</form>
        </div>

    @endif

</div>

</div>

</div>

@endsection
