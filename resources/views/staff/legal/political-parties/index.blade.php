@extends('layouts.staff')

@section('title', 'Legal & Compliance — Political Parties')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Political Parties
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Registered political parties and associated nomination records.
        </p>
    </div>

    <form method="GET" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

        <div class="flex gap-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search party name or acronym"
                class="flex-1 rounded-lg border-slate-300"
            >

            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white"
            >
                Search
            </button>

        </div>

    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Party
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Acronym
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse ($politicalParties as $party)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $party->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $party->acronym ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $party->is_active
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-slate-100 text-slate-600' }}">
                                    {{ $party->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('staff.legal.political-parties.show', $party) }}"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white"
                                >
                                    View
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                No political parties found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($politicalParties->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $politicalParties->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
