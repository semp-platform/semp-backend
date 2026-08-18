@extends('layouts.public')

@section('title', 'Elections')

@section('meta_description')
    Current, upcoming and published election information from the Ogun State Independent Electoral Commission.
@endsection

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Page heading --}}
    <div class="mb-8">

        <div class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">
            OGSIEC Elections
        </div>

        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
            Elections
        </h1>

        <p class="mt-2 max-w-2xl text-base leading-7 text-slate-600">
            View current and upcoming elections conducted by the
            Ogun State Independent Electoral Commission.
        </p>

    </div>


    @if($elections->isEmpty())

        {{-- Empty state --}}
        <div class="rounded-xl border border-slate-300 bg-white px-6 py-12 text-center shadow-sm">

            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-800">
                <span class="text-lg font-bold">E</span>
            </div>

            <h2 class="mt-4 text-xl font-semibold text-slate-950">
                No elections currently available
            </h2>

            <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-600">
                Election information will appear here when it is
                published by the Commission.
            </p>

        </div>

    @else

        {{-- Election cards --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

            @foreach($elections as $election)

                <article
                    class="group flex h-full flex-col overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
                >

                    {{-- Green accent --}}
                    <div class="h-1 bg-emerald-700"></div>

                    <div class="flex flex-1 flex-col p-6">

                        {{-- Type + status --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ $election->electionType?->name ?? 'Election' }}
                                </p>

                            </div>

                            <span
                                class="shrink-0 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold capitalize text-emerald-800"
                            >
                                {{ str_replace('_', ' ', $election->status) }}
                            </span>

                        </div>


                        {{-- Election name --}}
                        <h2 class="mt-3 text-xl font-semibold leading-7 text-slate-950">
                            {{ $election->name }}
                        </h2>


                        {{-- Information --}}
                        <div class="mt-6 space-y-4 border-t border-slate-200 pt-5">

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Election Date
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-950">
                                    {{ $election->election_date?->format('d M Y') ?? 'Not announced' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    State
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-950">
                                    {{ $election->state?->name ?? 'Ogun State' }}
                                </p>

                            </div>

                        </div>


                        {{-- Action --}}
                        <div class="mt-auto pt-6">

                            <a
                                href="{{ route('public.elections.show', $election) }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800 hover:text-emerald-950"
                            >
                                View election information

                                <span
                                    aria-hidden="true"
                                    class="transition-transform group-hover:translate-x-1"
                                >
                                    →
                                </span>
                            </a>

                        </div>

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</div>

@endsection
