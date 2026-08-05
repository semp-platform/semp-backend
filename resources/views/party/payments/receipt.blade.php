@extends('layouts.party')

@section('title', 'Payment Receipt')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm font-semibold text-emerald-600">
                Payments
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                Official Payment Receipt
            </h1>

            <p class="mt-2 text-slate-600">
                OGSIEC Nomination Payment Receipt
            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="{{ route('party.payments.index') }}"
                class="rounded-lg border px-5 py-2 font-medium hover:bg-slate-50"
            >
                Back
            </a>

            <a
                href="{{ route('party.payments.receipt.download', $payment) }}"
                class="rounded-lg bg-emerald-600 px-5 py-2 font-medium text-white hover:bg-emerald-700"
            >
                Download PDF
            </a>

        </div>

    </div>

    <div class="rounded-xl border bg-white shadow-sm">

        <div class="border-b px-8 py-6">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-2xl font-bold">
                        OGSIEC
                    </h2>

                    <p class="text-slate-500">
                        Ogun State Independent Electoral Commission
                    </p>

                </div>

                <div class="text-right">

                    <p class="text-sm text-slate-500">
                        Receipt Number
                    </p>

                    <p class="text-xl font-bold text-emerald-700">
                        {{ $payment->receipt_number }}
                    </p>

                </div>

            </div>

        </div>

        <div class="grid gap-8 px-8 py-8 md:grid-cols-2">

            <div>

                <p class="text-sm text-slate-500">
                    Political Party
                </p>

                <p class="mt-1 font-semibold">
                    {{ $payment->politicalParty->name }}
                </p>

            </div>

            <div>

                <p class="text-sm text-slate-500">
                    Election
                </p>

                <p class="mt-1 font-semibold">
                    {{ $payment->batch->election->name }}
                </p>

            </div>

            <div>

                <p class="text-sm text-slate-500">
                    Batch Number
                </p>

                <p class="mt-1 font-semibold">
                    {{ $payment->batch->batch_number }}
                </p>

            </div>

            <div>

                <p class="text-sm text-slate-500">
                    Payment Reference
                </p>

                <p class="mt-1 font-semibold">
                    {{ $payment->payment_reference }}
                </p>

            </div>

<div>

    <p class="text-sm text-slate-500">
        Payment Date
    </p>

    <p class="mt-1 font-semibold">
        {{ optional($payment->paid_at)->format('d M Y H:i') }}
    </p>

</div>

<div>

    <p class="text-sm text-slate-500">
        Payment Gateway
    </p>

    <p class="mt-1 font-semibold">
        {{ ucfirst($payment->gateway) }}
    </p>

</div>
        </div>

        <div class="border-t px-8 py-6">

            <table class="min-w-full divide-y divide-slate-200">

                <thead>

                    <tr>

                        <th class="py-3 text-left text-xs font-semibold uppercase">
                            Candidate
                        </th>

                        <th class="py-3 text-left text-xs font-semibold uppercase">
                            Position
                        </th>

                        <th class="py-3 text-right text-xs font-semibold uppercase">
                            Fee
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200">

                @foreach($payment->batch->nominations as $nomination)

                    <tr>

                        <td class="py-4">
                            {{ $nomination->candidate_name }}
                        </td>

                        <td class="py-4">
                            {{ $nomination->position->name }}
                        </td>

                        <td class="py-4 text-right">
                            ₦{{ number_format($nomination->nomination_fee,2) }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

                <tfoot>

                    <tr>

                        <td colspan="2" class="pt-6 text-right font-bold">
                            Total Paid
                        </td>

                        <td class="pt-6 text-right text-xl font-bold text-emerald-700">
                            ₦{{ number_format($payment->amount,2) }}
                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>

@endsection
