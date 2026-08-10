@extends('layouts.staff')

@section('title', 'ICT Batch Review')

@section('content')

<div class="space-y-6">

    {{-- Page heading --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                {{ $batch->batch_number }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                ICT batch intake and nomination review.
            </p>
        </div>

        <a
            href="{{ route('staff.ict.nomination-batches.index') }}"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
        >
            Back
        </a>

    </div>


    {{-- Batch information --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">
                Political Party
            </div>

            <div class="mt-2 font-semibold text-slate-900">
                {{ $batch->politicalParty?->name ?? '—' }}
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">
                Election
            </div>

            <div class="mt-2 font-semibold text-slate-900">
                {{ $batch->election?->name ?? '—' }}
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">
                Candidates
            </div>

            <div class="mt-2 font-semibold text-slate-900">
                {{ $batch->candidate_count }}
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">
                Submitted By
            </div>

            <div class="mt-2 font-semibold text-slate-900">
                {{ $batch->submittedBy?->name ?? '—' }}
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">
                Date Submitted
            </div>

            <div class="mt-2 font-semibold text-slate-900">
                {{ $batch->submitted_at?->format('d M Y H:i') ?? '—' }}
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="text-sm text-slate-500">
                Received by ICT
            </div>

            <div class="mt-2 font-semibold text-slate-900">
                {{ $batch->received_at?->format('d M Y H:i') ?? 'Not yet received' }}
            </div>
        </div>

    </div>


    {{-- Batch status --}}
    <div class="rounded-xl bg-white p-6 shadow-sm">

        <div class="flex items-center justify-between gap-4">

            <div>
                <h2 class="font-semibold text-slate-900">
                    Batch Status
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Current status:
                    <span class="font-medium text-slate-900">
                        {{ str_replace('_', ' ', ucfirst($batch->status)) }}
                    </span>
                </p>
            </div>

            @if($batch->status === \App\Models\Nomination\NominationBatch::STATUS_SUBMITTED)

                <form
                    method="POST"
                    action="{{ route('staff.ict.nomination-batches.receive', $batch) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Receive Batch at ICT
                    </button>
                </form>

            @endif

        </div>

    </div>


    {{-- Nominations --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Nominations in this Batch
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Candidate
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Position
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Workflow
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($batch->nominations as $nomination)

                        <tr>

                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $nomination->candidate_name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $nomination->position?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm">

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                    {{ str_replace('_', ' ', ucfirst($nomination->workflow_status ?? 'pending')) }}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-right">

                                @if($nomination->current_department === \App\Models\Nomination\Nomination::DEPARTMENT_ICT)

                                    <a
                                        href="{{ route('staff.ict.nominations.show', $nomination) }}"
                                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                                    >
                                        Open
                                    </a>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Awaiting ICT intake
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-sm text-slate-500"
                            >
                                No nominations found in this batch.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
