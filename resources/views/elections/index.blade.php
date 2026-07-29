@extends('layouts.app')

@section('title', 'Elections | SEMP')

@section('content')

<div class="mb-8 flex items-start justify-between gap-4">

    <div>
        <p class="text-sm font-medium text-emerald-700">Election Management</p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Elections
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            View and manage election events configured on SEMP.
        </p>
    </div>

</div>


<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="font-semibold text-slate-950">
            Election Records
        </h2>
    </div>

    @if ($elections->isEmpty())

        <div class="px-6 py-16 text-center">
            <p class="font-medium text-slate-700">
                No elections have been configured.
            </p>

            <p class="mt-2 text-sm text-slate-500">
                Election records will appear here once they are created.
            </p>
        </div>

    @else

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Election
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Type
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            State
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">

                    @foreach ($elections as $election)

                        <tr class="hover:bg-slate-50">

                            <td class="whitespace-nowrap px-6 py-4">
                                <p class="font-medium text-slate-900">
                                    {{ $election->name }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $election->electionType->name }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $election->state->name }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $election->election_date->format('d M Y') }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">

                                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold capitalize text-amber-700">
                                    {{ $election->status }}
                                </span>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <a
                                    href="{{ route('elections.show', $election->id) }}"
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
