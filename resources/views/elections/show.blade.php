@extends('layouts.app')

@section('title', $election->name . ' | SEMP')

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
            Election Details
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            {{ $election->name }}
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            View the election configuration and nomination fees.
        </p>
    </div>

</div>


{{-- Election information --}}
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Election Information
        </h2>
    </div>

    <div class="grid gap-6 p-6 sm:grid-cols-2 xl:grid-cols-4">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Election Type
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $election->electionType->name }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                State
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $election->state->name }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Election Date
            </p>

            <p class="mt-2 text-sm font-medium text-slate-900">
                {{ $election->election_date->format('d F Y') }}
            </p>
        </div>


        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Status
            </p>

            <div class="mt-2">
                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold capitalize text-amber-700">
                    {{ $election->status }}
                </span>
            </div>
        </div>

    </div>

</div>

{{-- Workflow Timeline --}}
<div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Election Workflow
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Timeline for nominations, screening, appeals and declaration.
        </p>
    </div>

    <div class="grid gap-6 p-6 md:grid-cols-2 lg:grid-cols-3">

        <div>
            <p class="text-xs font-semibold uppercase text-slate-400">
                Nomination Opens
            </p>

            <p class="mt-2 text-sm font-medium">
                {{ optional($election->nomination_open_date)->format('d F Y') ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase text-slate-400">
                Nomination Closes
            </p>

            <p class="mt-2 text-sm font-medium">
                {{ optional($election->nomination_close_date)->format('d F Y') ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase text-slate-400">
                Screening Date
            </p>

            <p class="mt-2 text-sm font-medium">
                {{ optional($election->screening_date)->format('d F Y') ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase text-slate-400">
                Appeal Deadline
            </p>

            <p class="mt-2 text-sm font-medium">
                {{ optional($election->appeal_deadline)->format('d F Y') ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase text-slate-400">
                Result Declaration
            </p>

            <p class="mt-2 text-sm font-medium">
                {{ optional($election->result_declaration_date)->format('d F Y') ?? '-' }}
            </p>
        </div>

    </div>

</div>

{{-- Workflow Actions --}}
<div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Workflow Actions
        </h2>
    </div>

    <div class="flex flex-wrap gap-3 p-6">

        @switch($election->status)

            @case('draft')

                <form method="POST"
      action="{{ route('elections.open-nominations', $election) }}">
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="rounded-lg bg-emerald-700 px-5 py-2 text-white hover:bg-emerald-800">
        Open Nominations
    </button>
</form>

                @break

            @case('nominations_open')

                <form method="POST"
      action="{{ route('elections.start-screening', $election) }}">
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="rounded-lg bg-amber-600 px-5 py-2 text-white hover:bg-amber-700">
        Start Screening
    </button>
</form>

                @break

            @case('screening')

                <form method="POST"
      action="{{ route('elections.complete', $election) }}">
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="rounded-lg bg-blue-700 px-5 py-2 text-white hover:bg-blue-800">
        Complete Election
    </button>
</form>

                @break

            @case('completed')

                <form method="POST"
      action="{{ route('elections.archive', $election) }}">
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="rounded-lg bg-slate-700 px-5 py-2 text-white hover:bg-slate-800">
        Archive Election
    </button>
</form>

                @break

            @case('archived')

                <span class="text-sm text-slate-500">
                    This election has been archived.
                </span>

                @break

        @endswitch

    </div>

</div>


{{-- Positions --}}
<div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Positions & Nomination Fees
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Positions configured for this election.
        </p>
    </div>


    @if ($election->electionPositions->isEmpty())

        <div class="px-6 py-12 text-center">
            <p class="text-sm text-slate-500">
                No positions have been configured for this election.
            </p>
        </div>

    @else

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Code
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Nomination Fee
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-slate-100 bg-white">

                    @foreach ($election->electionPositions as $electionPosition)

                        <tr>

                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                {{ $electionPosition->position->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $electionPosition->position->code }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                ₦{{ number_format($electionPosition->nomination_fee, 2) }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($electionPosition->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection
