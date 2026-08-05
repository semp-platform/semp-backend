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


<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">
            Draft Nominations
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-950">
            0
        </p>
    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">
            Submitted
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-950">
            0
        </p>
    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">
            Approved
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-950">
            0
        </p>
    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">
            Action Required
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-950">
            0
        </p>
    </div>

</div>


<div class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <h2 class="text-lg font-semibold text-slate-950">
        Candidate Nomination
    </h2>

    <a
    href="{{ route('party.nominations.create') }}"
    class="mt-5 inline-flex rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
>
    New Candidate Nomination
</a>

    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
        Candidate registration, supporting documents, nomination payments,
        submissions, withdrawals and replacements will be managed from this
        party workspace.
    </p>

</div>

@endsection
