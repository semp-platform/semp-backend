@extends('layouts.staff')

@section('title', 'EPM Records')

@section('content')

<div class="space-y-6">

    {{-- Page heading --}}
    <div>
        <p class="text-sm font-medium text-indigo-600">
            OGSIEC Staff Portal
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-900">
            EPM Records
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Historical nominations previously reviewed and processed by EPM.
            These records remain available after a nomination moves to another department.
        </p>
    </div>


    {{-- Search and filters --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form
            method="GET"
            action="{{ route('staff.epm.records.index') }}"
            class="space-y-5"
        >

            <div>
                <label
                    for="search"
                    class="block text-sm font-medium text-slate-700"
                >
                    Search records
                </label>

                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ $search }}"
                    placeholder="Candidate name, political party, or nomination ID"
                    class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>


            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- Workflow status --}}
                <div>
                    <label
                        for="workflow_status"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Workflow Status
                    </label>

                    <select
                        id="workflow_status"
                        name="workflow_status"
                        class="mt-2 block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All statuses</option>

                        <option
                            value="submitted"
                            @selected($workflowStatus === 'submitted')
                        >
                            Submitted
                        </option>

                        <option
                            value="under_review"
                            @selected($workflowStatus === 'under_review')
                        >
                            Under Review
                        </option>

                        <option
                            value="returned"
                            @selected($workflowStatus === 'returned')
                        >
                            Returned
                        </option>

                        <option
                            value="approved"
                            @selected($workflowStatus === 'approved')
                        >
                            Approved
                        </option>
                    </select>
                </div>


                {{-- Current department --}}
                <div>
                    <label
                        for="department"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Current Department
                    </label>

                    <select
                        id="department"
                        name="department"
                        class="mt-2 block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All departments</option>

                        <option
                            value="ict"
                            @selected($department === 'ict')
                        >
                            ICT
                        </option>

                        <option
                            value="commissioner"
                            @selected($department === 'commissioner')
                        >
                            Commissioner
                        </option>

                        <option
                            value="epm"
                            @selected($department === 'epm')
                        >
                            EPM
                        </option>

                        <option
                            value="legal"
                            @selected($department === 'legal')
                        >
                            Legal
                        </option>
                    </select>
                </div>


                {{-- Election --}}
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
                        class="mt-2 block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All elections</option>

                        @foreach($elections as $election)

                            <option
                                value="{{ $election->id }}"
                                @selected((string) $electionId === (string) $election->id)
                            >
                                {{ $election->name }}
                            </option>

                        @endforeach

                    </select>
                </div>


                {{-- Position --}}
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
                        class="mt-2 block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All positions</option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected((string) $positionId === (string) $position->id)
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>


            {{-- Actions --}}
            <div class="flex flex-wrap items-center gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700"
                >
                    Search Records
                </button>

                <a
                    href="{{ route('staff.epm.records.index') }}"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Clear Filters
                </a>

            </div>

        </form>

    </div>


    {{-- Results --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="font-semibold text-slate-900">
                    Historical EPM Records
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $nominations->total() }}
                    {{ \Illuminate\Support\Str::plural('record', $nominations->total()) }}
                    found
                </p>
            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Political Party
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Current Department
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Workflow Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Last Updated
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                    @forelse($nominations as $nomination)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $nomination->candidate?->full_name ?? 'Unknown candidate' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Nomination #{{ $nomination->id }}
                                </div>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination->politicalParty?->name ?? '—' }}
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination->position?->name ?? '—' }}
                            </td>


                            <td class="px-6 py-4 text-sm">

                                @php
                                    $department = $nomination->current_department;
                                @endphp

                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $department
                                        ? ucfirst($department)
                                        : 'Completed' }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-sm">

                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {{ str_replace(
                                        '_',
                                        ' ',
                                        ucfirst($nomination->workflow_status ?? 'unknown')
                                    ) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $nomination->updated_at?->format('d M Y H:i') ?? '—' }}
                            </td>


                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('staff.epm.records.show', $nomination) }}"
                                    class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                                >
                                    View Record
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-12 text-center"
                            >

                                <p class="font-semibold text-slate-900">
                                    No EPM records found
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Try changing your search or filter criteria.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($nominations->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $nominations->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
