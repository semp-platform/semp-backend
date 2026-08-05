@extends('layouts.party')

@section('title', 'New Nomination Batch | SEMP')

@section('content')

<div class="mb-8">

    <a
        href="{{ route('party.nomination-batches.index') }}"
        class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
    >
        ← Back to Nomination Batches
    </a>

    <div class="mt-5">

        <p class="text-sm font-medium text-emerald-700">
            Candidate Nominations
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            New Nomination Batch
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Select an election to preview all ready nominations before creating a batch.
        </p>

    </div>

</div>


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <form method="GET">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-950">
                Batch Preview
            </h2>

        </div>

        <div class="space-y-6 px-6 py-6">

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
                    class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >

                    <option value="">
                        Select Election
                    </option>

                    @foreach($elections as $election)

                        <option
                            value="{{ $election->id }}"
                        >
                            {{ $election->name }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="flex justify-end border-t border-slate-200 px-6 py-4">

            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            >
                Preview Batch
            </button>

        </div>

    </form>
@if($selectedElection)

<div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-lg font-semibold text-slate-950">
            Batch Preview
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ $selectedElection->name }}
        </p>

    </div>

    <div class="px-6 py-6">

        @if($readyNominations->isEmpty())

            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-4">

                <p class="font-medium text-amber-900">
                    No ready nominations found.
                </p>

                <p class="mt-1 text-sm text-amber-700">
                    There are currently no ready nominations available for batching for this election.
                </p>

            </div>

        @else

            <div class="mb-6 grid gap-6 md:grid-cols-2">

                <div class="rounded-lg border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-500">
                        Ready Candidates
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $readyNominations->count() }}
                    </p>

                </div>

                <div class="rounded-lg border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-500">
                        Total Nomination Fee
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-700">
                        ₦{{ number_format($totalFee, 2) }}
                    </p>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Candidate
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Position
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Fee
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach($readyNominations as $nomination)

                            <tr>

                                <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                    {{ $nomination->candidate_name }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $nomination->position->name }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">
                                    ₦{{ number_format($nomination->nomination_fee, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="mt-6 flex justify-end">

                <form
    method="POST"
    action="{{ route('party.nomination-batches.store') }}"
>
    @csrf

    <input
        type="hidden"
        name="election_id"
        value="{{ $selectedElection->id }}"
    >

    <button
        type="submit"
        class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
    >
        Create Batch
    </button>

</form>

            </div>

        @endif

    </div>

</div>

@endif
</div>

@endsection
