@extends('layouts.staff')

@section('title', 'Result Details')

@section('content')

<div class="mx-auto max-w-7xl">
@if(session('success'))

    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
        {{ session('success') }}
    </div>

@endif

@if($errors->has('result'))

    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">
        {{ $errors->first('result') }}
    </div>

@endif
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Election Results
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                Result Details
            </h1>

            <p class="mt-2 text-sm text-slate-600">
                Review the imported election result before publication.
            </p>

        </div>

        <div class="flex items-center gap-3">

    <a
        href="{{ route('staff.ict.results.index') }}"
        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
    >
        ← Back to Results
    </a>

    @if($resultImport->status === 'imported')

        <form
            method="POST"
            action="{{ route('staff.ict.results.publish', $resultImport) }}"
            onsubmit="return confirm('Publish these election results? They will become visible on the public results page.');"
        >
            @csrf

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
            >
                Publish Results
            </button>
        </form>

    @endif

</div>

    </div>


    {{-- Import summary --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Status
            </p>

            <p class="mt-3 text-lg font-bold text-slate-950">
                {{ ucfirst($resultImport->status) }}
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Worksheets
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ number_format($resultImport->worksheet_count) }}
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Polling Units
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                {{ number_format($pollingUnits) }}
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Total Votes
            </p>

            <p class="mt-3 text-3xl font-bold text-emerald-700">
                {{ number_format($totalVotes) }}
            </p>
        </div>

    </div>


    {{-- Election information --}}
    <div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Election Information
            </h2>

        </div>

        <div class="grid gap-6 px-6 py-6 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Election
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $resultImport->election->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Position
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $resultImport->position->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Uploaded By
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $resultImport->uploader->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Imported
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $resultImport->imported_at?->format('d M Y H:i') ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Party totals --}}
    <div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Party Totals
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Total votes recorded for each political party.
            </p>

        </div>


        <div class="divide-y divide-slate-100">

                   <div class="divide-y divide-slate-100">

            @forelse($parties as $party)

                <div class="flex items-center justify-between px-6 py-4">

                    <div>
                        <p class="font-semibold text-slate-900">
                            {{ $party['party']->acronym ?? 'Unknown' }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ $party['party']->name ?? 'Unknown party' }}
                        </p>
                    </div>

                    <p class="text-lg font-bold text-slate-950">
                        {{ number_format($party['votes'] ?? 0) }}
                    </p>

                </div>

            @empty


        </div>

                <div class="px-6 py-8 text-sm text-slate-500">
                    No party totals available.
                </div>

            @endforelse

        </div>

    </div>


    {{-- LGA summary --}}
<div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-bold text-slate-950">
            Local Government Areas
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Results grouped by local government and ward.
        </p>

    </div>


    @forelse($lgas as $lga)

        {{-- LGA header --}}
        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-bold text-slate-950">
                        {{ $lga['lga']->name ?? 'Unknown LGA' }}
                    </h3>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $lga['polling_units'] }} polling units
                    </p>
                </div>

                <div class="text-right">

                    @if($lga['winner'])

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            LGA Leader
                        </p>

                        <p class="mt-1 text-sm font-bold text-slate-950">
                            {{ $lga['winner']['party']->acronym ?? 'Unknown' }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ number_format($lga['winner']['votes']) }} votes
                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- Ward table --}}
        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr class="border-b border-slate-200">

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Ward
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Polling Units
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Winner
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
    Votes
</th>

<th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
    Action
</th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($lga['wards'] as $ward)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <a
    href="{{ route('staff.ict.results.ward', [
        'resultImport' => $resultImport->id,
        'ward' => $ward['ward']->id,
    ]) }}"
    class="font-semibold text-slate-900 hover:text-emerald-700"
>
    {{ $ward['ward']->name ?? 'Unknown Ward' }}
</a>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-600">

                                {{ $ward['polling_units'] }}

                            </td>


                            <td class="px-6 py-4">

                                @if($ward['winner'])

                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">

                                        {{ $ward['winner']['party']->acronym ?? 'Unknown' }}

                                    </span>

                                @else

                                    <span class="text-xs text-slate-400">
                                        No votes
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-right">

                                @if($ward['winner'])

                                    <p class="font-bold text-slate-950">
                                        {{ number_format($ward['winner']['votes']) }}
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        winning votes
                                    </p>

                                @else

                                    <span class="text-sm text-slate-400">
                                        0
                                    </span>

                                @endif

                            </td>
<td class="px-6 py-4 text-right">

    <a
        href="{{ route('staff.ict.results.ward', [
            'resultImport' => $resultImport->id,
            'ward' => $ward['ward']->id,
        ]) }}"
        class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
    >
        View Results →
    </a>

</td>
                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-8 text-center text-sm text-slate-500"
                            >
                                No ward results available.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    @empty

        <div class="px-6 py-8 text-sm text-slate-500">
            No local government results available.
        </div>

    @endforelse

</div>


    {{-- Result entries --}}
    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-bold text-slate-950">
                Result Entries
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Imported polling-unit result records.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            LGA
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Ward
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Polling Unit
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Party
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                            Votes
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100 bg-white">

                    @foreach($entries as $entry)

                        <tr class="hover:bg-slate-50">

                            <td class="px-5 py-3 text-sm text-slate-700">
                                {{ $entry->lga->name ?? '—' }}
                            </td>

                            <td class="px-5 py-3 text-sm text-slate-700">
                                {{ $entry->ward->name ?? '—' }}
                            </td>

                            <td class="px-5 py-3">

                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $entry->polling_unit_code }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $entry->polling_unit_name }}
                                </p>

                            </td>

                            <td class="px-5 py-3 text-sm font-semibold text-slate-900">
                                {{ $entry->politicalParty->acronym ?? '—' }}
                            </td>

                            <td class="px-5 py-3 text-right text-sm font-bold text-slate-950">
                                {{ number_format($entry->votes) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
