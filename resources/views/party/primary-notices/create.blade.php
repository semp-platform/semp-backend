@extends('layouts.party')

@section('title', 'Submit Primary Notice | SEMP')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    <div>

        <a
            href="{{ route('party.primary-notices.index') }}"
            class="text-sm font-medium text-emerald-700 hover:underline"
        >
            ← Back to Party Primaries
        </a>

        <p class="mt-6 text-sm font-medium text-emerald-700">
            Political Party Portal
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Submit Primary Notice
        </h1>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Provide the details of the party primary that your party intends
            to conduct. The notice will be submitted to OGSIEC for review.
        </p>

    </div>


    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <p class="font-semibold text-red-800">
                Please correct the following:
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('party.primary-notices.store') }}"
        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-950">
                Primary Details
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Enter the official details of the proposed primary.
            </p>

        </div>


        <div class="space-y-6 px-6 py-6">

            <div class="grid gap-6 md:grid-cols-2">

                <div>

                    <label
                        for="election_id"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Election
                    </label>

                    <select
                        id="election_id"
                        name="election_id"
                        required
                        class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            Select election
                        </option>

                        @foreach($elections as $election)

                            <option
                                value="{{ $election->id }}"
                                @selected(old('election_id') == $election->id)
                            >
                                {{ $election->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div>

                    <label
                        for="position_id"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Position
                    </label>

                    <select
                        id="position_id"
                        name="position_id"
                        required
                        class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            Select position
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(old('position_id') == $position->id)
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div>

                    <label
                        for="primary_type"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Primary Type
                    </label>

                    <select
                        id="primary_type"
                        name="primary_type"
                        required
                        class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >

                        <option value="">
                            Select primary type
                        </option>

                        <option
                            value="direct"
                            @selected(old('primary_type') === 'direct')
                        >
                            Direct Primary
                        </option>

                        <option
                            value="indirect"
                            @selected(old('primary_type') === 'indirect')
                        >
                            Indirect Primary
                        </option>

                        <option
                            value="consensus"
                            @selected(old('primary_type') === 'consensus')
                        >
                            Consensus
                        </option>

                    </select>

                </div>


                <div>

                    <label
                        for="scheduled_date"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Proposed Date
                    </label>

                    <input
                        type="date"
                        id="scheduled_date"
                        name="scheduled_date"
                        value="{{ old('scheduled_date') }}"
                        required
                        class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >

                </div>


                <div>

                    <label
                        for="scheduled_time"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Proposed Time
                    </label>

                    <input
                        type="time"
                        id="scheduled_time"
                        name="scheduled_time"
                        value="{{ old('scheduled_time') }}"
                        class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >

                </div>


                <div>

                    <label
                        for="venue"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Venue
                    </label>

                    <input
                        type="text"
                        id="venue"
                        name="venue"
                        value="{{ old('venue') }}"
                        required
                        maxlength="255"
                        placeholder="Enter primary venue"
                        class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600"
                    >

                </div>

            </div>



            <div class="rounded-lg border border-amber-200 bg-amber-50 px-5 py-4">

                <p class="text-sm font-semibold text-amber-900">
                    Important
                </p>

                <p class="mt-1 text-sm leading-6 text-amber-800">
                    Submitting this notice sends it to OGSIEC for review.
                    It does not constitute approval of the party primary.
                </p>

            </div>

        </div>


        <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">

            <a
                href="{{ route('party.primary-notices.index') }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
            >
                Submit Primary Notice
            </button>

        </div>

    </form>

</div>

@endsection
