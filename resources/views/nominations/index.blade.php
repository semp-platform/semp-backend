@extends('layouts.app')

@section('title', 'Nominations | SEMP')

@section('content')

<div class="mb-8">
    <p class="text-sm font-medium text-emerald-700">
        Nomination Management
    </p>

    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
        Nominations
    </h1>

    <p class="mt-2 text-sm text-slate-500">
        View candidate nominations submitted for configured elections.
    </p>
</div>


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Nomination Records
        </h2>
    </div>

    @if ($nominations->isEmpty())

        <div class="px-6 py-16 text-center">
            <p class="font-medium text-slate-700">
                No nominations have been submitted.
            </p>
        </div>

    @else

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Party
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Constituency
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($nominations as $nomination)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <p class="whitespace-nowrap font-medium text-slate-900">
                                    {{ $nomination->candidate->first_name }}
                                    {{ $nomination->candidate->middle_name }}
                                    {{ $nomination->candidate->last_name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $nomination->election->name }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $nomination->politicalParty->acronym }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $nomination->politicalParty->name }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $nomination->position->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                <p>{{ $nomination->lga?->name ?? '—' }}</p>

                                @if ($nomination->ward)
                                    <p class="mt-1 text-xs text-slate-400">
                                        Ward: {{ $nomination->ward->name }}
                                    </p>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold capitalize text-amber-700">
                                    {{ $nomination->status }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a
                                    href="{{ route('nominations.show', $nomination->id) }}"
                                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
                                >
                                    View
                                </a>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection
