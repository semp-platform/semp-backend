@extends('layouts.public')

@section('title', 'Home')

@section('content')

<div class="space-y-8">

    {{-- ==========================================================
         IMPORTANT NOTICE TICKER
         ========================================================== --}}

    @if($ticker->isNotEmpty())

        <section
            class="overflow-hidden rounded-xl border border-emerald-200 bg-emerald-50"
            aria-label="Important notices"
        >

            <div class="flex items-stretch">

                <div class="flex shrink-0 items-center bg-emerald-800 px-4 py-3 text-xs font-bold uppercase tracking-wider text-white sm:px-5">
                    Important Notice
                </div>

                <div class="min-w-0 flex-1 overflow-hidden">

<div class="ticker-marquee flex min-w-max gap-12 px-6 py-3">
             @foreach($ticker as $item)

                            <a
                                href="{{ route('public.content.show', $item->slug) }}"
                                class="flex items-center gap-3 whitespace-nowrap text-sm font-medium text-slate-800 hover:text-emerald-800"
                            >

                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>

                                {{ $item->title }}

                                <span class="text-emerald-700">
                                    →
                                </span>

                            </a>

                        @endforeach

                    </div>

                </div>

            </div>

        </section>

    @endif


  {{-- ==========================================================
     HERO / FEATURED INFORMATION
     ========================================================== --}}

@if($featured->isNotEmpty())

    <section>

        <div class="mb-4 flex items-end justify-between gap-4">

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                    OGSIEC Updates
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                    Featured Information
                </h1>
            </div>

            <a
                href="{{ route('public.content.index') }}"
                class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
            >
                View all →
            </a>

        </div>


        {{-- FEATURED SLIDER --}}

        <div
            x-data="{
                active: 0,
                total: {{ $featured->count() }},
                timer: null,

                start() {
                    this.stop();

                    if (this.total > 1) {
                        this.timer = setInterval(() => {
                            this.next();
                        }, 6000);
                    }
                },

                stop() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },

                next() {
                    this.active = (this.active + 1) % this.total;
                },

                previous() {
                    this.active =
                        (this.active - 1 + this.total) % this.total;
                }
            }"
            x-init="start()"
            @mouseenter="stop()"
            @mouseleave="start()"
            class="relative overflow-hidden rounded-2xl bg-slate-950 shadow-lg"
        >

            {{-- Fixed banner height --}}

            <div class="relative h-[320px] sm:h-[380px] lg:h-[620px]">


                @foreach($featured as $index => $item)

                    <article
                        x-cloak
                        x-show="active === {{ $index }}"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0"
                    >

                        {{-- Background image --}}

                        @if($item->image_path)

                            <img
                                src="{{ asset('storage/' . $item->image_path) }}"
                                alt="{{ $item->title }}"
                                class="absolute inset-0 h-full w-full object-cover object-center"
                            >

                            {{-- Dark overlay for readability --}}

                            <div class="absolute inset-0 bg-slate-950/10"></div>

                            {{-- Slight bottom gradient --}}

                            <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-slate-950/80 to-transparent"></div>

                        @else

                            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950"></div>

                        @endif


                        {{-- Content --}}

                        <div class="relative flex h-full items-end">

<div class="w-full px-6 pb-10 pt-8 sm:px-10 sm:pb-12 lg:max-w-4xl">
                                {{-- Content type --}}

                                <span class="inline-flex rounded-full bg-emerald-600 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white shadow-sm">
                                    {{ str_replace('_', ' ', $item->type) }}
                                </span>


                                {{-- Title --}}

