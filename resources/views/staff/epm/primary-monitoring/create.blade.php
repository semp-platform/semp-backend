@extends('layouts.staff')

@section('title', 'Schedule Primary')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a
            href="{{ route('staff.epm.primary-monitoring.index') }}"
            class="text-sm font-medium text-indigo-600 hover:underline"
        >
            ← Back to Primary Monitoring
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-900">
            Schedule Primary Monitoring
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Register a political party primary for EPM monitoring.
        </p>
    </div>


    <form
        method="POST"
        action="{{ route('staff.epm.primary-monitoring.store') }}"
        class="space-y-6"
    >

        @csrf


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                Primary Details
            </h2>

            <div class="mt-5 grid gap-5 md:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Election
                    </label>

                    <select
                        name="election_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >
                        <option value="">Select election</option>

                        @foreach($elections as $election)

                            <option
                                value="{{ $election->id }}"
                                @selected(old('election_id') == $election->id)
                            >
                                {{ $election->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('election_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Political Party
                    </label>

                    <select
                        name="political_party_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >
                        <option value="">Select party</option>

                        @foreach($parties as $party)

                            <option
                                value="{{ $party->id }}"
                                @selected(old('political_party_id') == $party->id)
                            >
                                {{ $party->name }}
                                @if($party->acronym)
                                    ({{ $party->acronym }})
                                @endif
                            </option>

                        @endforeach

                    </select>

                    @error('political_party_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Position
                    </label>

                    <select
                        name="position_id"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >
                        <option value="">Select position</option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(old('position_id') == $position->id)
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('position_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1 text-xs text-slate-500">
                        The position must be configured for the selected election.
                    </p>
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Primary Type
                    </label>

                    <select
                        name="primary_type"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >
                        <option value="">Select type</option>

                        <option
                            value="direct"
                            @selected(old('primary_type') === 'direct')
                        >
                            Direct
                        </option>

                        <option
                            value="indirect"
                            @selected(old('primary_type') === 'indirect')
                        >
                            Indirect
                        </option>

                        <option
                            value="consensus"
                            @selected(old('primary_type') === 'consensus')
                        >
                            Consensus
                        </option>

                    </select>

                    @error('primary_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                Date, Time & Venue
            </h2>

            <div class="mt-5 grid gap-5 md:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Scheduled Date
                    </label>

                    <input
                        type="date"
                        name="scheduled_date"
                        value="{{ old('scheduled_date') }}"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >

                    @error('scheduled_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Scheduled Time
                    </label>

                    <input
                        type="time"
                        name="scheduled_time"
                        value="{{ old('scheduled_time') }}"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >

                    @error('scheduled_time')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-slate-700">
                        Venue
                    </label>

                    <input
                        type="text"
                        name="venue"
                        value="{{ old('venue') }}"
                        maxlength="255"
                        placeholder="Primary venue"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >

                    @error('venue')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                Electoral Area
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Enter the geographic area covered by this primary where applicable.
            </p>

            <div class="mt-5">

                <label class="block text-sm font-medium text-slate-700">
                    LGA
                </label>

                <select
                    name="lga_id"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >
                    <option value="">Select LGA</option>

                    @foreach($lgas as $lga)

                        <option
                            value="{{ $lga->id }}"
                            @selected(old('lga_id') == $lga->id)
                        >
                            {{ $lga->name }}
                        </option>

                    @endforeach

                </select>

                @error('lga_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

            </div>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                Party Notice
            </h2>

            <div class="mt-5 grid gap-5 md:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Notice Received
                    </label>

                    <input
                        type="datetime-local"
                        name="notice_received_at"
                        value="{{ old('notice_received_at') }}"
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >

                    @error('notice_received_at')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Notice Status
                    </label>

                    <select
                        name="notice_status"
                        required
                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                    >

                        @foreach([
                            'pending' => 'Pending',
                            'received' => 'Received',
                            'late' => 'Late',
                            'incomplete' => 'Incomplete',
                            'verified' => 'Verified',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected(old('notice_status', 'pending') === $value)
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                    @error('notice_status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>

        </div>


        <div class="flex justify-end gap-3">

            <a
                href="{{ route('staff.epm.primary-monitoring.index') }}"
                class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Create Primary Event
            </button>

        </div>

    </form>

</div>

@endsection
