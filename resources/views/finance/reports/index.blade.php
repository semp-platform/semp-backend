@extends('layouts.staff')

@section('title', 'Finance Reports')

@section('content')

<div class="space-y-8">

    <div>

        <p class="text-sm font-semibold text-emerald-600">
            Finance
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Reports
        </h1>

        <p class="mt-2 text-slate-600">
            Confirmed payment summary.
        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-2">

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Today's Revenue
            </p>

            <p class="mt-2 text-3xl font-bold text-emerald-700">
                ₦{{ number_format($todayRevenue,2) }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Total Revenue
            </p>

            <p class="mt-2 text-3xl font-bold text-emerald-700">
                ₦{{ number_format($totalRevenue,2) }}
            </p>

        </div>

    </div>

    <div class="overflow-hidden rounded-xl border bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Receipt
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Political Party
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Election
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase">
                        Amount
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase">
                        Confirmed On
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

                @forelse($payments as $payment)

                    <tr>

                        <td class="px-6 py-4">

                            {{ $payment->receipt_number }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $payment->politicalParty->name }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $payment->batch->election->name }}

                        </td>

                        <td class="px-6 py-4 text-right">

                            ₦{{ number_format($payment->amount,2) }}

                        </td>

                        <td class="px-6 py-4 text-center">

                            {{ optional($payment->confirmed_at)->format('d M Y') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">

                            No confirmed payments found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