<h2 class="mt-3 max-w-3xl text-2xl font-bold leading-tight tracking-tight text-white sm:text-3xl lg:text-4xl">                                    {{ $item->title }}
                                </h2>


                                {{-- Summary --}}

                                @if($item->summary)

                                    <p class="mt-3 max-w-2xl line-clamp-2 text-sm leading-6 text-slate-200 sm:text-base">
                                        {{ $item->summary }}
                                    </p>

                                @endif


                                {{-- Read more --}}

                                <a
                                    href="{{ route('public.content.show', $item->slug) }}"
                                    class="mt-5 inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-bold text-slate-950 shadow-sm transition hover:bg-slate-100"
                                >
                                    Read more
                                    <span class="ml-2">→</span>
                                </a>

                            </div>

                        </div>

                    </article>

                @endforeach


                {{-- Previous / Next buttons --}}

                @if($featured->count() > 1)

                    <button
                        type="button"
                        @click="previous()"
                        class="absolute left-4 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-slate-950/50 text-xl text-white backdrop-blur-sm transition hover:bg-slate-950/80"
                        aria-label="Previous featured story"
                    >
                        ←
                    </button>


                    <button
                        type="button"
                        @click="next()"
                        class="absolute right-4 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-slate-950/50 text-xl text-white backdrop-blur-sm transition hover:bg-slate-950/80"
                        aria-label="Next featured story"
                    >
                        →
                    </button>


                    {{-- Slide indicators --}}

                    <div class="absolute bottom-5 right-6 z-20 flex items-center gap-2">

                        @foreach($featured as $index => $item)

                            <button
                                type="button"
                                @click="active = {{ $index }}"
                                :class="
                                    active === {{ $index }}
                                        ? 'w-8 bg-white'
                                        : 'w-2 bg-white/50 hover:bg-white/80'
                                "
                                class="h-2 rounded-full transition-all duration-300"
                                aria-label="Show featured story {{ $index + 1 }}"
                            ></button>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </section>

@endif
{{-- ==========================================================
     CURRENT / UPCOMING ELECTION
     ========================================================== --}}

@if($currentElection)

    <section class="overflow-hidden rounded-2xl border border-emerald-200 bg-slate-50 shadow-sm">

        {{-- Section heading --}}
        <div class="border-b border-emerald-200 bg-gradient-to-r from-emerald-50 via-cyan-50 to-amber-50 px-6 py-6 sm:px-8">

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Election Information
            </p>

            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                {{ $currentElection->name }}
            </h2>

        </div>


        {{-- Election details --}}
        <div class="grid gap-4 bg-slate-100 p-4 sm:grid-cols-3 sm:gap-5 sm:p-5">


            {{-- Election date --}}
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                        <span class="text-lg">◷</span>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-700">
                            Election Date
                        </p>

                        <p class="mt-1 text-lg font-bold text-slate-950">
                            {{ $currentElection->election_date?->format('d M Y') ?? 'Not announced' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Status --}}
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <span class="text-lg">✓</span>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">
                            Status
                        </p>

                        <p class="mt-1 text-lg font-bold capitalize text-slate-950">
                            {{ str_replace('_', ' ', $currentElection->status) }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- View election --}}
            <div class="flex items-center rounded-xl border border-slate-200 bg-white px-6 py-5">

                <a
                    href="{{ route('public.elections.show', $currentElection) }}"
                    class="inline-flex items-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800"
                >
                    View Election
                    <span class="ml-2">→</span>
                </a>

            </div>

        </div>

    </section>

