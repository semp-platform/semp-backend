@extends('layouts.staff')

@section('title', 'Finance Dashboard')

@section('content')

<div class="space-y-8">

    <div>

        <p class="text-sm font-semibold text-emerald-600">
            Finance Department
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Dashboard
        </h1>

        <p class="mt-2 text-slate-600">
            Monitor and confirm political party payments.
        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-5">

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Total Payments
            </p>

            <p class="mt-3 text-3xl font-bold">
                {{ $totalPayments }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Pending
            </p>

            <p class="mt-3 text-3xl font-bold text-amber-600">
                {{ $pendingPayments }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Paid
            </p>

            <p class="mt-3 text-3xl font-bold text-blue-600">
                {{ $paidPayments }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Confirmed
            </p>

            <p class="mt-3 text-3xl font-bold text-emerald-600">
                {{ $confirmedPayments }}
            </p>

        </div>

        <div class="rounded-xl border bg-white p-6">

            <p class="text-sm text-slate-500">
                Revenue
            </p>

            <p class="mt-3 text-2xl font-bold">
                ₦{{ number_format($totalRevenue, 2) }}
            </p>

        </div>

    </div>

    <div class="rounded-xl border bg-white p-6">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-lg font-semibold">
                    Payment Verification
                </h2>

                @if($pendingPayments > 0)

                    <p class="text-slate-500">
                        There {{ $pendingPayments == 1 ? 'is' : 'are' }}
                        <strong>{{ $pendingPayments }}</strong>
                        payment{{ $pendingPayments == 1 ? '' : 's' }}
                        awaiting finance confirmation.
                    </p>

                @else

                    <p class="text-emerald-600 font-medium">
                        ✓ All payments have been reviewed and confirmed.
                    </p>

                @endif

            </div>

            @if($pendingPayments > 0)

                <a
                    href="{{ route('finance.payments.index') }}"
                    class="rounded-lg bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700"
                >
                    Open Payments
                </a>

            @endif

        </div>

    </div>

</div>

@endsection
