@extends('layouts.staff')

@section('title', 'Primary Monitoring Record')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    <div class="flex items-start justify-between gap-4">

        <div>

            <a
                href="{{ route('staff.epm.primary-monitoring.index') }}"
                class="text-sm font-medium text-indigo-600 hover:underline"
            >
                ← Primary Monitoring
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-900">
                Primary Monitoring Record
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Event #{{ $event->id }}
            </p>

        </div>

        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700">
            {{ str_replace('_', ' ', ucfirst($event->status)) }}
        </span>

    </div>


    @if(session('success'))

        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>

    @endif


    <div class="grid gap-6 lg:grid-cols-3">

        <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                Primary Details
            </h2>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2">

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Election
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->election?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Political Party
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->politicalParty?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Position
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->position?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Primary Type
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ ucfirst($event->primary_type) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Scheduled Date
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->scheduled_date?->format('d M Y') ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Scheduled Time
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->scheduled_time ?? '—' }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Venue
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->venue ?? '—' }}
                    </dd>
                </div>

            </dl>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-900">
                Party Notice
            </h2>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between gap-4">

        <div>
            <h2 class="font-semibold text-slate-900">
                Monitoring Assignment
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Assign an EPM officer to monitor this political party primary.
            </p>
        </div>

        @if($event->currentAssignment)
            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                Monitor Assigned
            </span>
        @endif

    </div>


    @if($event->currentAssignment)

        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                Current Monitor
            </p>

            <p class="mt-1 text-base font-semibold text-slate-900">
                {{ $event->currentAssignment->monitor?->name ?? 'Unknown monitor' }}
            </p>

            <p class="mt-1 text-sm text-slate-600">
                Assigned
                {{ $event->currentAssignment->assigned_at?->format('d M Y H:i') ?? '—' }}
            </p>

            @if($event->currentAssignment->instructions)

                <div class="mt-4 border-t border-emerald-200 pt-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                        Instructions
                    </p>

                    <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">
                        {{ $event->currentAssignment->instructions }}
                    </p>

                </div>

            @endif

        </div>

    @else

        <form
            method="POST"
            action="{{ route('staff.epm.primary-monitoring.assign-monitor', $event) }}"
            class="mt-6 space-y-5"
        >

            @csrf

            <div>

                <label class="block text-sm font-medium text-slate-700">
                    EPM Monitor
                </label>

                <select
                    name="monitor_id"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >

                    <option value="">
                        Select EPM officer
                    </option>

                    @foreach($monitors as $monitor)

                        <option
                            value="{{ $monitor->id }}"
                            @selected(old('monitor_id') == $monitor->id)
                        >
                            {{ $monitor->name }}
                            — {{ $monitor->email }}
                        </option>

                    @endforeach

                </select>

                @error('monitor_id')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div>

                <label class="block text-sm font-medium text-slate-700">
                    Monitoring Instructions
                </label>

                <textarea
                    name="instructions"
                    rows="4"
                    maxlength="5000"
                    placeholder="Enter any specific instructions for the assigned monitor..."
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >{{ old('instructions') }}</textarea>

                @error('instructions')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div class="flex justify-end">

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Assign Monitor
                </button>

            </div>

        </form>

    @endif

