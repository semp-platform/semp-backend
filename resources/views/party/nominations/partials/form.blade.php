<form
    method="POST"
    action="{{ $mode === 'create'
        ? route('party.nominations.store')
        : route('party.nominations.update', $nomination) }}"
    class="space-y-6"
    id="nomination-form"
>
    @csrf

    @if ($mode === 'edit')
        @method('PUT')
    @endif

    {{-- ==========================================================================
         Election
    ========================================================================== --}}

    <section class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Election
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Select the election and office for which the candidate
                is being nominated.
            </p>

        </div>

        <div class="grid gap-6 p-6 md:grid-cols-2">

            <div>

                <label
                    for="election_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    Election
                </label>

                <select
                    name="election_id"
                    id="election_id"
                    required
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                    <option value="">
                        Select election
                    </option>

                    @foreach ($elections as $election)

                        <option
                            value="{{ $election->id }}"
                            data-state-id="{{ $election->state_id }}"
                            {{
                                old(
                                    'election_id',
                                    $mode === 'edit'
                                        ? $nomination->election_id
                                        : null
                                ) == $election->id
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            {{ $election->name }}
                        </option>

                    @endforeach

                </select>

                @error('election_id')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div>

                <label
                    for="position_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    Position
                </label>

                <select
                    name="position_id"
                    id="position_id"
                    required
                    disabled
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                    <option value="">
                        Select election first
                    </option>

                </select>

                @error('position_id')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>

    </section>


    {{-- Candidate --}}

    @if ($mode === 'create')

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Candidate
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Enter the candidate's 11-digit National Identification Number and verify the identity before saving the nomination.
                </p>

            </div>

            <div class="p-6">

                <label
                    for="nin"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    NIN
                </label>

                <div class="flex max-w-2xl gap-3">

                    <input
                        type="text"
                        id="nin"
                        name="nin"
                        value="{{ old('nin') }}"
                        maxlength="11"
                        inputmode="numeric"
                        autocomplete="off"
                        placeholder="Enter 11-digit NIN"
                        class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                        required
                    >

                    <button
                        type="button"
                        id="verify-nin-button"
                        class="rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Verify NIN
                    </button>

                </div>

                @error('nin')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                <p
                    id="nin-verification-message"
                    class="mt-2 hidden text-sm"
                ></p>

                <div
                    id="candidate-details"
                    class="mt-6 hidden max-w-2xl rounded-lg border border-emerald-200 bg-emerald-50 p-5"
                >

                    <div class="mb-4 flex items-center justify-between">

                        <div>

                            <p class="font-semibold text-slate-900">
                                Verified Candidate
                            </p>

                            <p class="mt-1 text-sm text-emerald-700">
                                Identity successfully verified.
                            </p>

                        </div>

                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Verified
                        </span>

                    </div>

                    <dl class="grid gap-4 sm:grid-cols-2">

                        <div>

                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                Full Name
                            </dt>

                            <dd
                                id="candidate-name"
                                class="mt-1 text-sm font-semibold text-slate-900"
                            ></dd>

                        </div>

                        <div>

                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                Gender
                            </dt>

                            <dd
                                id="candidate-gender"
                                class="mt-1 text-sm text-slate-900"
                            ></dd>

                        </div>

                        <div>

                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                Date of Birth
                            </dt>

                            <dd
                                id="candidate-date-of-birth"
                                class="mt-1 text-sm text-slate-900"
                            ></dd>

                        </div>

                    </dl>

                </div>

            </div>

        </div>

    @else

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Candidate
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Candidate identity has already been verified and cannot be changed.
                </p>

            </div>

            <div class="p-6">

                <div class="mb-6 flex items-center justify-between">

                    <div>

                        <p class="text-lg font-semibold text-slate-900">
                            {{ $nomination->candidate->full_name }}
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            âœ“ Identity successfully verified.
                        </p>

                    </div>

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Verified
                    </span>

                </div>

                <dl class="grid gap-6 sm:grid-cols-2">

                    <div>

                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            National Identification Number
                        </dt>

                        <dd class="mt-1 text-sm text-slate-900">
                            {{ $nomination->candidate->nin }}
                        </dd>

                    </div>

                    <div>

                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Gender
                        </dt>

                        <dd class="mt-1 text-sm text-slate-900">
                            {{ $nomination->candidate->gender }}
                        </dd>

                    </div>

                    <div>

                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Date of Birth
                        </dt>

                        <dd class="mt-1 text-sm text-slate-900">
                            {{ $nomination->candidate->date_of_birth }}
                        </dd>

                    </div>

                </dl>

            </div>

        </div>

    @endif


    {{-- Candidate supplementary information --}}

    <section
        id="candidate-information-section"
        class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm"
    >

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Candidate Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Provide the candidate's qualification and disability information.
            </p>

        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">

            {{-- Qualification --}}

            <div>

                <label
                    for="qualification"
                    class="block text-sm font-medium text-slate-700"
                >
                    Qualification
                </label>

                <input
                    type="text"
                    name="qualification"
                    id="qualification"
                    value="{{ old('qualification', $nomination->candidate?->qualification ?? '') }}"
                    class="mt-2 block w-full rounded-lg border border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    placeholder="e.g. B.Sc. Computer Science"
                >

                @error('qualification')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Qualification details --}}

            <div>

                <label
                    for="qualification_details"
                    class="block text-sm font-medium text-slate-700"
                >
                    Qualification Details
                </label>

                <textarea
                    name="qualification_details"
                    id="qualification_details"
                    rows="3"
                    class="mt-2 block w-full rounded-lg border border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    placeholder="Institution, course, year or other relevant details"
                >{{ old('qualification_details', $nomination->candidate?->qualification_details ?? '') }}</textarea>

                @error('qualification_details')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Disability --}}

            <div>

                <label
                    for="has_disability"
                    class="block text-sm font-medium text-slate-700"
                >
                    Does the candidate have a disability?
                </label>

                <select
                    name="has_disability"
                    id="has_disability"
                    class="mt-2 block w-full rounded-lg border border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                >

                    <option value="0">No</option>
                    <option value="1">Yes</option>

                </select>

                @error('has_disability')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Disability description --}}

            <div>

                <label
                    for="disability_description"
                    class="block text-sm font-medium text-slate-700"
                >
                    Disability Description
                </label>

                <textarea
                    name="disability_description"
                    id="disability_description"
                    rows="3"
                    class="mt-2 block w-full rounded-lg border border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    placeholder="Provide a brief description"
                ></textarea>

                @error('disability_description')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>

    </section>


    {{-- Electoral area --}}

    <section
        id="electoral-area-section"
        class="hidden rounded-xl border border-slate-200 bg-white shadow-sm"
    >

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Electoral Area
            </h2>

            <p
                id="electoral-area-description"
                class="mt-1 text-sm text-slate-500"
            >
                Select the electoral area for this nomination.
            </p>

        </div>

        <div class="grid gap-6 p-6 md:grid-cols-2">

            <div>

                <label
                    for="lga_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    Local Government Area
                </label>

                <select
                    name="lga_id"
                    id="lga_id"
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                    <option value="">
                        Select LGA
                    </option>

                </select>

                @error('lga_id')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div id="ward-container" class="hidden">

                <label
                    for="ward_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    Ward
                </label>

                <select
                    name="ward_id"
                    id="ward_id"
                    disabled
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                    <option value="">
                        Select LGA first
                    </option>

                </select>

                @error('ward_id')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div id="lcda-container" class="hidden">

                <label
                    for="lcda_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    LCDA
                </label>

                <select
                    name="lcda_id"
                    id="lcda_id"
                    disabled
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                    <option value="">
                        Select LGA first
                    </option>

                </select>

                @error('lcda_id')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

<div id="lcda-ward-container" class="hidden">

    <label
        for="lcda_ward_id"
        class="block text-sm font-medium text-slate-700"
    >
        LCDA Ward
    </label>

    <select
        name="lcda_ward_id"
        id="lcda_ward_id"
        disabled
        class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
    >
        <option value="">
            Select LCDA first
        </option>
    </select>

    @error('lcda_ward_id')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>

        </div>

    </section>


    {{-- ==========================================================================
         Actions
    ========================================================================== --}}

    <div class="flex items-center justify-end gap-3">

        <a
            href="{{ route('party.dashboard') }}"
            class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            Cancel
        </a>

        <button
            type="submit"
            id="{{ $mode === 'create' ? 'save-draft-button' : 'update-draft-button' }}"
            @if ($mode === 'create') disabled @endif
            class="rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
        >
            {{ $mode === 'create' ? 'Save as Draft' : 'Update Draft' }}
        </button>

    </div>

</form>


@php
    $electionsJson = $elections->mapWithKeys(function ($election) {

        return [

            $election->id => [

                'type' => $election->electionType->name,

                'state_id' => $election->state_id,

                'lga_id' => $election->lga_id,

                'ward_id' => $election->ward_id,

                'lcda_id' => $election->lcda_id,

                'positions' => $election->electionPositions
                    ->filter(function ($electionPosition) {
                        return $electionPosition->position !== null;
                    })
                    ->map(function ($electionPosition) {
                        return [
                            'id' => $electionPosition->position->id,
                            'name' => $electionPosition->position->name,
                            'code' => $electionPosition->position->code,
                            'nomination_fee' => $electionPosition->nomination_fee,
                        ];
                    })
                    ->values()
                    ->all(),

            ],

        ];

    })->all();


    $lgasJson = $lgas->map(function ($lga) {

        return [
            'id' => $lga->id,
            'state_id' => $lga->state_id,
            'name' => $lga->name,
        ];

    })->values()->all();


    $selectedPositionId = old(
        'position_id',
        $mode === 'edit' ? $nomination->position_id : null
    );


    $selectedLgaId = old(
        'lga_id',
        $mode === 'edit' ? $nomination->lga_id : null
    );


    $selectedWardId = old(
        'ward_id',
        $mode === 'edit' ? $nomination->ward_id : null
    );


    $selectedLcdaId = old(
        'lcda_id',
        $mode === 'edit' ? $nomination->lcda_id : null
    );
