@extends('layouts.party')

@section('title', 'Withdraw Candidate | SEMP')

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- Back --}}
    <div class="mb-6">
        <a
            href="{{ route('party.nominations.show', $nomination) }}"
            class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
        >
            &larr; Back to Candidate
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">
            Candidate Withdrawal Request
        </h1>

        <p class="mt-2 text-sm text-slate-600">
            Submit a request to withdraw this candidate from the election.
        </p>
    </div>

    {{-- Candidate Information --}}
    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-sm font-semibold uppercase tracking-wide text-slate-500">
            Candidate & Election Information
        </h2>

        <div class="grid gap-5 md:grid-cols-2">

            {{-- Candidate --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Candidate
                </p>

                <p class="mt-1 text-base font-semibold text-slate-900">
                    {{ $nomination->candidate_name }}
                </p>
            </div>

            {{-- Election --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Election
                </p>

                <p class="mt-1 text-base font-semibold text-slate-900">
                    {{ $nomination->election?->name ?? '—' }}
                </p>
            </div>

            {{-- Position --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Position
                </p>

                <p class="mt-1 text-base font-semibold text-slate-900">
                    {{ $nomination->position?->name ?? '—' }}
                </p>
            </div>

            {{-- Electoral Area --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Electoral Area
                </p>

                <p class="mt-1 text-base font-semibold text-slate-900">
                    {{ $nomination->electoral_area }}
                </p>
            </div>

            {{-- Current Status --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Current Status
                </p>

                <p class="mt-1 text-base font-semibold capitalize text-slate-900">
                    {{ str_replace('_', ' ', $nomination->status) }}
                </p>
            </div>

            {{-- Current Department --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Current OGSIEC Department
                </p>

                <p class="mt-1 text-base font-semibold uppercase text-slate-900">
                    {{ $nomination->current_department
                        ? $nomination->current_department
                        : 'Party' }}
                </p>
            </div>

        </div>

    </div>

    {{-- Warning --}}
@if($nomination->current_department)
        <div class="mb-8 rounded-xl border border-amber-200 bg-amber-50 p-5">

            <div class="flex gap-3">

                <div class="mt-0.5 text-amber-600">
                    !
                </div>

                <div>

                    <h3 class="font-semibold text-amber-900">
                        OGSIEC withdrawal review required
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-amber-800">
                        This candidate has entered the OGSIEC processing workflow.
                        The withdrawal request will be sent to the Commissioner
                        for review. The existing nomination will not be removed
                        while the request is pending.
                    </p>

                </div>

            </div>

        </div>

    @else

        <div class="mb-8 rounded-xl border border-blue-200 bg-blue-50 p-5">

            <h3 class="font-semibold text-blue-900">
                Party-level withdrawal
            </h3>

            <p class="mt-1 text-sm leading-6 text-blue-800">
                This candidate has not yet entered OGSIEC processing.
                The withdrawal can be handled within the political party
                workflow.
            </p>

        </div>

    @endif

    {{-- Withdrawal Form --}}
    <form
        method="POST"
        action="{{ route('party.withdrawals.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf

        <input
            type="hidden"
            name="nomination_id"
            value="{{ $nomination->id }}"
        >

        {{-- Reason --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <label
                for="candidate_change_reason_id"
                class="mb-2 block text-sm font-semibold text-slate-900"
            >
                Reason for Withdrawal
            </label>

            <select
                id="candidate_change_reason_id"
                name="candidate_change_reason_id"
                required
                class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
            >

                <option value="">
                    Select withdrawal reason
                </option>

                @foreach($reasons as $reason)

                    <option
                        value="{{ $reason->id }}"
                        @selected(old('candidate_change_reason_id') == $reason->id)
                    >
                        {{ $reason->name }}
                    </option>

                @endforeach

            </select>

            @error('candidate_change_reason_id')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Remarks --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <label
                for="remarks"
                class="mb-2 block text-sm font-semibold text-slate-900"
            >
                Explanation / Remarks
            </label>

            <textarea
                id="remarks"
                name="remarks"
                rows="6"
                required
                minlength="10"
                maxlength="5000"
                class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="Explain why this candidate is being withdrawn..."
            >{{ old('remarks') }}</textarea>

            <p class="mt-2 text-xs text-slate-500">
                Provide sufficient details to support the withdrawal request.
            </p>

            @error('remarks')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Supporting Evidence --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <label
                for="supporting_evidence"
                class="mb-2 block text-sm font-semibold text-slate-900"
            >
                Supporting Evidence
            </label>

            <input
                id="supporting_evidence"
                type="file"
                name="supporting_evidence"
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700"
            >

            <p class="mt-2 text-xs text-slate-500">
                Examples may include a candidate's withdrawal letter,
                death certificate, court order, or other relevant evidence.
                Maximum file size: 10 MB.
            </p>

            @error('supporting_evidence')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">

            <a
                href="{{ route('party.nominations.show', $nomination) }}"
                class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
            >
                Submit Withdrawal Request
            </button>

        </div>

    </form>

</div>

@endsection
