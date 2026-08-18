@extends('layouts.party')

@section('title', 'Payments')

@section('content')

<div class="space-y-8">

    <div>

        <p class="text-sm font-semibold text-emerald-600">
            Payments
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Payment Dashboard
        </h1>

        <p class="mt-2 text-slate-600">
            View and manage all nomination batch payments.
        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-4">

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Total Payments
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $payments->count() }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Pending
            </p>

            <p class="mt-2 text-3xl font-bold text-amber-600">
                {{ $payments->where('status', 'pending')->count() }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Paid
            </p>

            <p class="mt-2 text-3xl font-bold text-emerald-600">
                {{ $payments->where('status', 'paid')->count() }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Total Value
            </p>

            <p class="mt-2 text-2xl font-bold">
                ₦{{ number_format($payments->sum('amount'), 2) }}
            </p>

        </div>

    </div>

    <div class="overflow-hidden rounded-xl border bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Reference
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

            <tbody class="divide-y">

                @forelse($payments as $payment)

                    <tr>

                        <td class="px-6 py-4">
                            {{ $payment->payment_reference }}
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

                            @if ($payment->status === \App\Models\Payment\BatchPayment::STATUS_PENDING)

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                    Pending
                                </span>

                            @elseif ($payment->status === \App\Models\Payment\BatchPayment::STATUS_PAID)

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                    Paid
                                </span>

                            @elseif ($payment->status === \App\Models\Payment\BatchPayment::STATUS_FAILED)

                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                    Failed
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ ucfirst($payment->status) }}
                                </span>

                            @endif

                        </td>

                  <td class="px-6 py-4 text-center">

    @if ($payment->status === \App\Models\Payment\BatchPayment::STATUS_PENDING)

        <a
            href="{{ route('party.payments.show', $payment) }}"
            class="inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
        >
            Review & Pay
        </a>

    @elseif ($payment->status === \App\Models\Payment\BatchPayment::STATUS_PAID)

        <a
            href="{{ route('party.payments.show', $payment) }}"
            class="inline-flex rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100"
        >
            Awaiting Confirmation
        </a>

    @elseif ($payment->status === \App\Models\Payment\BatchPayment::STATUS_CONFIRMED)

    <a
        href="{{ route('party.payments.show', $payment) }}"
        class="inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
    >
        View Payment
    </a>

    @elseif (
        $payment->status === \App\Models\Payment\BatchPayment::STATUS_FAILED
        || $payment->status === \App\Models\Payment\BatchPayment::STATUS_CANCELLED
    )

        <a
            href="{{ route('party.payments.show', $payment) }}"
            class="inline-flex rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700"
        >
            Retry Payment
        </a>

    @else

        <a
            href="{{ route('party.payments.show', $payment) }}"
            class="inline-flex rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            View Payment
        </a>

    @endif

</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">

                            No payments found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
