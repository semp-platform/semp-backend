@extends('layouts.party')

@section('title', 'Withdraw Candidate | SEMP')

@section('content')

<div class="mb-8">

    <a
        href="{{ route('party.nominations.show', $nomination) }}"
        class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Nomination
    </a>

    <div class="mt-5">

        <p class="text-sm font-medium text-emerald-700">
            Candidate Withdrawal
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Withdraw Candidate
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Submit a withdrawal request for the nominated candidate.
        </p>

    </div>

</div>


@if ($errors->any())

    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">

        <ul class="list-disc pl-5 text-sm text-red-700">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <form
    method="POST"
    action="{{ route('party.withdrawals.store') }}"
    enctype="multipart/form-data"
>
        @csrf

        <input
            type="hidden"
            name="nomination_id"
            value="{{ $nomination->id }}"
        >

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Withdrawal Request
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Please provide the reason for withdrawing this candidate.
            </p>

        </div>


        <div class="space-y-6 px-6 py-6">

            <div>

                <label
                    class="block text-sm font-medium text-slate-700"
                >
                    Candidate
                </label>

                <p class="mt-2 text-sm text-slate-900">
                    {{ $nomination->candidate_name }}
                </p>

            </div>


            <div>

    <label
        for="candidate_change_reason_id"
        class="block text-sm font-medium text-slate-700"
    >
        Withdrawal Reason
    </label>

    <select
        id="candidate_change_reason_id"
        name="candidate_change_reason_id"
        required
        class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
    >

        <option value="">
            -- Select Withdrawal Reason --
        </option>

        @foreach ($reasons as $reason)

            <option
                value="{{ $reason->id }}"
                data-requires-document="{{ $reason->requires_document ? 1 : 0 }}"
                {{ old('candidate_change_reason_id') == $reason->id ? 'selected' : '' }}
            >
                {{ $reason->name }}
            </option>

        @endforeach

    </select>

</div>
<div>

    <label
        for="supporting_evidence"
        class="block text-sm font-medium text-slate-700"
    >
        Supporting Evidence
    </label>

    <input
        type="file"
        id="supporting_evidence"
        name="supporting_evidence"
        class="mt-2 block w-full rounded-lg border border-slate-300 px-3 py-2"
    >

    <p
        id="supporting-evidence-note"
        class="mt-2 text-xs text-slate-500"
    >
        Optional unless the selected withdrawal reason requires evidence.
    </p>

</div>
<div>

    <label
        for="remarks"
        class="block text-sm font-medium text-slate-700"
    >
        Additional Remarks
    </label>

    <textarea
        id="remarks"
        name="remarks"
        rows="6"
        class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
    >{{ old('remarks') }}</textarea>

</div>

        </div>


        <div class="flex justify-between border-t border-slate-200 px-6 py-4">

            <a
                href="{{ route('party.nominations.show', $nomination) }}"
                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700"
            >
                Save Withdrawal Request
            </button>

        </div>

    </form>

</div>

@endsection
