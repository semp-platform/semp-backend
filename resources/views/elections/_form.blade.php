<form
    method="POST"
    action="{{ $formAction }}"
    class="space-y-8"
>
    @csrf

    @if($isEdit)
        @method('PUT')
    @endif


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
                    value="{{ old('name', $election->name ?? '') }}"
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
                            @selected(
    old(
        'election_type_id',
        $election->election_type_id ?? null
    ) == $type->id
)
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
                            @selected(
    old(
        'state_id',
        $election->state_id ?? null
    ) == $state->id
)
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
                    value="{{ old(
    'election_date',
    isset($election)
        ? $election->election_date->format('Y-m-d')
        : ''
) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                >
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
       $selected =
    old(
        "positions.{$position->id}.position_id",
        isset($election)
            ? $election->electionPositions
                ->contains('position_id', $position->id)
            : false
    );
    @endphp

    <div class="grid gap-4 px-6 py-5 md:grid-cols-[1fr_220px] md:items-center">

        <div class="flex items-start gap-3">

            <input
                id="position-{{ $position->id }}"
                type="checkbox"
                name="positions[{{ $position->id }}][position_id]"
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
                name="positions[{{ $position->id }}][nomination_fee]"

                value="{{ old(
                    "positions.{$position->id}.nomination_fee",
                    $position->code === 'VICE' ? '0' : ''
                ) }}"

                @disabled(!$selected)

                @if ($position->code === 'VICE')
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
            {{ $submitLabel }}
        </button>

    </div>

</form>
