@extends('layouts.app')

@section('title', 'Election Results')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
            Election Results
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
            Published Results
        </h1>

        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
            View official election results published by the Commission.
        </p>
    </div>

    {{-- Results --}}
    <div class="mt-8">

        @forelse($results as $result)

            <a
                href="{{ route('public.results.show', $result) }}"
                class="block rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-emerald-300 hover:shadow-md"
            >

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">
                            Published Election Result
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-slate-950">
                            {{ $result->election->name ?? 'Election' }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-600">
                            {{ $result->position->name ?? 'Election Position' }}
                        </p>

                        @if($result->published_at)
                            <p class="mt-3 text-xs text-slate-500">
                                Published
                                {{ $result->published_at->format('d M Y, H:i') }}
                            </p>
                        @endif

                    </div>

                    <div class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white">
                        View Results
                    </div>

                </div>

            </a>

        @empty

            <div class="rounded-xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 9H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586A2 2 0 0115 3.586L19.414 8A2 2 0 0120 9.414V19a2 2 0 01-2 2z"
                        />
                    </svg>

                </div>

                <h2 class="mt-5 text-lg font-semibold text-slate-950">
                    No published results
                </h2>

                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                    Official election results will appear here after they have been reviewed and published.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
