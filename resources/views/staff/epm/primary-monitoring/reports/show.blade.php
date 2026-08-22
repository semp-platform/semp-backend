@extends('layouts.staff')

@section('title', 'Primary Monitoring Report')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <a
                href="{{ route('staff.epm.primary-monitoring.reports.index') }}"
                class="text-sm font-medium text-indigo-600 hover:underline"
            >
                ← Back to Monitoring Reports
            </a>

            <h1 class="mt-2 text-2xl font-bold text-slate-900">
                Primary Monitoring Report
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Submitted monitoring report for the primary event.
            </p>
        </div>

        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">
            Submitted
        </span>

    </div>


    {{-- Event Information --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Primary Event Information
        </h2>

        <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Political Party
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $event->politicalParty?->name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Position
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $event->position?->name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Date
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    @if($event->scheduled_date)
                        {{ \Carbon\Carbon::parse($event->scheduled_date)->format('d M Y') }}
                    @else
                        —
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Venue
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $event->venue ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    LGA
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $event->lga?->name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Ward
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $event->ward?->name ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Monitor Information --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Monitoring Officer
        </h2>

        <div class="mt-4 grid gap-6 sm:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Officer
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $report->monitor?->name ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Submitted
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    @if($report->submitted_at)
                        {{ $report->submitted_at->format('d M Y H:i') }}
                    @else
                        —
                    @endif
                </p>
            </div>

        </div>

    </div>


    {{-- Report Outcome --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Event Outcome
        </h2>

        <div class="mt-5 grid gap-6 sm:grid-cols-3">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Attendance Status
                </p>

                <p class="mt-1 font-medium capitalize text-slate-900">
                    {{ $report->attendance_status ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Accredited Voters
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $report->accredited_voters ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Votes Cast
                </p>

                <p class="mt-1 font-medium text-slate-900">
                    {{ $report->votes_cast ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Observations --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Observations
        </h2>

        <div class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">
            {{ $report->observations ?: 'No observations provided.' }}
        </div>

    </div>


    {{-- Recommendations --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Recommendations
        </h2>

        <div class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">
            {{ $report->recommendations ?: 'No recommendations provided.' }}
        </div>

    </div>


    {{-- Attachments --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Supporting Evidence
        </h2>

        @if($report->attachments->isEmpty())

            <p class="mt-4 text-sm text-slate-500">
                No photographs or supporting documents were uploaded.
            </p>

        @else

            <div class="mt-5 space-y-8">

                {{-- Photos --}}
                @php
                    $photos = $report->attachments
                        ->where('category', 'photo');
                @endphp

                @if($photos->isNotEmpty())

                    <div>

                        <h3 class="text-sm font-semibold text-slate-900">
                            Photographs
                        </h3>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach($photos as $photo)

                                <a
                                    href="{{ asset('storage/' . $photo->file_path) }}"
                                    target="_blank"
                                    class="group overflow-hidden rounded-lg border border-slate-200 bg-slate-50"
                                >

                                    <img
                                        src="{{ asset('storage/' . $photo->file_path) }}"
                                        alt="{{ $photo->original_name }}"
                                        class="h-48 w-full object-cover transition group-hover:scale-105"
                                    >

                                    <div class="p-3">

                                        <p class="truncate text-sm font-medium text-slate-700">
                                            {{ $photo->original_name }}
                                        </p>

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- Documents --}}
                @php
                    $documents = $report->attachments
                        ->where('category', 'document');
                @endphp

                @if($documents->isNotEmpty())

                    <div>

                        <h3 class="text-sm font-semibold text-slate-900">
                            Supporting Documents
                        </h3>

                        <div class="mt-4 divide-y divide-slate-200 rounded-lg border border-slate-200">

                            @foreach($documents as $document)

                                <a
                                    href="{{ asset('storage/' . $document->file_path) }}"
                                    target="_blank"
                                    class="flex items-center justify-between gap-4 px-4 py-3 hover:bg-slate-50"
                                >

                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-medium text-slate-900">
                                            {{ $document->original_name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ number_format($document->file_size / 1024, 1) }} KB
                                        </p>

                                    </div>

                                    <span class="shrink-0 text-sm font-medium text-indigo-600">
                                        View
                                    </span>

                                </a>

                            @endforeach

                        </div>

                    </div>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection
