@extends('layouts.staff')

@section('title', 'Political Party — Legal & Compliance')

@section('content')

<div class="space-y-6">

    <div>
        <a
            href="{{ route('staff.legal.political-parties.index') }}"
            class="text-sm font-medium text-emerald-700"
        >
            ← Back to Political Parties
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-900">
            {{ $politicalParty->name }}
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            {{ $politicalParty->acronym ?? 'No acronym recorded' }}
        </p>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <h2 class="font-semibold text-slate-900">
            Party Information
        </h2>

        <dl class="mt-5 grid gap-5 sm:grid-cols-3">

            <div>
                <dt class="text-sm text-slate-500">Name</dt>
                <dd class="mt-1 font-medium text-slate-900">
                    {{ $politicalParty->name }}
                </dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Acronym</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $politicalParty->acronym ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm text-slate-500">Status</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $politicalParty->is_active ? 'Active' : 'Inactive' }}
                </dd>
            </div>

        </dl>

    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Associated Nominations
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Election
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse ($nominations as $nomination)

                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $nomination->candidate?->full_name
                                    ?? $nomination->candidate_name
                                    ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $nomination->position?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $nomination->election?->name ?? '—' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">
                                No nominations are associated with this party.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($nominations->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $nominations->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
