@extends('layouts.staff')

@section('title', 'Commissioner Withdrawal Review | SEMP')

@section('content')

<div class="mb-8">

    <p class="text-sm font-medium text-emerald-700">
        Commissioner
    </p>

    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
        Candidate Withdrawal Review
    </h1>

    <p class="mt-2 text-sm text-slate-500">
        Review candidate withdrawal request submitted by political party.
    </p>

</div>


<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-900">
            Withdrawal Details
        </h2>

    </div>


    <div class="px-6 py-6">

        <dl class="space-y-6">


            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Candidate
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->candidate_name }}
                </dd>
            </div>


            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Political Party
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->politicalParty->name }}
                </dd>
            </div>


            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Election
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->election->name }}
                </dd>
            </div>


            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Position
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->nomination->position->name }}
                </dd>
            </div>


            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Withdrawal Reason
                </dt>

                <dd class="mt-2 text-sm font-medium text-slate-900">
                    {{ $withdrawal->candidateChangeReason?->name }}
                </dd>
            </div>


            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Remarks
                </dt>

                <dd class="mt-2 text-sm text-slate-700">
                    {{ $withdrawal->remarks }}
                </dd>
            </div>


            @if ($withdrawal->supporting_evidence_path)

            <div>

                <dt class="text-xs font-semibold uppercase text-slate-500">
                    Supporting Evidence
                </dt>

                <dd class="mt-2">

                    <a
                        href="{{ asset('storage/' . $withdrawal->supporting_evidence_path) }}"
                        target="_blank"
                        class="inline-flex rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-emerald-700"
                    >
                        View Supporting Evidence
                    </a>

                </dd>

            </div>

            @endif


        </dl>

    </div>

</div>

@if ($withdrawal->status === \App\Models\Candidate\CandidateWithdrawal::STATUS_SUBMITTED)

    <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-900">
                Commissioner Decision
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Approve or deny this candidate withdrawal request.
            </p>

        </div>

        <div class="px-6 py-6">

            <div class="grid gap-4 sm:grid-cols-2">

                {{-- Approve --}}
                @can('withdrawals.approve')
                    <form
                        method="POST"
                        action="{{ route('staff.commissioner.withdrawals.approve', $withdrawal) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                            onclick="return confirm('Approve this withdrawal request?')"
                        >
                            Approve Withdrawal
                        </button>
                    </form>
                @endcan


                {{-- Reject --}}
                @can('withdrawals.reject')
                    <form
                        method="POST"
                        action="{{ route('staff.commissioner.withdrawals.reject', $withdrawal) }}"
                    >
                        @csrf

                        <label
                            for="comment"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Reason for denial
                        </label>

                        <textarea
                            name="comment"
                            id="comment"
                            rows="3"
                            class="mb-3 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Enter reason for denying this request..."
                        ></textarea>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700"
                            onclick="return confirm('Deny this withdrawal request?')"
                        >
                            Deny Withdrawal
                        </button>
                    </form>
                @endcan

            </div>

        </div>

    </div>

@endif
@endsection
