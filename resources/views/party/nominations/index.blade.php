@extends('layouts.party')

@section('title', 'Candidate Nominations | SEMP')

@section('content')

<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

    <div>
        <p class="text-sm font-medium text-emerald-700">
            Political Party Portal
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Candidate Nominations
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Manage candidate nominations for {{ $party->name }}.
        </p>
    </div>

    @can('party-nominations.create')
        <a
            href="{{ route('party.nominations.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
        >
            New Candidate Nomination
        </a>
    @endcan

</div>


@if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    @if ($nominations->isEmpty())

        <div class="px-6 py-16 text-center">

            <h2 class="text-lg font-semibold text-slate-900">
                No candidate nominations yet
            </h2>

            <p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">
                Candidate nominations created by {{ $party->name }}
                will appear here.
            </p>

            @can('party-nominations.create')
                <a
                    href="{{ route('party.nominations.create') }}"
                    class="mt-5 inline-flex rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
                >
                    Create First Nomination
                </a>
            @endcan

        </div>

    @else

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

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Electoral Area
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Election
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($nominations as $nomination)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">
                                    {{ $nomination->candidate_name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Candidate ID: {{ $nomination->candidate->id }}
                                </div>
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination->position->name }}
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">

                                @if ($nomination->ward)
                                    {{ $nomination->ward->name }}
                                    <div class="text-xs text-slate-500">
                                        {{ $nomination->lga?->name }}
                                    </div>
                                @elseif ($nomination->lga)
                                    {{ $nomination->lga->name }}
                                @else
                                    —
                                @endif

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination->election->name }}
                            </td>


                            <td class="px-6 py-4">

                                @if ($nomination->display_status === 'draft')

    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
        Draft
    </span>

@elseif ($nomination->display_status === 'withdrawal_pending')

    <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-800">
        Withdrawal Pending
    </span>

@elseif ($nomination->display_status === 'ready')

    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">
        Ready
    </span>

@elseif ($nomination->display_status === 'approved')

    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
        Approved
    </span>

@elseif ($nomination->display_status === 'rejected')

    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800">
        Rejected
    </span>

@else

    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
        {{ ucfirst(str_replace('_', ' ', $nomination->display_status)) }}
    </span>

@endif

                            </td>


                            <td class="px-6 py-4 text-right">

    <div class="flex items-center justify-end gap-4">

        <a
            href="{{ route('party.nominations.show', $nomination->id) }}"
            class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
        >
            View
        </a>

        @if ($nomination->canBeDeleted())

            <form
                method="POST"
                action="{{ route('party.nominations.destroy', $nomination->id) }}"
                onsubmit="return confirm('Remove this candidate nomination? This action cannot be undone.');"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="text-sm font-semibold text-red-600 hover:text-red-800"
                >
                    Delete
                </button>
            </form>

        @endif

    </div>

</td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection
