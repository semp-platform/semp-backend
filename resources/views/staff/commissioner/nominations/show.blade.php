@'
@extends('layouts.staff')

@section('content')

<div class="mb-6">
    <a
        href="{{ route('staff.commissioner.nominations.index') }}"
        class="text-sm font-medium text-slate-600 hover:text-slate-900"
    >
        ← Back to Commissioner Nominations
    </a>
</div>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">
        Commissioner Review
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Review the screening recommendations before making the final decision.
    </p>
</div>

{{-- Candidate summary --}}
<div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Candidate
            </p>
            <p class="mt-1 text-lg font-semibold text-slate-900">
                {{ $nomination->candidate_name }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Political Party
            </p>
            <p class="mt-1 text-slate-900">
                {{ $nomination->politicalParty?->name ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Election
            </p>
            <p class="mt-1 text-slate-900">
                {{ $nomination->election_type ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Position
            </p>
            <p class="mt-1 text-slate-900">
                {{ $nomination->position?->name ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Electoral Area
            </p>
            <p class="mt-1 text-slate-900">
                {{ $nomination->electoral_area ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Current Department
            </p>
            <p class="mt-1 font-semibold text-slate-900">
                Commissioner
            </p>
        </div>

    </div>
</div>

{{-- Screening history --}}
<div class="mb-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

    <div class="border-b border-slate-200 px-6 py-4">
        <h2 class="font-semibold text-slate-900">
            Screening & Workflow History
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            The Commissioner may approve or return the nomination based on the recommendations recorded by the screening departments.
        </p>
    </div>

    @if ($nomination->workflowHistories->isNotEmpty())

        <div class="divide-y divide-slate-200">

            @foreach ($nomination->workflowHistories->sortBy('created_at') as $history)

                <div class="px-6 py-5">

                    <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">

                        <div>
                            <p class="font-medium text-slate-900">
                                {{ ucfirst($history->action) }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $history->from_department
                                    ? strtoupper($history->from_department)
                                    : 'Submission' }}

                                →

                                {{ $history->to_department
                                    ? strtoupper($history->to_department)
                                    : 'Completed / Returned' }}
                            </p>
                        </div>

                        <p class="text-sm text-slate-500">
                            {{ $history->created_at?->format('d M Y, H:i') }}
                        </p>

                    </div>

                    @if ($history->comment)
                        <div class="mt-3 rounded-lg bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Recommendation / Comment
                            </p>

                            <p class="mt-1 text-sm text-slate-700">
                                {{ $history->comment }}
                            </p>
                        </div>
                    @endif

                    @if ($history->reason)
                        <div class="mt-3 rounded-lg bg-amber-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">
                                Reason
                            </p>

                            <p class="mt-1 text-sm text-amber-900">
                                {{ $history->reason }}
                            </p>
                        </div>
                    @endif

                </div>

            @endforeach

        </div>

    @else

        <div class="px-6 py-10 text-center text-sm text-slate-500">
            No workflow history is available.
        </div>

    @endif

</div>

{{-- Commissioner decision --}}
<div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

    <div class="mb-5">
        <h2 class="text-lg font-semibold text-slate-900">
            Commissioner Decision
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            The Commissioner cannot reject a candidate. A nomination may only be approved or returned in accordance with the recommendations from the screening departments.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- Approve --}}
        <form
            method="POST"
            action="{{ route('staff.commissioner.nominations.approve', $nomination) }}"
            class="rounded-xl border border-emerald-200 bg-emerald-50 p-5"
        >
            @csrf

            <h3 class="font-semibold text-emerald-900">
                Approve Nomination
            </h3>

            <p class="mt-1 text-sm text-emerald-800">
                Confirm that the nomination may proceed.
            </p>

            <label class="mt-4 block text-sm font-medium text-emerald-900">
                Comment
            </label>

            <textarea
                name="comment"
                rows="4"
                class="mt-1 block w-full rounded-lg border-emerald-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="Optional approval comment"
            ></textarea>

            <button
                type="submit"
                class="mt-4 inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                onclick="return confirm('Approve this nomination?')"
            >
                Approve Nomination
            </button>

        </form>

        {{-- Return --}}
        <form
            method="POST"
            action="{{ route('staff.commissioner.nominations.return', $nomination) }}"
            class="rounded-xl border border-amber-200 bg-amber-50 p-5"
        >
            @csrf

            <h3 class="font-semibold text-amber-900">
                Return for Further Action
            </h3>

            <p class="mt-1 text-sm text-amber-800">
                Return the nomination based on the screening department's recommendation.
            </p>

            <label class="mt-4 block text-sm font-medium text-amber-900">
                Recommendation / Reason
            </label>

            <textarea
                name="reason"
                rows="4"
                required
                class="mt-1 block w-full rounded-lg border-amber-300 bg-white text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500"
                placeholder="State the screening recommendation supporting the return"
            ></textarea>

            <label class="mt-4 block text-sm font-medium text-amber-900">
                Additional Comment
            </label>

            <textarea
                name="comment"
                rows="3"
                class="mt-1 block w-full rounded-lg border-amber-300 bg-white text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500"
                placeholder="Optional additional comment"
            ></textarea>

            <button
                type="submit"
                class="mt-4 inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700"
                onclick="return confirm('Return this nomination based on the screening recommendation?')"
            >
                Return Nomination
            </button>

        </form>

    </div>

</div>

@endsection
