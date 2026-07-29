@extends('layouts.app')

@section('title', 'Dashboard | SEMP')

@section('content')

    <div class="mb-8">

        <p class="text-sm font-medium text-emerald-700">
            SEMP
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Dashboard
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Welcome back, {{ auth()->user()->name }}.
        </p>

    </div>


    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Elections
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                —
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Election records
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Nominations
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                —
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Candidate nominations
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Political Parties
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                —
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Registered parties
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Candidates
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-950">
                —
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Verified candidate profiles
            </p>
        </div>

    </div>


    <div class="mt-8 rounded-xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-950">
                Election Management
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Manage elections and candidate nominations from SEMP.
            </p>
        </div>


        <div class="grid gap-4 p-6 md:grid-cols-2">

            <div class="rounded-lg border border-slate-200 p-5">
                <h3 class="font-semibold text-slate-900">
                    Elections
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Create election events, configure positions and manage
                    election details.
                </p>

                <span class="mt-4 inline-block text-sm font-semibold text-emerald-700">
                    Election management
                </span>
            </div>


            <div class="rounded-lg border border-slate-200 p-5">
                <h3 class="font-semibold text-slate-900">
                    Nominations
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Review political party nominations and verified candidate
                    information.
                </p>

                <span class="mt-4 inline-block text-sm font-semibold text-emerald-700">
                    Nomination management
                </span>
            </div>

        </div>

    </div>

@endsection
