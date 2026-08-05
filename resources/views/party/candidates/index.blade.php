@extends('layouts.party')

@section('title', 'Candidate Documents')

@section('content')

<div class="space-y-6">

    <div>

        <h1 class="text-2xl font-bold">
            Candidate Documents
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Manage supporting documents for party candidates.
        </p>

    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Candidate
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Position
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Electoral Area
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Status
                    </th>

                    <th class="px-6 py-3"></th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($nominations as $nomination)

                    @php

                        $uploaded = $nomination->candidate
                            ? $nomination->candidate->documents
                                ->whereIn('document_type_id', $requiredDocumentIds)
                                ->count()
                            : 0;

                        $complete = ($uploaded >= $requiredDocuments);

                    @endphp

                    <tr>

                        <td class="px-6 py-4">

                            <div class="font-medium text-slate-900">
                                {{ $nomination->candidate_name }}
                            </div>

                        </td>

                        <td class="px-6 py-4">

                            {{ $nomination->position?->name }}

                        </td>

                        <td class="px-6 py-4">

                            @if($nomination->ward)

                                <div>{{ $nomination->lga?->name }}</div>

                                <div class="text-sm text-slate-500">
                                    {{ $nomination->ward->name }}
                                </div>

                            @elseif($nomination->lga)

                                {{ $nomination->lga->name }}

                            @else

                                Statewide

                            @endif

                        </td>

                        <td class="px-6 py-4">

                            @if($complete)

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Complete
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                    Incomplete
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route('party.candidates.documents.index', $nomination->candidate) }}"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                            >
                                Manage
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">

                            No nominations found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