@endphp
<script>
    const elections = @json($electionsJson);
    const lgas = @json($lgasJson);

    const oldPositionId = @json($selectedPositionId);
    const oldLgaId = @json($selectedLgaId);
    const oldWardId = @json($selectedWardId);
    const oldLcdaId = @json($selectedLcdaId);

    const electionSelect =
        document.getElementById('election_id');

    const positionSelect =
        document.getElementById('position_id');

    const electoralAreaSection =
        document.getElementById('electoral-area-section');

    const electoralAreaDescription =
        document.getElementById('electoral-area-description');

    const lgaSelect =
        document.getElementById('lga_id');

    const wardContainer =
        document.getElementById('ward-container');

    const wardSelect =
        document.getElementById('ward_id');

    const lcdaContainer =
        document.getElementById('lcda-container');

    const lcdaSelect =
        document.getElementById('lcda_id');

        const lcdaWardContainer =
    document.getElementById('lcda-ward-container');

const lcdaWardSelect =
    document.getElementById('lcda_ward_id');

const oldLcdaWardId =
    '{{ old('lcda_ward_id', $mode === 'edit' ? ($nomination->lcda_ward_id ?? '') : '') }}';


    /*
     * --------------------------------------------------------------------------
     * Selected election / position
     * --------------------------------------------------------------------------
     */

    function selectedElection() {

        return elections[electionSelect.value] ?? null;

    }


    function selectedPosition() {

        const election = selectedElection();

        if (!election) {
            return null;
        }

        return election.positions.find(
            position =>
                String(position.id) ===
                String(positionSelect.value)
        ) ?? null;

    }


    /*
     * --------------------------------------------------------------------------
     * Reset helpers
     * --------------------------------------------------------------------------
     */

    function resetWard() {

        wardSelect.innerHTML =
            '<option value="">Select LGA first</option>';

        wardSelect.disabled = true;

    }


    function resetLcda() {

        lcdaSelect.innerHTML =
            '<option value="">Select LGA first</option>';

        lcdaSelect.disabled = true;

    }
        /*
     * --------------------------------------------------------------------------
     * Reset LCDA wards
     * --------------------------------------------------------------------------
     */

    function resetLcdaWard() {

        lcdaWardSelect.innerHTML =
            '<option value="">Select LCDA first</option>';

        lcdaWardSelect.disabled = true;

        lcdaWardSelect.value = '';

        lcdaWardContainer.classList.add('hidden');

        lcdaWardSelect.required = false;

    }


    /*
     * --------------------------------------------------------------------------
     * Load wards belonging to an LCDA
     * --------------------------------------------------------------------------
     */

    async function loadLcdaWards(lcdaId) {

        resetLcdaWard();

        if (!lcdaId) {
            return;
        }

        lcdaWardSelect.innerHTML =
            '<option value="">Loading LCDA wards...</option>';

        lcdaWardSelect.disabled = true;

        try {

            const response = await fetch(
                `/locations/lcdas/${lcdaId}/wards`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Unable to load LCDA wards.'
                );
            }

            const payload =
                await response.json();

            const wards =
                payload.data ?? payload;

            lcdaWardSelect.innerHTML =
                '<option value="">Select LCDA Ward</option>';

            wards.forEach(ward => {

                const option =
                    document.createElement('option');

                option.value = ward.id;

                option.textContent = ward.name;

                if (
                    String(oldLcdaWardId) ===
                    String(ward.id)
                ) {
                    option.selected = true;
                }

                lcdaWardSelect.appendChild(option);

            });

            lcdaWardSelect.disabled = false;

            lcdaWardContainer.classList.remove('hidden');

            lcdaWardSelect.required =
    (
        selectedElection()?.type === 'LCDA Election' &&
        selectedPosition()?.code === 'COUNC'
    )
    ||
    (
        selectedElection()?.type === 'LGA/LCDA Election' &&
        selectedPosition()?.code === 'LCDA_COUNC'
    );

        } catch (error) {

            lcdaWardSelect.innerHTML =
                '<option value="">Unable to load LCDA wards</option>';

            lcdaWardSelect.disabled = true;

            console.error(error);

        }

    }


    function resetElectoralArea() {

        electoralAreaSection.classList.add('hidden');

        wardContainer.classList.add('hidden');

        lcdaContainer.classList.add('hidden');

        lgaSelect.required = false;
        wardSelect.required = false;
        lcdaSelect.required = false;

        lgaSelect.value = '';

        resetWard();
        resetLcda();

    }


    /*
     * --------------------------------------------------------------------------
     * Populate positions
     * --------------------------------------------------------------------------
     */

    function populatePositions() {

        const election = selectedElection();

        positionSelect.innerHTML =
            '<option value="">Select position</option>';

        if (!election) {

            positionSelect.disabled = true;

            resetElectoralArea();

            return;
        }

        election.positions.forEach(position => {

            const option =
                document.createElement('option');

            option.value = position.id;

            option.textContent = position.name;

            option.dataset.code = position.code;

            option.dataset.nominationFee =
                position.nomination_fee;

            if (
                String(oldPositionId) ===
                String(position.id)
            ) {
                option.selected = true;
            }

            positionSelect.appendChild(option);

        });

        positionSelect.disabled = false;

        populateLgas();

        updateElectoralArea();

    }


    /*
     * --------------------------------------------------------------------------
     * Populate LGAs
     * --------------------------------------------------------------------------
     */

    function populateLgas() {

        const election = selectedElection();

        lgaSelect.innerHTML =
            '<option value="">Select LGA</option>';

        if (!election) {
            return;
        }

        lgas
            .filter(
                lga =>
                    String(lga.state_id) ===
                    String(election.state_id)
            )
            .forEach(lga => {

                const option =
                    document.createElement('option');

                option.value = lga.id;

                option.textContent = lga.name;

                if (
                    String(oldLgaId) ===
                    String(lga.id)
                ) {
                    option.selected = true;
                }

                lgaSelect.appendChild(option);

            });

    }


    /*
     * --------------------------------------------------------------------------
     * Load wards for an LGA
     * --------------------------------------------------------------------------
     */

    async function loadWards(lgaId) {

        resetWard();

        if (!lgaId) {
            return;
        }

        wardSelect.innerHTML =
            '<option value="">Loading wards...</option>';

        try {

            const response = await fetch(
                `/api/lgas/${lgaId}/wards`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Unable to load wards.'
                );
            }

            const payload =
                await response.json();

            const wards =
                payload.data ?? payload;

            wardSelect.innerHTML =
                '<option value="">Select ward</option>';

            wards.forEach(ward => {

                const option =
                    document.createElement('option');

                option.value = ward.id;

                option.textContent = ward.name;

                if (
                    String(oldWardId) ===
                    String(ward.id)
                ) {
                    option.selected = true;
                }

                wardSelect.appendChild(option);

            });

            wardSelect.disabled = false;

            syncWardLcda();

        } catch (error) {

            wardSelect.innerHTML =
                '<option value="">Unable to load wards</option>';

            wardSelect.disabled = true;

            console.error(error);

        }

    }


    /*
     * --------------------------------------------------------------------------
     * Load LCDAs belonging to an LGA
     * --------------------------------------------------------------------------
     */

    async function loadLcdas(lgaId) {

    resetLcda();

    if (!lgaId) {
        return;
    }

    lcdaSelect.innerHTML =
        '<option value="">Loading LCDAs...</option>';

    try {

        const response = await fetch(
            `/locations/lgas/${lgaId}/lcdas`,
            {
                headers: {
                    'Accept': 'application/json'
                }
            }
        );

        if (!response.ok) {
            throw new Error(
                'Unable to load LCDAs.'
            );
        }

        const lcdas =
            await response.json();

        lcdaSelect.innerHTML =
            '<option value="">Select LCDA</option>';

        lcdas.forEach(lcda => {

            const option =
                document.createElement('option');

            option.value = lcda.id;
            option.textContent = lcda.name;

            if (
                String(oldLcdaId) ===
                String(lcda.id)
            ) {
                option.selected = true;
            }

            lcdaSelect.appendChild(option);

        });

        lcdaSelect.disabled = false;

        /*
         * LCDA Ward is required only for:
         *
         * - COUNC during LCDA Election
         * - LCDA_COUNC during LGA/LCDA Election
         */
        const positionCode =
            selectedPosition()?.code;

        if (
            lcdaSelect.value &&
            (
                (
                    electionSelect &&
                    selectedElection()?.type === 'LCDA Election' &&
                    positionCode === 'COUNC'
                )
                ||
                (
                    selectedElection()?.type === 'LGA/LCDA Election' &&
                    positionCode === 'LCDA_COUNC'
                )
            )
        ) {
            await loadLcdaWards(lcdaSelect.value);
        } else {
            resetLcdaWard();
        }

    } catch (error) {

        lcdaSelect.innerHTML =
            '<option value="">Unable to load LCDAs</option>';

        lcdaSelect.disabled = true;

        console.error(error);
    }
}


    /*
     * --------------------------------------------------------------------------
     * Load LCDAs directly from the State
     *
     * Used ONLY by LCDA Bye Election.
     * --------------------------------------------------------------------------
     */

    async function loadStateLcdas(stateId) {

        resetLcda();

        if (!stateId) {
            return;
        }

        lcdaSelect.innerHTML =
            '<option value="">Loading LCDAs...</option>';

        try {

            const response = await fetch(
                `/locations/states/${stateId}/lcdas`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Unable to load LCDAs.'
                );
            }

            const lcdas =
                await response.json();

            lcdaSelect.innerHTML =
                '<option value="">Select LCDA</option>';

            lcdas.forEach(lcda => {

                const option =
                    document.createElement('option');

                option.value = lcda.id;

                option.textContent = lcda.name;

                if (
                    String(oldLcdaId) ===
                    String(lcda.id)
                ) {
                    option.selected = true;
                }

                lcdaSelect.appendChild(option);

            });

            lcdaSelect.disabled = false;

        } catch (error) {

            lcdaSelect.innerHTML =
                '<option value="">Unable to load LCDAs</option>';

            lcdaSelect.disabled = true;

            console.error(error);

        }

    }


    /*
     * --------------------------------------------------------------------------
     * Ward / LCDA mutual exclusion
     *
     * This is used ONLY where both fields are actually visible.
     * --------------------------------------------------------------------------
     */

    function syncWardLcda() {

        if (
            wardContainer.classList.contains('hidden') ||
            lcdaContainer.classList.contains('hidden')
        ) {
            return;
        }

        const wardSelected =
            wardSelect.value !== '';

        const lcdaSelected =
            lcdaSelect.value !== '';

        if (wardSelected) {

            lcdaSelect.disabled = true;

        } else if (lcdaSelected) {

            wardSelect.disabled = true;

        } else {

            /*
             * Neither field has been selected.
             * Both are available.
             */
            wardSelect.disabled = false;
            lcdaSelect.disabled = false;

        }

    }


    /*
     * --------------------------------------------------------------------------
     * Electoral-area rules
     * --------------------------------------------------------------------------
     */

    function updateElectoralArea() {

    const election =
        selectedElection();

    const position =
        selectedPosition();

    if (!position || !election) {

        resetElectoralArea();

        return;
    }

    electoralAreaSection.classList.remove('hidden');

    /*
     * ==========================================================================
     * LCDA Bye Election
     *
     * The administrator already selected the specific LCDA and LCDA Ward
     * when creating the election.
     *
     * The party does not select an electoral area again.
     * ==========================================================================
     */

    if (election.type === 'LCDA Bye Election') {

        resetElectoralArea();

        return;
    }


    /*
     * ==========================================================================
     * LGA/LCDA Election
     *
     * The election itself is statewide.
     *
     * Location depends on the position:
     *
     * CHAIR       -> LGA
     * VICE        -> LGA
     * COUNC       -> LGA + Ward
     *
     * LCDA_CHAIR  -> LGA + LCDA
     * LCDA_VICE   -> LGA + LCDA
     * LCDA_COUNC  -> LGA + LCDA + LCDA Ward
     * ==========================================================================
     */

    if (election.type === 'LGA/LCDA Election') {

        /*
         * LGA Chairmanship / Vice Chairmanship
         */
        if (
            position.code === 'CHAIR' ||
            position.code === 'VICE'
        ) {

            electoralAreaDescription.textContent =
                'Select the Local Government Area for this nomination.';

            lgaSelect.required = true;

            wardContainer.classList.add('hidden');
            wardSelect.required = false;
            wardSelect.value = '';

            lcdaContainer.classList.add('hidden');
            lcdaSelect.required = false;
            lcdaSelect.value = '';

            resetLcdaWard();

            return;
        }


        /*
         * LGA Councillorship
         *
         * LGA + Ward
         */
        if (position.code === 'COUNC') {

            electoralAreaDescription.textContent =
                'Select the Local Government Area and Ward for this nomination.';

            lgaSelect.required = true;

            wardContainer.classList.remove('hidden');
            wardSelect.required = true;

            lcdaContainer.classList.add('hidden');
            lcdaSelect.required = false;
            lcdaSelect.value = '';

            resetLcdaWard();

            if (lgaSelect.value) {
                loadWards(lgaSelect.value);
            }

            return;
        }


        /*
         * LCDA Chairmanship / Vice Chairmanship
         *
         * LGA + LCDA
         */
        if (
            position.code === 'LCDA_CHAIR' ||
            position.code === 'LCDA_VICE'
        ) {

            electoralAreaDescription.textContent =
                'Select the Local Government Area and LCDA for this nomination.';

            lgaSelect.required = true;

            wardContainer.classList.add('hidden');
            wardSelect.required = false;
            wardSelect.value = '';

            lcdaContainer.classList.remove('hidden');
            lcdaSelect.required = true;

            resetLcdaWard();

            if (lgaSelect.value) {
                loadLcdas(lgaSelect.value);
            }

            return;
        }


        /*
         * LCDA Councillorship
         *
         * LGA + LCDA + LCDA Ward
         */
        if (position.code === 'LCDA_COUNC') {

            electoralAreaDescription.textContent =
                'Select the Local Government Area, LCDA and LCDA Ward for this nomination.';

            lgaSelect.required = true;

            wardContainer.classList.add('hidden');
            wardSelect.required = false;
            wardSelect.value = '';

            lcdaContainer.classList.remove('hidden');
            lcdaSelect.required = true;

            if (lgaSelect.value) {
                loadLcdas(lgaSelect.value);
            }

            return;
        }

        resetElectoralArea();

        return;
    }


    /*
     * ==========================================================================
     * LCDA Election
     *
     * CHAIR -> LGA + LCDA
     * VICE  -> LGA + LCDA
     * COUNC -> LGA + LCDA + LCDA Ward
     * ==========================================================================
     */

    if (election.type === 'LCDA Election') {

        lgaSelect.required = true;

        wardContainer.classList.add('hidden');
        wardSelect.required = false;
        wardSelect.value = '';

        lcdaContainer.classList.remove('hidden');
        lcdaSelect.required = true;

        if (
            position.code === 'CHAIR' ||
            position.code === 'VICE'
        ) {

            electoralAreaDescription.textContent =
                'Select the Local Government Area and LCDA for this nomination.';

            resetLcdaWard();

        } else if (position.code === 'COUNC') {

            electoralAreaDescription.textContent =
                'Select the Local Government Area, LCDA and LCDA Ward for this nomination.';

        } else {

            resetElectoralArea();

            return;
        }

        if (lgaSelect.value) {
            loadLcdas(lgaSelect.value);
        }

        return;
    }


    /*
     * ==========================================================================
     * Normal LGA elections
     *
     * CHAIR -> LGA
     * VICE  -> LGA
     * COUNC -> LGA + Ward
     * ==========================================================================
     */

    if (
        position.code === 'CHAIR' ||
        position.code === 'VICE'
    ) {

        electoralAreaDescription.textContent =
            'Select the Local Government Area for this nomination.';

        lgaSelect.required = true;

        wardContainer.classList.add('hidden');
        wardSelect.required = false;
        wardSelect.value = '';

        lcdaContainer.classList.add('hidden');
        lcdaSelect.required = false;
        lcdaSelect.value = '';

        resetLcdaWard();

        return;
    }


    if (position.code === 'COUNC') {

        electoralAreaDescription.textContent =
            'Select the Local Government Area and Ward for this nomination.';

        lgaSelect.required = true;

        wardContainer.classList.remove('hidden');
        wardSelect.required = true;

        lcdaContainer.classList.add('hidden');
        lcdaSelect.required = false;
        lcdaSelect.value = '';

        resetLcdaWard();

        if (lgaSelect.value) {
            loadWards(lgaSelect.value);
        }

        return;
    }


    resetElectoralArea();
}


    /*
     * --------------------------------------------------------------------------
     * Election changed
     * --------------------------------------------------------------------------
     */

    electionSelect.addEventListener(
        'change',
        function () {

            positionSelect.value = '';

            lgaSelect.value = '';

            resetWard();

            resetLcda();

            populatePositions();

        }
    );


    /*
     * --------------------------------------------------------------------------
     * Position changed
     * --------------------------------------------------------------------------
     */

    positionSelect.addEventListener(
        'change',
        function () {

            resetWard();

            resetLcda();

            updateElectoralArea();

        }
    );


    /*
     * --------------------------------------------------------------------------
     * LGA changed
     * --------------------------------------------------------------------------
     */

    lgaSelect.addEventListener(
    'change',
    function () {

        const election =
            selectedElection();

        const position =
            selectedPosition();

        resetWard();
        resetLcda();
        resetLcdaWard();

        if (!election || !position || !this.value) {
            return;
        }


        /*
         * LGA Councillorship
         */
        if (
            position.code === 'COUNC' &&
            (
                election.type === 'Local Government Election' ||
                election.type === 'Bye Election' ||
                election.type === 'Re-run Election' ||
                election.type === 'Supplementary Election' ||
                election.type === 'LGA/LCDA Election'
            )
        ) {

            loadWards(this.value);

            return;
        }


        /*
         * LCDA Election
         */
        if (
            election.type === 'LCDA Election'
        ) {

            loadLcdas(this.value);

            return;
        }


        /*
         * LGA/LCDA Election
         *
         * LCDA positions use LCDA.
         */
        if (
            election.type === 'LGA/LCDA Election' &&
            (
                position.code === 'LCDA_CHAIR' ||
                position.code === 'LCDA_VICE' ||
                position.code === 'LCDA_COUNC'
            )
        ) {

            loadLcdas(this.value);

            return;
        }


        /*
         * All other positions require no additional
         * location loading after LGA selection.
         */
    }
);

    /*
     * --------------------------------------------------------------------------
     * Ward selected
     * --------------------------------------------------------------------------
     */

    wardSelect.addEventListener(
        'change',
        function () {

            if (wardSelect.value) {

                lcdaSelect.value = '';

            }

            syncWardLcda();

        }
    );


    /*
     * --------------------------------------------------------------------------
     * LCDA selected
     * --------------------------------------------------------------------------
     */

