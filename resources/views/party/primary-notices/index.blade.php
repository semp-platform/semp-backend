@extends('layouts.party')

@section('title', 'Party Primaries | SEMP')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-sm font-medium text-emerald-700">
                Political Party Portal
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                Party Primaries
            </h1>

            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                Submit and track notices of your party's primary elections
                for OGSIEC review and monitoring.
            </p>
        </div>

        <a
            href="{{ route('party.primary-notices.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
        >
            Submit Primary Notice
        </a>

    </div>


    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-950">
                Primary Notices
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Notices submitted by {{ $party->name }}.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Election
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Primary
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Date
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                    @forelse($notices as $notice)

                        @php
                            $statusClasses = match ($notice->status) {
                                'approved' => 'bg-emerald-50 text-emerald-700',
                                'submitted',
                                'received',
                                'under_review' => 'bg-blue-50 text-blue-700',
                                'returned' => 'bg-amber-50 text-amber-700',
                                'rejected',
                                'cancelled' => 'bg-red-50 text-red-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp

                        <tr>

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $notice->election?->name ?? '—' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Notice #{{ $notice->id }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $notice->position?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm capitalize text-slate-700">
                                {{ $notice->primary_type }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ optional($notice->scheduled_date)->format('d M Y') }}

                                @if($notice->scheduled_time)
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $notice->scheduled_time }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm">

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ str_replace('_', ' ', ucfirst($notice->status)) }}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('party.primary-notices.show', $notice) }}"
                                    class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-14 text-center">

                                <p class="font-semibold text-slate-900">
                                    No primary notices
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Submit your party's primary notice to begin the
                                    OGSIEC review process.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($notices->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $notices->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