</div>
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <h2 class="font-semibold text-slate-900">
        Assignment History
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        Previous and current EPM monitor assignments for this primary.
    </p>

    <div class="mt-5 divide-y divide-slate-200">

        @forelse($event->assignments as $assignment)

            <div class="py-4 first:pt-0 last:pb-0">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">

                    <div>

                        <p class="font-medium text-slate-900">
                            {{ $assignment->monitor?->name ?? 'Unknown monitor' }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Assigned by
                            {{ $assignment->assignedBy?->name ?? 'System' }}
                            on
                            {{ $assignment->assigned_at?->format('d M Y H:i') ?? '—' }}
                        </p>

                    </div>

                    <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                        {{ ucfirst($assignment->status) }}
                    </span>

                </div>

                @if($assignment->instructions)

                    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                        {{ $assignment->instructions }}
                    </p>

                @endif

            </div>

        @empty

            <p class="py-5 text-sm text-slate-500">
                No monitor has been assigned yet.
            </p>

        @endforelse

    </div>

</div>

            <dl class="mt-5 space-y-5">

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Status
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ ucfirst($event->notice_status) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Received
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-slate-900">
                        {{ $event->notice_received_at?->format('d M Y H:i') ?? 'Not recorded' }}
                    </dd>
                </div>

            </dl>

        </div>

    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="font-semibold text-slate-900">
            Electoral Area
        </h2>

        <dl class="mt-5 grid gap-5 sm:grid-cols-3">

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    LGA
                </dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">
                    {{ $event->lga?->name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    LCDA
                </dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">
                    {{ $event->lcda?->name ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Ward
                </dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">
                    {{ $event->ward?->name ?? '—' }}
                </dd>
            </div>

        </dl>

    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between gap-4">

        <div>
            <h2 class="font-semibold text-slate-900">
                Monitoring Workflow
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                Assign an EPM officer to monitor this primary event.
            </p>
        </div>

        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
            @if($event->status === \App\Models\PrimaryEvent::STATUS_MONITOR_ASSIGNED)
                bg-indigo-100 text-indigo-700
            @elseif($event->status === \App\Models\PrimaryEvent::STATUS_MONITORING)
                bg-blue-100 text-blue-700
            @elseif($event->status === \App\Models\PrimaryEvent::STATUS_COMPLETED)
                bg-green-100 text-green-700
            @else
                bg-slate-100 text-slate-700
            @endif
        ">
            {{ str_replace('_', ' ', ucfirst($event->status)) }}
        </span>

    </div>


    @php
        $activeAssignment = $event->assignments
            ->firstWhere('status', 'assigned');
    @endphp


    @if($activeAssignment)

        <div class="mt-6 rounded-lg border border-indigo-200 bg-indigo-50 p-5">

            <h3 class="text-sm font-semibold text-indigo-900">
                Current Monitor
            </h3>

            <dl class="mt-4 grid gap-4 sm:grid-cols-2">

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-indigo-700">
                        Monitor
                    </dt>

                    <dd class="mt-1 text-sm font-medium text-indigo-950">
                        {{ $activeAssignment->monitor?->name ?? 'Unknown monitor' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-indigo-700">
                        Assigned At
                    </dt>

                    <dd class="mt-1 text-sm font-medium text-indigo-950">
                        {{ $activeAssignment->assigned_at?->format('d M Y, H:i') ?? '—' }}
                    </dd>
                </div>

            </dl>


            @if($activeAssignment->instructions)

                <div class="mt-4">

                    <dt class="text-xs font-semibold uppercase tracking-wide text-indigo-700">
                        Instructions
                    </dt>

                    <dd class="mt-1 whitespace-pre-line text-sm text-indigo-950">
                        {{ $activeAssignment->instructions }}
                    </dd>

                </div>

            @endif

        </div>

    @endif


    @can('primary-monitoring.assign')

        <form
            method="POST"
            action="{{ route(
                'staff.epm.primary-monitoring.assign-monitor',
                $event
            ) }}"
            class="mt-6 space-y-5"
        >

            @csrf

            <div>

                <label
                    for="monitor_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    EPM Monitor
                </label>

                <select
                    id="monitor_id"
                    name="monitor_id"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        Select an EPM officer
                    </option>

                    @foreach($monitors as $monitor)

                        <option
                            value="{{ $monitor->id }}"
                            @selected(
                                old('monitor_id') ==
                                ($activeAssignment?->monitor_id ?? null)
                            )
                        >
                            {{ $monitor->name }}
                            @if($monitor->email)
                                — {{ $monitor->email }}
                            @endif
                        </option>

                    @endforeach

                </select>

                @error('monitor_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div>

                <label
                    for="instructions"
                    class="block text-sm font-medium text-slate-700"
                >
                    Instructions
                </label>

                <textarea
                    id="instructions"
                    name="instructions"
                    rows="5"
                    maxlength="5000"
                    class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Enter any instructions for the assigned monitor..."
                >{{ old('instructions', $activeAssignment?->instructions) }}</textarea>

                @error('instructions')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div class="flex justify-end">

                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    {{ $activeAssignment ? 'Reassign Monitor' : 'Assign Monitor' }}
                </button>

            </div>

        </form>

    @endcan


    @if($event->assignments->isNotEmpty())

        <div class="mt-8 border-t border-slate-200 pt-6">

            <h3 class="text-sm font-semibold text-slate-900">
                Assignment History
            </h3>

            <div class="mt-4 overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead>
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Monitor
                            </th>

                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Assigned
                            </th>

                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Status
                            </th>

                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Instructions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($event->assignments as $assignment)

                            <tr>

                                <td class="px-3 py-3 text-sm font-medium text-slate-900">
                                    {{ $assignment->monitor?->name ?? 'Unknown monitor' }}
                                </td>

                                <td class="px-3 py-3 text-sm text-slate-600">
                                    {{ $assignment->assigned_at?->format('d M Y, H:i') ?? '—' }}
                                </td>

                                <td class="px-3 py-3">

                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        @if($assignment->status === 'assigned')
                                            bg-green-100 text-green-700
                                        @elseif($assignment->status === 'reassigned')
                                            bg-amber-100 text-amber-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif
                                    ">
                                        {{ ucfirst($assignment->status) }}
                                    </span>

                                </td>

                                <td class="max-w-md px-3 py-3 text-sm text-slate-600">
                                    {{ $assignment->instructions ?: '—' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif
{{-- Monitoring Report --}}
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-6">
        <p class="text-sm font-medium text-indigo-600">
            Monitoring Report
        </p>

        <h2 class="mt-1 text-lg font-bold text-slate-900">
            Primary Event Report
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Record the outcome and observations from the primary event.
        </p>
    </div>

    <form
    method="POST"
    action="{{ route('staff.epm.primary-monitoring.submit-report', $event) }}"
    enctype="multipart/form-data"
    class="space-y-6"
>
        @csrf

        <div class="grid gap-6 md:grid-cols-3">

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Attendance Status
                </label>

                <select
                    name="attendance_status"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >
                    <option value="">Select status</option>
                    <option value="orderly">Orderly</option>
                    <option value="disrupted">Disrupted</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Accredited Voters
                </label>

                <input
                    type="number"
                    name="accredited_voters"
                    min="0"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Votes Cast
                </label>

                <input
                    type="number"
                    name="votes_cast"
                    min="0"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                >
            </div>

        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">
                Observations
            </label>

            <textarea
                name="observations"
                rows="5"
                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                placeholder="Record important observations from the primary..."
            ></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">
                Recommendations
            </label>

            <textarea
                name="recommendations"
                rows="4"
                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm"
                placeholder="Enter any recommendations..."
            ></textarea>
        </div>
<div class="grid gap-6 md:grid-cols-2">

    {{-- Photos --}}
    <div>
        <label class="block text-sm font-medium text-slate-700">
            Photos
        </label>

        <p class="mt-1 text-xs text-slate-500">
            Upload photos taken during the primary event.
            JPG, JPEG, PNG or WEBP. Maximum 10 files.
        </p>

        <input
            type="file"
            name="photos[]"
            multiple
            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            class="mt-3 block w-full rounded-lg border border-slate-300 bg-white
                   px-3 py-2 text-sm text-slate-700 shadow-sm
                   file:mr-4 file:rounded-md file:border-0
                   file:bg-slate-100 file:px-3 file:py-2
                   file:text-sm file:font-medium"
        >
    </div>

    {{-- Supporting Documents --}}
    <div>
        <label class="block text-sm font-medium text-slate-700">
            Supporting Documents
        </label>

        <p class="mt-1 text-xs text-slate-500">
            Upload supporting documents related to the monitoring report.
            PDF, DOC or DOCX. Maximum 10 files.
        </p>

        <input
            type="file"
            name="documents[]"
            multiple
            accept=".pdf,.doc,.docx,application/pdf"
            class="mt-3 block w-full rounded-lg border border-slate-300 bg-white
                   px-3 py-2 text-sm text-slate-700 shadow-sm
                   file:mr-4 file:rounded-md file:border-0
                   file:bg-slate-100 file:px-3 file:py-2
                   file:text-sm file:font-medium"
        >
    </div>
{{-- Uploaded Evidence --}}
@if(isset($report) && $report->attachments->isNotEmpty())

    <div class="border-t border-slate-200 pt-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Uploaded Evidence
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Photos and supporting documents attached to this monitoring report.
            </p>
        </div>

        @php
            $photos = $report->attachments->where('category', 'photo');
            $documents = $report->attachments->where('category', 'document');
        @endphp

        {{-- Photos --}}
        @if($photos->isNotEmpty())

            <div class="mt-5">

                <h4 class="text-sm font-medium text-slate-700">
                    Photos
                </h4>

                <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach($photos as $photo)

                        <a
                            href="{{ Storage::disk('public')->url($photo->file_path) }}"
                            target="_blank"
                            class="group overflow-hidden rounded-lg border border-slate-200 bg-white"
                        >

                            <img
                                src="{{ Storage::disk('public')->url($photo->file_path) }}"
                                alt="{{ $photo->original_name }}"
                                class="h-40 w-full object-cover transition group-hover:scale-105"
                            >

                            <div class="p-2">

                                <p class="truncate text-xs font-medium text-slate-700">
                                    {{ $photo->original_name }}
                                </p>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        @endif

        {{-- Documents --}}
        @if($documents->isNotEmpty())

            <div class="mt-6">

                <h4 class="text-sm font-medium text-slate-700">
                    Supporting Documents
                </h4>

                <div class="mt-3 space-y-2">

                    @foreach($documents as $document)

                        <a
                            href="{{ Storage::disk('public')->url($document->file_path) }}"
                            target="_blank"
                            class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100"
                        >

                            <div class="min-w-0">

                                <p class="truncate text-sm font-medium text-slate-800">
                                    {{ $document->original_name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ number_format($document->file_size / 1024, 1) }} KB
                                </p>

                            </div>

                            <span class="ml-4 shrink-0 text-sm font-semibold text-indigo-600">
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
        <div class="flex justify-end">
            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Submit Monitoring Report
            </button>
        </div>

    </form>

</div>
</div>

</div>

@endsection
