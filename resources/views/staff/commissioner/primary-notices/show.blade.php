@extends('layouts.staff')

@section('title', 'Review Party Primary Notice')

@section('content')

<div class="mx-auto max-w-5xl">

    <a
        href="{{ route('staff.commissioner.primary-notices.index') }}"
        class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
    >
        ← Party Primary Notices
    </a>


    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Party Primary Notice #{{ $notice->id }}
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                {{ $notice->politicalParty?->name ?? 'Political Party' }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Submitted primary notice for Commissioner review.
            </p>

        </div>


        <span class="inline-flex w-fit rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">
            {{ ucwords(str_replace('_', ' ', $notice->status)) }}
        </span>

    </div>


    @if(session('success'))
        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif


    @if($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Primary details --}}

    <div class="mt-8 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-bold text-slate-950">
                Primary Details
            </h2>
        </div>


        <div class="grid gap-6 px-6 py-6 sm:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Election
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->election?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Position
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->position?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Primary Type
                </p>

                <p class="mt-1 font-semibold capitalize text-slate-950">
                    {{ $notice->primary_type }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Date
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->scheduled_date?->format('d M Y') ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Time
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->scheduled_time ?: 'Not specified' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Venue
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->venue ?: 'Not specified' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Submission --}}

    <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-bold text-slate-950">
                Submission
            </h2>
        </div>


        <div class="grid gap-6 px-6 py-6 sm:grid-cols-2">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Submitted By
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->submittedBy?->name ?? '—' }}
                </p>
            </div>


            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Submitted At
                </p>

                <p class="mt-1 font-semibold text-slate-950">
                    {{ $notice->submitted_at?->format('d M Y H:i') ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Commissioner decision --}}

    @if($notice->status === 'submitted')

        <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 shadow-sm">

            <div class="border-b border-amber-200 px-6 py-5">

                <h2 class="font-bold text-amber-950">
                    Commissioner Decision
                </h2>

                <p class="mt-1 text-sm text-amber-800">
                    Approve this primary for EPM monitoring or return it to
                    the political party for correction.
                </p>

            </div>


            <div class="grid gap-6 p-6 lg:grid-cols-2">

                {{-- Approve --}}

                <form
                    method="POST"
                    action="{{ route(
                        'staff.commissioner.primary-notices.approve',
                        $notice
                    ) }}"
                    class="rounded-lg border border-emerald-200 bg-white p-5"
                >
                    @csrf

                    <h3 class="font-bold text-slate-950">
                        Approve for EPM
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        The notice will become available to the EPM department
                        for monitoring preparation.
                    </p>

                    <textarea
                        name="review_comment"
                        rows="4"
                        class="mt-4 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                        placeholder="Optional Commissioner comment"
                    ></textarea>

                    <button
                        type="submit"
                        class="mt-4 w-full rounded-lg bg-emerald-700 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-800"
                    >
                        Approve for EPM
                    </button>

                </form>


                {{-- Return --}}

                <form
                    method="POST"
                    action="{{ route(
                        'staff.commissioner.primary-notices.return',
                        $notice
                    ) }}"
                    class="rounded-lg border border-orange-200 bg-white p-5"
                >
                    @csrf

                    <h3 class="font-bold text-slate-950">
                        Return to Party
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        Send the notice back to the political party for
                        correction or clarification.
                    </p>

                    <textarea
                        name="review_comment"
                        rows="4"
                        required
                        class="mt-4 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                        placeholder="Explain what needs to be corrected"
                    ></textarea>

                    <button
                        type="submit"
                        class="mt-4 w-full rounded-lg bg-orange-600 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-700"
                    >
                        Return to Party
                    </button>

                </form>

            </div>

        </div>

    @endif


    {{-- Previous decision --}}

    @if(
        in_array(
            $notice->status,
            ['approved', 'returned', 'rejected']
        )
    )

        <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="font-bold text-slate-950">
                    Commissioner Review
                </h2>
            </div>

            <div class="space-y-5 px-6 py-6">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Reviewed By
                    </p>

                    <p class="mt-1 font-semibold text-slate-950">
                        {{ $notice->reviewedBy?->name ?? '—' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Reviewed At
                    </p>

                    <p class="mt-1 font-semibold text-slate-950">
                        {{ $notice->reviewed_at?->format('d M Y H:i') ?? '—' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Comment
                    </p>

                    <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">
                        {{ $notice->review_comment ?: 'No comment provided.' }}
                    </p>
                </div>

            </div>

        </div>

    @endif

</div>

@endsection
