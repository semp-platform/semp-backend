@extends('layouts.staff')

@section('title', 'ICT - Nomination Review')

@section('content')

<div class="mb-8 flex items-start justify-between">

    <div>
        <p class="text-sm font-medium text-slate-500">
            ICT Nomination Review
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-900">
            {{ $batch->batch_number }}
        </h1>

        <p class="mt-2 text-sm text-slate-600">
            {{ $batch->politicalParty?->name ?? '—' }}
            ·
            {{ $batch->election?->name ?? '—' }}
        </p>
    </div>

    <div class="rounded-lg bg-amber-100 px-3 py-2 text-sm font-medium text-amber-800">
        {{ str_replace('_', ' ', ucfirst($batch->status)) }}
    </div>

</div>

<div class="space-y-6">

    @forelse($batch->nominations as $nomination)

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                <div>

                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ $nomination->candidate_name }}
                    </h2>

                    <div class="mt-3 space-y-1 text-sm text-slate-600">

                        <p>
                            <span class="font-medium text-slate-700">
                                Position:
                            </span>
                            {{ $nomination->position?->name ?? '—' }}
                        </p>

                        <p>
                            <span class="font-medium text-slate-700">
                                Electoral Area:
                            </span>
                            {{ $nomination->electoral_area }}
                        </p>

                        <p>
                            <span class="font-medium text-slate-700">
                                Department:
                            </span>

                            <span class="font-semibold">
                                {{ strtoupper($nomination->current_department ?? '—') }}
                            </span>
                        </p>

                        <p>
                            <span class="font-medium text-slate-700">
                                Workflow:
                            </span>

                            {{ str_replace('_', ' ', ucfirst($nomination->workflow_status)) }}
                        </p>

                    </div>

                </div>

                @if($nomination->current_department === \App\Models\Nomination\Nomination::DEPARTMENT_ICT)

                    <div class="flex flex-col gap-3 sm:flex-row">

                        {{-- Forward to EDP --}}
                        <form
                            method="POST"
                            action="{{ route('staff.ict.forward-to-edp', $nomination) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                            >
                                Forward to EDP
                            </button>
                        </form>

                        {{-- Return to Party --}}
                        <button
                            type="button"
                            onclick="document.getElementById('return-modal-{{ $nomination->id }}').classList.remove('hidden')"
                            class="w-full rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50"
                        >
                            Return to Party
                        </button>

                    </div>

                @endif

            </div>

            {{-- Workflow history --}}
            @if($nomination->workflowHistories->count())

                <div class="mt-6 border-t border-slate-200 pt-6">

                    <h3 class="text-sm font-semibold text-slate-900">
                        Workflow History
                    </h3>

                    <div class="mt-3 space-y-3">

                        @foreach($nomination->workflowHistories as $history)

                            <div class="rounded-lg bg-slate-50 p-4">

                                <div class="flex flex-wrap items-center gap-2 text-sm">

                                    <span class="font-medium">
                                        {{ strtoupper($history->from_department ?? 'PARTY') }}
                                    </span>

                                    <span class="text-slate-400">
                                        →
                                    </span>

                                    <span class="font-medium">
                                        {{ strtoupper($history->to_department ?? 'COMPLETED') }}
                                    </span>

                                    <span class="text-slate-400">
                                        ·
                                    </span>

                                    <span class="text-slate-500">
                                        {{ $history->created_at?->format('d M Y H:i') }}
                                    </span>

                                </div>

                                @if($history->comment)

                                    <p class="mt-2 text-sm text-slate-600">
                                        {{ $history->comment }}
                                    </p>

                                @endif

                                @if($history->reason)

                                    <p class="mt-2 text-sm text-red-700">
                                        <span class="font-semibold">
                                            Reason:
                                        </span>
                                        {{ $history->reason }}
                                    </p>

                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

       
    @empty

        <div class="rounded-xl bg-white p-12 text-center shadow-sm ring-1 ring-slate-200">

            <p class="text-slate-500">
                This batch has no nominations.
            </p>

        </div>

    @endforelse

</div>

@endsection
