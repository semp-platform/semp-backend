@extends('layouts.party')

@section('title', 'Primary Notice | SEMP')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    <div>

        <a
            href="{{ route('party.primary-notices.index') }}"
            class="text-sm font-medium text-emerald-700 hover:underline"
        >
            ← Back to Party Primaries
        </a>

        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div>

                <p class="text-sm font-medium text-emerald-700">
                    Primary Notice #{{ $notice->id }}
                </p>

                <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                    {{ $notice->election?->name ?? 'Primary Notice' }}
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $notice->position?->name ?? '—' }}
                </p>

            </div>

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

            <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusClasses }}">
                {{ str_replace('_', ' ', ucfirst($notice->status)) }}
            </span>

        </div>

    </div>


    @if(session('success'))

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>

    @endif


    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-950">
                Primary Details
            </h2>

        </div>

        <div class="grid gap-6 px-6 py-6 sm:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Primary Type
                </p>

                <p class="mt-1 text-sm font-medium capitalize text-slate-900">
                    {{ $notice->primary_type }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Election
                </p>

                <p class="mt-1 text-sm font-medium text-slate-900">
                    {{ $notice->election?->name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Position
                </p>

                <p class="mt-1 text-sm font-medium text-slate-900">
                    {{ $notice->position?->name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Date
                </p>

                <p class="mt-1 text-sm font-medium text-slate-900">
                    {{ optional($notice->scheduled_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Time
                </p>

                <p class="mt-1 text-sm font-medium text-slate-900">
                    {{ $notice->scheduled_time ?? 'Not specified' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Venue
                </p>

                <p class="mt-1 text-sm font-medium text-slate-900">
                    {{ $notice->venue ?? '—' }}
                </p>
            </div>

        </div>

    </div>




    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-950">
                Submission
            </h2>

        </div>

        <div class="space-y-4 px-6 py-6 text-sm">

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
                <span class="text-slate-500">
                    Submitted by
                </span>

                <span class="font-medium text-slate-900">
                    {{ $notice->submitter?->name ?? '—' }}
                </span>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
                <span class="text-slate-500">
                    Submitted at
                </span>

                <span class="font-medium text-slate-900">
                    {{ optional($notice->submitted_at)->format('d M Y H:i') ?? '—' }}
                </span>
            </div>

            @if($notice->reviewed_at)

                <div class="border-t border-slate-100 pt-4">

                    <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">

                        <span class="text-slate-500">
                            Reviewed at
                        </span>

                        <span class="font-medium text-slate-900">
                            {{ $notice->reviewed_at->format('d M Y H:i') }}
                        </span>

                    </div>



                </div>

            @endif

        </div>

    </div>

</div>

@endsection
