@extends('layouts.party')

@section('title', 'Party Dashboard | SEMP')

@section('content')

<div class="mb-8">

    <p class="text-sm font-medium text-emerald-700">
        Political Party Portal
    </p>

    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
        Dashboard
    </h1>

    <p class="mt-2 text-sm text-slate-500">
        Manage candidate nominations and election requirements for
        {{ $party->name }}.
    </p>

</div>


{{-- Summary cards --}}
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

    {{-- Draft --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Draft Nominations
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-950">
            {{ $draftNominations }}
        </p>

        <p class="mt-2 text-xs text-slate-500">
            Nominations still being prepared
        </p>

    </div>


    {{-- Submitted --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Submitted
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-950">
            {{ $submittedNominations }}
        </p>

        <p class="mt-2 text-xs text-slate-500">
            Submitted or currently under review
        </p>

    </div>


    {{-- Approved --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Approved
        </p>

        <p class="mt-3 text-3xl font-bold text-emerald-700">
            {{ $approvedNominations }}
        </p>

        <p class="mt-2 text-xs text-slate-500">
            Nominations approved by the Commission
        </p>

    </div>


    {{-- Action required --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Action Required
        </p>

        <p class="mt-3 text-3xl font-bold text-amber-600">
            {{ $actionRequired }}
        </p>

        <p class="mt-2 text-xs text-slate-500">
            Draft or returned nominations requiring attention
        </p>

    </div>

</div>


{{-- Candidate nominations --}}
<div class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h2 class="text-lg font-semibold text-slate-950">
                Candidate Nominations
            </h2>

            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">
                Create, complete and submit candidate nominations for
                {{ $party->name }}.
            </p>

        </div>

        <a
            href="{{ route('party.nominations.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
        >
            New Candidate Nomination
        </a>

    </div>

</div>


{{-- Returned nominations --}}
@if($returnedNominations > 0)

    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-amber-900">
                    Returned Nominations
                </h2>

                <p class="mt-1 text-sm text-amber-800">
                    {{ $returnedNominations }}
                    nomination{{ $returnedNominations === 1 ? '' : 's' }}
                    {{ $returnedNominations === 1 ? 'has' : 'have' }}
                    been returned and may require your attention.
                </p>

            </div>

            <a
                href="{{ route('party.returned-nominations.index') }}"
                class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700"
            >
                View Returned
            </a>

        </div>

    </div>

@endif


@endsection