lcdaSelect.addEventListener(
    'change',
    async function () {

        const election =
            selectedElection();

        const position =
            selectedPosition();

        resetLcdaWard();

        if (!election || !position || !lcdaSelect.value) {
            return;
        }


        /*
         * LCDA Councillorship during an LCDA Election.
         */
        if (
            election.type === 'LCDA Election' &&
            position.code === 'COUNC'
        ) {

            await loadLcdaWards(
                lcdaSelect.value
            );

            return;
        }


        /*
         * LCDA Councillorship during an
         * LGA/LCDA Election.
         */
        if (
            election.type === 'LGA/LCDA Election' &&
            position.code === 'LCDA_COUNC'
        ) {

            await loadLcdaWards(
                lcdaSelect.value
            );

            return;
        }


        /*
         * LCDA Chairmanship / Vice Chairmanship
         * do not use an LCDA Ward.
         */
        resetLcdaWard();
    }
);

    /*
     * If an existing nomination has a Ward, restore it after the
     * Ward options have been loaded.
     *
     * If it has an LCDA, restore it after the LCDA options have loaded.
     *
     * The loaders already use oldWardId / oldLcdaId when creating
     * their options, so no second assignment is needed here.
     */

/*
 * --------------------------------------------------------------------------
 * Initial page load
 *
 * Restore the existing election, position and electoral area.
 * The existing loaders already restore oldWardId / oldLcdaId.
 * --------------------------------------------------------------------------
 */

