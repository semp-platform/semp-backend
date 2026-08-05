@extends('layouts.party')

@section('title', 'Payment Summary')

@section('content')

<div class="space-y-8">

    <div>

        <a
            href="{{ route('party.payments.index') }}"
            class="text-sm font-medium text-emerald-600 hover:underline"
        >
            ← Back to Payments
        </a>

        <p class="mt-5 text-sm font-semibold text-emerald-600">
            Payment Summary
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            {{ $payment->payment_reference }}
        </h1>

        <p class="mt-2 text-slate-600">
            {{ $payment->batch->election->name }}
        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-4">

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase text-slate-500">
                Batch
            </p>

            <p class="mt-2 text-lg font-semibold">
                {{ $payment->batch->batch_number }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase text-slate-500">
                Status
            </p>

            <p class="mt-2">

                @if($payment->status === \App\Models\Payment\BatchPayment::STATUS_PENDING)

                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800">
                        Pending
                    </span>

                @elseif($payment->status === \App\Models\Payment\BatchPayment::STATUS_PAID)

                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">
                        Paid
                    </span>

                @elseif($payment->status === \App\Models\Payment\BatchPayment::STATUS_CONFIRMED)

                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">
                        Confirmed
                    </span>

                @endif

            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase text-slate-500">
                Candidates
            </p>

            <p class="mt-2 text-lg font-semibold">
                {{ $payment->batch->candidate_count }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-xs uppercase text-slate-500">
                Amount
            </p>

            <p class="mt-2 text-2xl font-bold text-emerald-700">
                ₦{{ number_format($payment->amount, 2) }}
            </p>

        </div>

    </div>

    <div class="overflow-hidden rounded-xl border bg-white">

        <div class="border-b px-6 py-4">

            <h2 class="text-lg font-semibold">
                Candidates Included
            </h2>

        </div>

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

            <tbody class="divide-y">

                @foreach($payment->batch->nominations as $nomination)

                    <tr>

                        <td class="px-6 py-4">
                            {{ $nomination->candidate->full_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $nomination->position->name }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            ₦{{ number_format($nomination->nomination_fee, 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="rounded-xl border bg-slate-50 p-6">

        <div class="flex items-center justify-between">

            <span class="font-semibold">
                Total Payable
            </span>

            <span class="text-2xl font-bold">
                ₦{{ number_format($payment->amount, 2) }}
            </span>

        </div>

    </div>

    <div class="flex justify-end gap-3">

        <a
            href="{{ route('party.payments.index') }}"
            class="rounded-lg border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100"
        >
            Back
        </a>

        @if($payment->status === \App\Models\Payment\BatchPayment::STATUS_PENDING)

            <form
                method="POST"
                action="{{ route('party.payments.pay', $payment) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700"
                >
                    Pay Now
                </button>

            </form>

        @elseif($payment->receipt_number)

            <a
    href="{{ route('party.payments.receipt', $payment) }}"
    class="rounded-lg border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100"
>
    View Receipt
</a>

<a
    href="{{ route('party.payments.receipt.download', $payment) }}"
    class="rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700"
>
    Download Receipt
</a>

        @endif

    </div>

</div>

@endsection
