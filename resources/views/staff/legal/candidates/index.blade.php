@extends('layouts.staff')

@section('title', 'Legal & Compliance — Candidates')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Candidate Records
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Read-only candidate records for legal and compliance review.
        </p>
    </div>

    <form method="GET" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

        <div class="grid gap-4 md:grid-cols-3">

            <div>
                <label class="text-sm font-medium text-slate-700">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Candidate name"
                    class="mt-1 w-full rounded-lg border-slate-300"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">
                    Gender
                </label>

                <select
                    name="gender"
                    class="mt-1 w-full rounded-lg border-slate-300"
                >
                    <option value="">All</option>
                    <option value="Male" @selected(request('gender') === 'Male')>
                        Male
                    </option>
                    <option value="Female" @selected(request('gender') === 'Female')>
                        Female
                    </option>
                </select>
            </div>

            <div class="flex items-end">
                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-700"
                >
                    Filter
                </button>
            </div>

        </div>

    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Party
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Qualification
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse ($candidates as $candidate)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">
                                    {{ $candidate->full_name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $candidate->gender ?? '—' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $candidate->nomination?->politicalParty?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $candidate->nomination?->position?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $candidate->qualification ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('staff.legal.candidates.show', $candidate) }}"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                                >
                                    View
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                No candidate records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($candidates->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $candidates->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