if (electionSelect.value) {
    populatePositions();
}


    const ninInput = document.getElementById('nin');

    const verifyNinButton =
        document.getElementById('verify-nin-button');

    const verificationMessage =
        document.getElementById('nin-verification-message');

    const candidateDetails =
        document.getElementById('candidate-details');

    const candidateName =
        document.getElementById('candidate-name');

    const candidateGender =
        document.getElementById('candidate-gender');

    const candidateDateOfBirth =
        document.getElementById('candidate-date-of-birth');

    const saveDraftButton =
        document.getElementById(
            '{{ $mode === "create" ? "save-draft-button" : "update-draft-button" }}'
        );

    let verifiedNin = null;


    function resetCandidateVerification() {

        verifiedNin = null;

        candidateDetails.classList.add('hidden');

        candidateName.textContent = '';
        candidateGender.textContent = '';
        candidateDateOfBirth.textContent = '';

        verificationMessage.classList.add('hidden');
        verificationMessage.textContent = '';

        saveDraftButton.disabled = true;
    }


    ninInput.addEventListener('input', function () {

        this.value =
            this.value.replace(/\D/g, '').slice(0, 11);

        resetCandidateVerification();
    });


    verifyNinButton.addEventListener('click', async function () {

        resetCandidateVerification();

        const nin = ninInput.value.trim();

        if (!/^\d{11}$/.test(nin)) {

            verificationMessage.textContent =
                'Enter a valid 11-digit NIN.';

            verificationMessage.className =
                'mt-2 text-sm text-red-600';

            verificationMessage.classList.remove('hidden');

            return;
        }

        verifyNinButton.disabled = true;
        verifyNinButton.textContent = 'Verifying...';

        try {

            const response = await fetch(
                '{{ route('party.candidates.verify-nin') }}',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    body: JSON.stringify({
                        nin: nin
                    })
                }
            );

            const payload = await response.json();

            if (!response.ok) {

                const message =
                    payload.errors?.nin?.[0]
                    ?? payload.message
                    ?? 'The NIN could not be verified.';

                throw new Error(message);
            }

            const candidate = payload.data;

            verifiedNin = nin;

            const fullName = [
                candidate.first_name,
                candidate.middle_name,
                candidate.last_name
            ]
                .filter(Boolean)
                .join(' ');

            candidateName.textContent = fullName;

            candidateGender.textContent =
                candidate.gender ?? 'â€”';

            candidateDateOfBirth.textContent =
                candidate.date_of_birth ?? 'â€”';

            candidateDetails.classList.remove('hidden');

            saveDraftButton.disabled = false;

            verificationMessage.textContent =
                'NIN verified successfully.';

            verificationMessage.className =
                'mt-2 text-sm font-medium text-emerald-700';

            verificationMessage.classList.remove('hidden');

        } catch (error) {

            candidateDetails.classList.add('hidden');

            verificationMessage.textContent =
                error.message;

            verificationMessage.className =
                'mt-2 text-sm text-red-600';

            verificationMessage.classList.remove('hidden');

        } finally {

            verifyNinButton.disabled = false;
            verifyNinButton.textContent = 'Verify NIN';
        }
    });


    /*
     * Prevent the nomination from being marked as ready
     * unless the current NIN is the NIN that was verified.
     */
    const nominationForm =
        document.getElementById('nomination-form');

    nominationForm.addEventListener('submit', function (event) {

        const currentNin = ninInput.value.trim();

        if (
            !verifiedNin ||
            verifiedNin !== currentNin
        ) {

            event.preventDefault();

            verificationMessage.textContent =
                'Please verify the candidate NIN before saving the nomination.';

            verificationMessage.className =
                'mt-2 text-sm text-red-600';

            verificationMessage.classList.remove('hidden');

            candidateDetails.classList.add('hidden');

            saveDraftButton.disabled = true;
        }
    });

</script>

