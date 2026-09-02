@extends('layouts.app')

@section('title', 'Create Election | SEMP')

@section('content')

<div class="mb-8">

    <a
        href="{{ route('elections.index') }}"
        class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Elections
    </a>

    <div class="mt-5">
        <p class="text-sm font-medium text-emerald-700">
            Election Management
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Create Election
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Configure a new election and its nomination positions.
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


<form
    method="POST"
    action="{{ route('elections.store') }}"
    class="space-y-8"
>
    @csrf


    {{-- Basic information --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-950">
                Election Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Enter the basic information for this election.
            </p>
        </div>


        <div class="grid gap-6 p-6 md:grid-cols-2">

            <div>
                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Election Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="e.g. 2027 Ogun Local Government Election"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                >
            </div>


            <div>
                <label
                    for="election_type_id"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Election Type
                </label>

                <select
                    id="election_type_id"
                    name="election_type_id"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                >
                    <option value="">Select election type</option>

                    @foreach ($electionTypes as $type)
                        <option
                            value="{{ $type->id }}"
                            @selected(old('election_type_id') == $type->id)
                        >
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div>
                <label
                    for="state_id"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    State
                </label>

                <select
                    id="state_id"
                    name="state_id"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                >
                    <option value="">Select state</option>

                    @foreach ($states as $state)
                        <option
                            value="{{ $state->id }}"
                            @selected(old('state_id') == $state->id)
                        >
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div>
                <label
                    for="election_date"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Election Date
                </label>

                <input
                    id="election_date"
                    type="date"
                    name="election_date"
                    value="{{ old('election_date') }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                >
            </div>

        </div>

    </div>
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Election Location
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Specify where this election will be conducted.
        </p>
    </div>

    <div class="grid gap-6 p-6 md:grid-cols-2">

        {{-- LGA --}}
        <div id="lga-container" class="hidden">

            <label
                for="lga_id"
                class="mb-2 block text-sm font-medium text-slate-700"
            >
                Local Government
            </label>

            <select
                id="lga_id"
                name="lga_id"
                class="w-full rounded-lg border border-slate-300 px-4 py-3"
            >
                <option value="">Select LGA</option>
            </select>

        </div>


        {{-- Ward --}}
        <div id="ward-container" class="hidden">

            <label
                for="ward_id"
                class="mb-2 block text-sm font-medium text-slate-700"
            >
                Ward
            </label>

            <select
                id="ward_id"
                name="ward_id"
                class="w-full rounded-lg border border-slate-300 px-4 py-3"
            >
                <option value="">Select Ward</option>
            </select>

        </div>

        {{-- LCDA Ward --}}
<div id="lcda-ward-container" class="hidden">

    <label
        for="lcda_ward_id"
        class="mb-2 block text-sm font-medium text-slate-700"
    >
        LCDA Ward
    </label>

    <select
        id="lcda_ward_id"
        name="lcda_ward_id"
        class="w-full rounded-lg border border-slate-300 px-4 py-3"
    >
        <option value="">Select LCDA Ward</option>
    </select>

</div>

        {{-- LCDA --}}
        <div id="lcda-container" class="hidden">

            <label
                for="lcda_id"
                class="mb-2 block text-sm font-medium text-slate-700"
            >
                LCDA
            </label>

            <select
                id="lcda_id"
                name="lcda_id"
                class="w-full rounded-lg border border-slate-300 px-4 py-3"
            >
                <option value="">Select LCDA</option>
            </select>

        </div>

    </div>

</div>
{{-- Workflow --}}
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Election Workflow
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Configure the nomination, screening and declaration timeline.
        </p>
    </div>

    <div class="grid gap-6 p-6 md:grid-cols-2 lg:grid-cols-3">

        <div>
            <label class="mb-2 block text-sm font-medium">
                Nomination Opens
            </label>

            <input
                type="date"
                name="nomination_open_date"
                value="{{ old('nomination_open_date') }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-3">
        </div>


        <div>
            <label class="mb-2 block text-sm font-medium">
                Nomination Closes
            </label>

            <input
                type="date"
                name="nomination_close_date"
                value="{{ old('nomination_close_date') }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-3">
        </div>


        <div>
            <label class="mb-2 block text-sm font-medium">
                Screening Date
            </label>

            <input
                type="date"
                name="screening_date"
                value="{{ old('screening_date') }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-3">
        </div>


        <div>
            <label class="mb-2 block text-sm font-medium">
                Appeal Deadline
            </label>

            <input
                type="date"
                name="appeal_deadline"
                value="{{ old('appeal_deadline') }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-3">
        </div>


        <div>
            <label class="mb-2 block text-sm font-medium">
                Result Declaration
            </label>

            <input
                type="date"
                name="result_declaration_date"
                value="{{ old('result_declaration_date') }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-3">
        </div>

    </div>

</div>

    {{-- Positions --}}
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Positions & Nomination Fees
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Select the positions that will be contested and configure their nomination fees.
        </p>
    </div>

    <div class="divide-y divide-slate-100">

        @foreach ($positions as $position)

            @php
                $index = $loop->index;

                $selected = old("positions.{$index}.position_id") == $position->id;
            @endphp

            <div class="grid gap-4 px-6 py-5 md:grid-cols-[1fr_220px] md:items-center">

                <div class="flex items-start gap-3">

                    <input
                        id="position-{{ $position->id }}"
                        type="checkbox"
                        name="positions[{{ $index }}][position_id]"
                        value="{{ $position->id }}"
                        @checked($selected)
                        onchange="
                            document.getElementById('fee-{{ $position->id }}').disabled = !this.checked;
                        "
                        class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600"
                    >

                    <label
                        for="position-{{ $position->id }}"
                        class="cursor-pointer"
                    >
                        <span class="block text-sm font-semibold text-slate-900">
                            {{ $position->name }}
                        </span>

                        <span class="mt-1 block text-xs text-slate-500">
                            {{ $position->code }}
                        </span>
                    </label>

                </div>

                <div>

                    <label
                        for="fee-{{ $position->id }}"
                        class="mb-1 block text-xs font-medium text-slate-500"
                    >
                        Nomination Fee (₦)
                    </label>

                    <input
                        id="fee-{{ $position->id }}"
                        type="number"
                        min="0"
                        step="0.01"
                        name="positions[{{ $index }}][nomination_fee]"
                        value="{{ old(
                            "positions.{$index}.nomination_fee",
                            in_array($position->code, ['VICE', 'LCDA_VICE'], true) ? '0' : ''
                        ) }}"
                        @disabled(!$selected)
                        @if (in_array($position->code, ['VICE', 'LCDA_VICE'], true))
                            readonly
                        @endif
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition disabled:bg-slate-100 disabled:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                    >

                </div>

            </div>

        @endforeach

    </div>

</div>

    <div class="flex items-center justify-end gap-3">

        <a
            href="{{ route('elections.index') }}"
            class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="rounded-lg bg-emerald-800 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-900"
        >
            Create Election
        </button>

    </div>

</form>

@push('scripts')
<script>

const electionTypes = @json(
    $electionTypes->pluck('name', 'id')
);

const routes = {
    lgas: "{{ url('/api/states') }}",
    wards: "{{ url('/api/lgas') }}",
};

</script>
@endpush
@push('scripts')
<script
    src="{{ asset('js/election-location.js') }}"
    defer
></script>
@endpush
@endsection
