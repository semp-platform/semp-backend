@'
@extends('layouts.party')

@section('title', 'Replace Candidate | SEMP')

@section('content')

<div class="mx-auto max-w-5xl">

    <div class="mb-8">

        <a
            href="{{ route('party.replacements.index') }}"
            class="text-sm font-medium text-emerald-700 hover:text-emerald-800"
        >
            ← Back to Candidate Replacements
        </a>

        <div class="mt-4">

            <p class="text-sm font-medium text-emerald-700">
                Candidate Changes
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                Replace Candidate
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Register a replacement candidate for {{ $party->name }}.
                The replacement will inherit the original candidate's
                approved nomination payment.
            </p>

        </div>

    </div>


    @if ($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="font-semibold text-red-800">
                Please correct the following:
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Original nomination --}}

    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Original Nomination
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                These details are inherited from the withdrawn candidate and
                cannot be changed during replacement.
            </p>

        </div>


        <div class="grid gap-5 md:grid-cols-2">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Withdrawn Candidate
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $nomination->candidate_name }}
                </p>
            </div>


            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Election
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $nomination->election?->name }}
                </p>
            </div>


            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Position
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $nomination->position?->name }}
                </p>
            </div>


            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Electoral Area
                </p>

                <p class="mt-1 font-semibold text-slate-950">

                    @if ($nomination->lcda)
                        LCDA: {{ $nomination->lcda->name }}
                    @elseif ($nomination->ward)
                        Ward: {{ $nomination->ward->name }}
                    @elseif ($nomination->lga)
                        LGA: {{ $nomination->lga->name }}
                    @else
                        Statewide
                    @endif

                </p>
            </div>

        </div>


        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">

            <p class="text-sm font-semibold text-emerald-800">
                Payment inheritance
            </p>

            <p class="mt-1 text-sm leading-6 text-emerald-700">
                The original nomination was already paid for and confirmed.
                This replacement will use that existing payment.
                No additional payment will be required.
            </p>

        </div>

    </div>


    {{-- Replacement candidate --}}

    <form
        method="POST"
        action="{{ route('party.replacements.store', $withdrawal) }}"
        class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
    >

        @csrf


        {{-- Required by the existing nomination request.
             The replacement service deliberately uses the original
             nomination's election/position instead of allowing changes. --}}

        <input
            type="hidden"
            name="election_id"
            value="{{ $nomination->election_id }}"
        >

        <input
            type="hidden"
            name="position_id"
            value="{{ $nomination->position_id }}"
        >


        <div class="mb-6">

            <h2 class="text-lg font-semibold text-slate-950">
                Replacement Candidate
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Verify the new candidate using their National Identification
                Number.
            </p>

        </div>


        {{-- NIN --}}

        <div>

            <label
                for="nin"
                class="block text-sm font-medium text-slate-700"
            >
                National Identification Number (NIN)
            </label>

            <div class="mt-2 flex gap-3">

                <input
                    id="nin"
                    name="nin"
                    type="text"
                    inputmode="numeric"
                    maxlength="11"
                    value="{{ old('nin') }}"
                    required
                    class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    placeholder="Enter 11-digit NIN"
                >

                <button
                    type="button"
                    id="verify-nin"
                    class="shrink-0 rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800"
                >
                    Verify NIN
                </button>

            </div>

            <p
                id="nin-message"
                class="mt-2 hidden text-sm"
            ></p>

        </div>


        {{-- Candidate identity returned by NIN verification --}}

        <div
            id="candidate-result"
            class="mt-5 hidden rounded-lg border border-emerald-200 bg-emerald-50 p-4"
        >

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                Verified Candidate
            </p>

            <p
                id="candidate-name"
                class="mt-1 text-lg font-semibold text-slate-950"
            ></p>

            <p
                id="candidate-details"
                class="mt-1 text-sm text-slate-600"
            ></p>

        </div>


        {{-- Qualification --}}

        <div class="mt-6">

            <label
                for="qualification"
                class="block text-sm font-medium text-slate-700"
            >
                Qualification
            </label>

            <input
                id="qualification"
                name="qualification"
                type="text"
                value="{{ old('qualification') }}"
                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                placeholder="e.g. B.Sc., HND, OND"
            >

        </div>


        {{-- Qualification details --}}

        <div class="mt-6">

            <label
                for="qualification_details"
                class="block text-sm font-medium text-slate-700"
            >
                Qualification Details
            </label>

            <textarea
                id="qualification_details"
                name="qualification_details"
                rows="4"
                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                placeholder="Provide additional qualification information if applicable."
            >{{ old('qualification_details') }}</textarea>

        </div>


        {{-- Disability --}}

        <div class="mt-6">

            <p class="text-sm font-medium text-slate-700">
                Does the candidate have a disability?
            </p>

            <div class="mt-3 flex gap-6">

                <label class="inline-flex items-center gap-2">

                    <input
                        type="radio"
                        name="has_disability"
                        value="0"
                        {{ old('has_disability', '0') == '0' ? 'checked' : '' }}
                        class="border-slate-300 text-emerald-700 focus:ring-emerald-600"
                    >

                    <span class="text-sm text-slate-700">
                        No
                    </span>

                </label>


                <label class="inline-flex items-center gap-2">

                    <input
                        type="radio"
                        name="has_disability"
                        value="1"
                        {{ old('has_disability') == '1' ? 'checked' : '' }}
                        class="border-slate-300 text-emerald-700 focus:ring-emerald-600"
                    >

                    <span class="text-sm text-slate-700">
                        Yes
                    </span>

                </label>

            </div>

        </div>


        <div class="mt-5">

            <label
                for="disability_description"
                class="block text-sm font-medium text-slate-700"
            >
                Disability Description
            </label>

            <textarea
                id="disability_description"
                name="disability_description"
                rows="3"
                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                placeholder="Describe the disability if applicable."
            >{{ old('disability_description') }}</textarea>

        </div>


        {{-- Confirmation --}}

        <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-4">

            <p class="text-sm font-semibold text-amber-800">
                Before registering the replacement
            </p>

            <p class="mt-1 text-sm leading-6 text-amber-700">
                Confirm that the candidate above is the intended replacement.
                The replacement will inherit the withdrawn candidate's
                election, position, electoral area and confirmed payment.
            </p>

        </div>


        <div class="mt-8 flex items-center justify-end gap-3">

            <a
                href="{{ route('party.replacements.index') }}"
                class="rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800"
            >
                Register Replacement Candidate
            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const verifyButton = document.getElementById('verify-nin');
    const ninInput = document.getElementById('nin');
    const message = document.getElementById('nin-message');

    const result = document.getElementById('candidate-result');
    const candidateName = document.getElementById('candidate-name');
    const candidateDetails = document.getElementById('candidate-details');


    verifyButton.addEventListener('click', async function () {

        const nin = ninInput.value.trim();

        message.classList.remove(
            'hidden',
            'text-red-700',
            'text-emerald-700'
        );

        result.classList.add('hidden');


        if (!/^\d{11}$/.test(nin)) {

            message.textContent =
                'Please enter a valid 11-digit NIN.';

            message.classList.add('text-red-700');

            return;
        }


        verifyButton.disabled = true;
        verifyButton.textContent = 'Verifying...';

        message.textContent = 'Verifying candidate...';
        message.classList.add('text-slate-500');


        try {

            const response = await fetch(
                '{{ route('party.candidates.verify-nin') }}',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN':
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute('content')
                    },

                    body: JSON.stringify({
                        nin: nin
                    })
                }
            );


            const data = await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'NIN verification failed.'
                );
            }


            const candidate = data.data || {};


            const fullName = [
                candidate.first_name,
                candidate.middle_name,
                candidate.last_name
            ]
            .filter(Boolean)
            .join(' ');


            candidateName.textContent =
                fullName || 'Candidate verified';


            candidateDetails.textContent =
                candidate.nin
                    ? 'NIN: ' + candidate.nin
                    : 'Identity successfully verified.';


            result.classList.remove('hidden');


            message.textContent =
                'NIN verified successfully.';

            message.classList.remove(
                'text-slate-500'
            );

            message.classList.add(
                'text-emerald-700'
            );


        } catch (error) {

            message.textContent =
                error.message ||
                'Unable to verify NIN.';

            message.classList.remove(
                'text-slate-500'
            );

            message.classList.add(
                'text-red-700'
            );

        } finally {

            verifyButton.disabled = false;
            verifyButton.textContent = 'Verify NIN';

        }

    });

});

</script>

@endsection
'@ | Set-Content resources\views\party\replacements\create.blade.php
