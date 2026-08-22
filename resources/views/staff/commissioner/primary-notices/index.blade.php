@extends('layouts.staff')

@section('title', 'Party Primary Notices')

@section('content')

<div class="mx-auto max-w-7xl">

    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
            Commissioner
        </p>

        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
            Party Primary Notices
        </h1>

        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
            Review primary notices submitted by political parties and
            approve those that may proceed to EPM for monitoring.
        </p>
    </div>


    @if(session('success'))
        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif


    <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-bold text-slate-950">
                Submitted Primary Notices
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Party primary events requiring Commissioner review.
            </p>
        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200">

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Party
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Election / Position
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Primary Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Venue
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

                    @forelse($notices as $notice)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-950">
                                    {{ $notice->politicalParty?->name ?? '—' }}
                                </p>
                            </td>


                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-900">
                                    {{ $notice->election?->name ?? '—' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $notice->position?->name ?? '—' }}
                                </p>
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $notice->scheduled_date?->format('d M Y') ?? '—' }}

                                @if($notice->scheduled_time)
                                    <div class="text-xs text-slate-500">
                                        {{ $notice->scheduled_time }}
                                    </div>
                                @endif
                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $notice->venue ?: '—' }}
                            </td>


                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    @class([
                                        'bg-amber-50 text-amber-800' =>
                                            $notice->status === 'submitted',

                                        'bg-emerald-50 text-emerald-800' =>
                                            $notice->status === 'approved',

                                        'bg-orange-50 text-orange-800' =>
                                            $notice->status === 'returned',

                                        'bg-red-50 text-red-800' =>
                                            $notice->status === 'rejected',

                                        'bg-slate-100 text-slate-700' =>
                                            ! in_array(
                                                $notice->status,
                                                [
                                                    'submitted',
                                                    'approved',
                                                    'returned',
                                                    'rejected',
                                                ]
                                            ),
                                    ])
                                >
                                    {{ ucwords(str_replace('_', ' ', $notice->status)) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route(
                                        'staff.commissioner.primary-notices.show',
                                        $notice
                                    ) }}"
                                    class="inline-flex rounded-lg bg-slate-950 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                >
                                    Review
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-sm text-slate-500"
                            >
                                No party primary notices are currently available.
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