@endif


    {{-- ==========================================================
         LATEST NEWS
         ========================================================== --}}

    @if($latestNews->isNotEmpty())

        <section>

    <div class="mb-5 flex items-end justify-between gap-4">

        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Latest
            </p>

            <h2 class="mt-1 text-2xl font-bold text-slate-950">
                News & Updates
            </h2>
        </div>

        <a
            href="{{ route('public.content.index', ['type' => 'news']) }}"
            class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
        >
            View all →
        </a>

    </div>


    {{-- News + Information Layout --}}
    <div class="grid gap-5 lg:grid-cols-3">

        {{-- =========================================================
             NEWS LIST — ESCALATOR STYLE
             ========================================================= --}}

        <div
            class="lg:col-span-2"
            x-data="{
                timer: null,

                start() {
                    if ({{ $latestNews->count() }} <= 3) {
                        return;
                    }

                    this.stop();

                    this.timer = setInterval(() => {
                        const container = this.$refs.newsList;

                        if (!container) {
                            return;
                        }

                        const firstItem = container.querySelector('[data-news-item]');

                        if (!firstItem) {
                            return;
                        }

                        const itemHeight = firstItem.offsetHeight;
                        const maxScroll = container.scrollHeight - container.clientHeight;

                        if (container.scrollTop + itemHeight >= maxScroll - 5) {
                            container.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            container.scrollBy({
                                top: itemHeight,
                                behavior: 'smooth'
                            });
                        }
                    }, 6000);
                },

                stop() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                }
            }"
            x-init="start()"
            @mouseenter="stop()"
            @mouseleave="start()"
        >

            {{-- News heading --}}
            <div class="mb-5 flex items-end justify-between gap-4">
                <a
                    href="{{ route('public.content.index', ['type' => 'news']) }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
                >
                    View all →
                </a>
            </div>

            {{-- =========================================================
                 FIXED NEWS WINDOW
                 ========================================================= --}}

            <div
                x-ref="newsList"
                class="news-scroll h-[420px] overflow-y-auto scroll-smooth"
                style="scrollbar-width: none;"
            >
                @foreach($latestNews as $item)
                    <article
                        data-news-item
                        class="group mb-3 min-h-[132px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
                    >
                        <a
                            href="{{ route('public.content.show', $item->slug) }}"
                            class="flex h-full gap-4"
                        >
                            {{-- Thumbnail --}}
                            @if($item->image_path)
                                <div class="relative h-[132px] w-32 shrink-0 overflow-hidden bg-slate-100 sm:w-40">
                                    <img
                                        src="{{ asset('storage/' . $item->image_path) }}"
                                        alt="{{ $item->title }}"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent to-slate-950/10"></div>
                                </div>
                            @else
                                <div class="flex h-[132px] w-32 shrink-0 items-center justify-center bg-gradient-to-br from-emerald-50 to-emerald-100 text-xs font-bold text-emerald-700 sm:w-40">
                                    OGSIEC
                                </div>
                            @endif

                            {{-- News content --}}
                            <div class="min-w-0 flex-1 py-4 pr-4 sm:py-5 sm:pr-5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-700">
                                        News
                                    </span>

                                    @if($item->published_at)
                                        <span class="text-[10px] text-slate-400">
                                            {{ $item->published_at->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="mt-1 line-clamp-2 text-sm font-bold leading-5 text-slate-950 sm:text-base">
                                    {{ $item->title }}
                                </h3>

                                @if($item->summary)
                                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-600 sm:text-sm">
                                        {{ $item->summary }}
                                    </p>
                                @endif

                                <p class="mt-2 text-xs font-bold text-emerald-700 transition group-hover:text-emerald-900">
                                    Read story →
                                </p>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            {{-- =========================================================
                 OGSIEC AT A GLANCE
                 ========================================================= --}}

<div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">                {{-- Section heading --}}
                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                        OGSIEC at a Glance
                    </p>

                    <div class="flex items-center justify-between gap-4">
                        <h3 class="mt-1 text-lg font-bold text-slate-950">
                            Electoral Coverage
                        </h3>

                        <span class="hidden text-xs font-medium text-slate-400 sm:block">
                            Ogun State
                        </span>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="grid grid-cols-3 divide-x divide-slate-200">
                    <div class="group relative overflow-hidden px-3 py-5 text-center transition duration-300 hover:bg-emerald-50/60 sm:px-5">
                        <div class="pointer-events-none absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-50 opacity-0 transition duration-500 group-hover:scale-150 group-hover:opacity-100"></div>

                        <div class="relative mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-5 7 5v12M8 21v-7h3v7m2 0v-7h3v7" />
                            </svg>
                        </div>

                        <div class="relative mt-3 text-2xl font-black text-emerald-700 sm:text-3xl">
                            {{ number_format($lgaCount) }}
                        </div>

                        <p class="relative mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 sm:text-xs">
                            LGAs
                        </p>

                        <p class="relative mt-1 hidden text-[10px] text-slate-400 sm:block">
                            Local Governments
                        </p>
                    </div>

                    <div class="group relative overflow-hidden px-3 py-5 text-center transition duration-300 hover:bg-slate-50 sm:px-5">
                        <div class="pointer-events-none absolute -right-6 -top-6 h-20 w-20 rounded-full bg-slate-100 opacity-0 transition duration-500 group-hover:scale-150 group-hover:opacity-100"></div>

                        <div class="relative mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4.5-8 4.5-8-4.5L12 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12l8 4.5 8-4.5M4 16.5l8 4.5 8-4.5" />
                            </svg>
                        </div>

                        <div class="relative mt-3 text-2xl font-black text-slate-800 sm:text-3xl">
                            {{ number_format($wardCount) }}
                        </div>

                        <p class="relative mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 sm:text-xs">
                            Wards
                        </p>

                        <p class="relative mt-1 hidden text-[10px] text-slate-400 sm:block">
                            Electoral Wards
                        </p>
                    </div>

                    <div class="group relative overflow-hidden px-3 py-5 text-center transition duration-300 hover:bg-amber-50/60 sm:px-5">
                        <div class="pointer-events-none absolute -right-6 -top-6 h-20 w-20 rounded-full bg-amber-50 opacity-0 transition duration-500 group-hover:scale-150 group-hover:opacity-100"></div>

                        <div class="relative mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 016.5 3h11A2.5 2.5 0 0120 5.5v13a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 18.5v-13z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M8 15h4" />
                            </svg>
                        </div>

                        <div class="relative mt-3 text-2xl font-black text-amber-600 sm:text-3xl">
                            {{ number_format($pollingUnitCount) }}
                        </div>

                        <p class="relative mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 sm:text-xs">
                            Polling Units
                        </p>

                        <p class="relative mt-1 hidden text-[10px] text-slate-400 sm:block">
                            Voting Locations
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
             OGSIEC INFORMATION PANEL
             ========================================================= --}}

        @if($homeServices->isNotEmpty())
            <aside class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-5 py-5">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                        OGSIEC Information
                    </p>

                    <h3 class="mt-1 text-xl font-bold tracking-tight text-slate-950">
                        Public Services
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Access official election information and public services
                        from OGSIEC.
                    </p>
                </div>

                <div class="space-y-3 p-4">
                    @foreach($homeServices as $service)
                        @php
                            $serviceImage = $service->image_path
                                ? asset('storage/' . ltrim($service->image_path, '/'))
                                : asset('images/olumo_rock.jpg');
                        @endphp

                        <a
                            href="{{ route('public.content.show', $service->slug) }}"
                            class="group relative block min-h-[118px] overflow-hidden rounded-lg border border-slate-200 bg-slate-900 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md"
                        >
                            <div
                                class="absolute inset-0 bg-cover bg-center transition duration-700 group-hover:scale-105"
                                style="background-image: url('{{ $serviceImage }}');"
                            ></div>

                            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-transparent transition duration-300"></div>
                            <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500"></div>

                            <div class="absolute inset-x-0 bottom-0 z-10 p-3 pr-12">
                                <div class="min-w-0">
                                    <p class="line-clamp-1 text-xs font-bold leading-4 text-white sm:text-sm">
                                        {{ $service->title }}
                                    </p>

                                    @if($service->summary)
                                        <p class="mt-1 line-clamp-1 text-[10px] leading-4 text-white/85 sm:text-xs">
                                            {{ $service->summary }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <span class="absolute right-3 top-1/2 z-20 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/25 text-sm font-medium text-white backdrop-blur-sm transition duration-300 group-hover:border-emerald-400 group-hover:bg-emerald-600">
                                →
                            </span>
                        </a>
                    @endforeach
                </div>
            </aside>
        @endif
    </div>

</section>

@endif


{{-- ==========================================================
     NOTICES + ANNOUNCEMENTS
     ========================================================== --}}

    <section class="grid gap-6 lg:grid-cols-2">

        @if($latestNotices->isNotEmpty())

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-widest text-amber-700">
                            Public Information
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-slate-950">
                            Notices
                        </h2>

                    </div>

                    <a
                        href="{{ route('public.content.index', ['type' => 'notice']) }}"
                        class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
                    >
                        All →
                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @foreach($latestNotices as $item)

                        <a
                            href="{{ route('public.content.show', $item->slug) }}"
                            class="block px-6 py-5 transition hover:bg-slate-50"
                        >

                            <div class="flex items-start gap-4">

                                <div class="mt-1 h-2 w-2 shrink-0 rounded-full bg-amber-500"></div>

                                <div class="min-w-0">

                                    <h3 class="font-semibold text-slate-950">
                                        {{ $item->title }}
                                    </h3>

                                    @if($item->published_at)

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $item->published_at->format('d M Y') }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        @endif


        @if($announcements->isNotEmpty())

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                            Commission Updates
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-slate-950">
                            Announcements
                        </h2>

                    </div>

                    <a
                        href="{{ route('public.content.index', ['type' => 'announcement']) }}"
                        class="text-sm font-semibold text-emerald-700 hover:text-emerald-900"
                    >
                        All →
                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @foreach($announcements as $item)

                        <a
                            href="{{ route('public.content.show', $item->slug) }}"
                            class="block px-6 py-5 transition hover:bg-slate-50"
                        >

                            <div class="flex items-start gap-4">

                                <div class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-600"></div>

                                <div class="min-w-0">

                                    <h3 class="font-semibold text-slate-950">
                                        {{ $item->title }}
                                    </h3>

                                    @if($item->published_at)

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $item->published_at->format('d M Y') }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        @endif

    </section>


    {{-- ==========================================================
         QUICK ACCESS
         ========================================================== --}}

    <section>

        <div class="mb-5">

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Quick Access
            </p>

            <h2 class="mt-1 text-2xl font-bold text-slate-950">
                OGSIEC Public Services
            </h2>

        </div>


        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <a
                href="{{ route('public.elections.index') }}"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
            >

                <p class="text-sm font-bold text-emerald-700">
                    Elections
                </p>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Current and upcoming elections conducted by OGSIEC.
                </p>

            </a>


            <a
                href="{{ route('public.candidates.index') }}"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
            >

                <p class="text-sm font-bold text-emerald-700">
                    Candidates
                </p>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    View published candidate information.
                </p>

            </a>


            <a
                href="{{ route('public.content.index', ['type' => 'notice']) }}"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
            >

                <p class="text-sm font-bold text-emerald-700">
                    Notices
                </p>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Official notices and public information.
                </p>

            </a>


            <a
                href="{{ route('public.content.index', ['type' => 'press_release']) }}"
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
            >

                <p class="text-sm font-bold text-emerald-700">
                    Press Releases
                </p>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Official statements and releases from the Commission.
                </p>

            </a>

        </div>

    </section>

</div>

{{-- ==========================================================
     MARQUEE ANIMATION
     ========================================================== --}}

<style>

    @keyframes marquee {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(-100%);
        }
    }

    .ticker-marquee {
        animation: marquee 45s linear infinite;
        width: max-content;
    }

    .ticker-marquee:hover {
        animation-play-state: paused;
    }

    [x-cloak] {
        display: none !important;
    }

</style>

@endsection
