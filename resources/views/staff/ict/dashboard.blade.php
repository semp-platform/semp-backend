@extends('layouts.staff')

@section('title', 'ICT Dashboard')

@section('content')

<div class="mx-auto max-w-7xl">

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-800">
            ICT Dashboard
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Overview of nomination, results and election operations.
        </p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

        <a
            href="{{ route('staff.ict.nomination-batches.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow"
        >
            <p class="text-sm font-medium text-slate-500">
                Pending Batches
            </p>

            <p class="mt-2 text-3xl font-semibold text-slate-900">
                {{ $pendingBatches }}
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Submitted or under ICT review
            </p>
        </a>

        <a
            href="{{ route('staff.ict.nominations.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow"
        >
            <p class="text-sm font-medium text-slate-500">
                Nominations Under Review
            </p>

            <p class="mt-2 text-3xl font-semibold text-slate-900">
                {{ $nominationsUnderReview }}
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Currently with ICT
            </p>
        </a>

        <a
            href="{{ route('staff.ict.results.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow"
        >
            <p class="text-sm font-medium text-slate-500">
                Total Nominations
            </p>

            <p class="mt-2 text-3xl font-semibold text-slate-900">
                {{ $totalNominations }}
            </p>

            <p class="mt-2 text-xs text-slate-400">
                All recorded nominations
            </p>
        </a>

        <a
            href="{{ route('staff.ict.results.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow"
        >
            <p class="text-sm font-medium text-slate-500">
                Published Results
            </p>

            <p class="mt-2 text-3xl font-semibold text-slate-900">
                {{ $publishedResults ?? 0 }}
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Results available for publication
            </p>
        </a>

    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800">
                Nomination Management
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Review incoming nomination batches and nominations currently assigned to ICT.
            </p>

            <div class="mt-5 flex flex-wrap gap-3">

                <a
                    href="{{ route('staff.ict.nomination-batches.index') }}"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                >
                    Nomination Batches
                </a>

                <a
                    href="{{ route('staff.ict.nominations.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Nominations
                </a>

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800">
                Results Management
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Import, analyse and manage election results.
            </p>

            <div class="mt-5 flex flex-wrap gap-3">

                <a
                    href="{{ route('staff.ict.results.index') }}"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                >
                    Results Management
                </a>

                <a
                    href="{{ route('staff.ict.results.create') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Upload Results
                </a>

            </div>

        </div>

    </div>

</div>

@endsection
