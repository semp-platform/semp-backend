@extends('layouts.staff')

@section('title', 'Finance Payments')

@section('content')

<div class="space-y-8">

    <div>

        <p class="text-sm font-semibold text-emerald-600">
            Finance
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Batch Payments
        </h1>

        <p class="mt-2 text-slate-600">
            Review and confirm political party payments.
        </p>

    </div>

    <div class="overflow-hidden rounded-xl border bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Reference
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Receipt
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Party
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Batch
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Election
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase">
                        Amount
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase">
                        Status
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

            @forelse($payments as $payment)

                <tr>

                    <td class="px-6 py-4">
                        {{ $payment->payment_reference }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $payment->receipt_number ?? '—' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $payment->politicalParty->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $payment->batch->batch_number }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $payment->batch->election->name }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        ₦{{ number_format($payment->amount, 2) }}
                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($payment->status === \App\Models\Payment\BatchPayment::STATUS_PENDING)

                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                Pending
                            </span>

                        @elseif($payment->status === \App\Models\Payment\BatchPayment::STATUS_PAID)

                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                Paid
                            </span>

                        @elseif($payment->status === \App\Models\Payment\BatchPayment::STATUS_CONFIRMED)

                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                Confirmed
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ ucfirst($payment->status) }}
                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($payment->status === \App\Models\Payment\BatchPayment::STATUS_PAID)

                            <form
                                method="POST"
                                action="{{ route('finance.payments.confirm', $payment) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                                >
                                    Confirm
                                </button>

                            </form>

                        @elseif($payment->status === \App\Models\Payment\BatchPayment::STATUS_CONFIRMED)

                            <a
                                href="{{ route('finance.payments.receipt', $payment) }}"
                                class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700"
                            >
                                View Receipt
                            </a>

                        @else

                            <span class="text-slate-400">
                                —
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">

                        No payments found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
