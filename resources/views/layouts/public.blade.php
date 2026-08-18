<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'OGSIEC')
    </title>

    <meta
        name="description"
        content="@yield(
            'meta_description',
            'Official website of the Ogun State Independent Electoral Commission.'
        )"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-white text-slate-950 antialiased">

    {{-- ==========================================================
         PUBLIC HEADER
         ========================================================== --}}

    <header class="border-b border-slate-300 bg-white">

        {{-- Top information bar --}}
        <div class="bg-slate-950 text-white">

            <div
                class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2.5 text-xs sm:px-6 lg:px-8"
            >

                <div class="font-medium">
                    Ogun State Independent Electoral Commission

                </div>

                <div class="hidden font-medium text-slate-300 sm:block">
                    Official Public Information Portal
                </div>

            </div>

        </div>


        {{-- Main navigation --}}
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="flex min-h-[76px] items-center justify-between gap-6">

                {{-- Commission identity --}}
                <a
                    href="{{ url('/') }}"
                    class="group flex min-w-0 items-center gap-1.5"
                >

                    {{-- Simple OGSIEC mark --}}


                    <div class="min-w-0">

                        <div class="flex items-center gap-0.5">

    {{-- Official Logos --}}
<div class="flex shrink-0 items-center gap-1">

    {{-- Ogun State Logo --}}
    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white p-1 shadow-sm sm:h-14 sm:w-14">
        <img
            src="{{ asset('images/ogun_logo.png') }}"
            alt="Ogun State Government Logo"
            class="h-full w-full object-contain"
        >
    </div>

    {{-- OGSIEC Logo --}}
    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white p-1 shadow-sm sm:h-14 sm:w-14">
        <img
            src="{{ asset('images/ogsiec-logo.png') }}"
            alt="OGSIEC Logo"
            class="h-full w-full object-contain"
        >
    </div>

</div>

    {{-- Commission Name --}}
    <div class="min-w-0">

        <div class="text-sm font-bold leading-tight text-slate-950">
            OGSIEC
        </div>

        <div class="text-[13px] leading-4 text-slate-500">
            Ogun State Independent Electoral Commission
        </div>

    </div>

</div>

                    </div>

                </a>


                {{-- Desktop navigation --}}
                <nav
                    class="hidden items-center gap-5 lg:flex"
                    aria-label="Main navigation"
                >

                    <a
                        href="{{ url('/') }}"
                        class="border-b-2 border-transparent py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-700 hover:text-emerald-800"
                    >
                        Home
                    </a>

                    <a
                        href="{{ route('public.elections.index') }}"
                        class="border-b-2 border-transparent py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-700 hover:text-emerald-800"
                    >
                        Elections
                    </a>

                    {{-- Not yet connected to a public route --}}
                    <a
    href="{{ route('public.candidates.index') }}"
    class="border-b-2 border-transparent py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-700 hover:text-emerald-800"
>
    Candidates
</a>

                    {{-- Not yet connected to a public route --}}
                    <span
                        class="cursor-default py-2 text-sm font-semibold text-slate-500"
                        title="Results publication will be added here"
                    >
                        Results
                    </span>

                    {{-- Not yet connected to a public route --}}
                    <a
    href="{{ route('public.content.index') }}"
    class="border-b-2 border-transparent py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-700 hover:text-emerald-800"
>
    Publications
</a>

                    {{-- Existing public content is currently managed through
                         the authenticated public-content area. --}}
                    <a
    href="{{ route('public.content.index', ['type' => 'news']) }}"
    class="border-b-2 border-transparent py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-700 hover:text-emerald-800"
>
    News
</a>

                    {{-- Not yet connected to a public route --}}
                    <a
    href="{{ route('public.about') }}"
    class="border-b-2 border-transparent py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-700 hover:text-emerald-800"
>
    About
</a>

                    {{-- SEMP --}}
                    <a
                        href="{{ url('/login') }}"
                        class="ml-1 inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800"
                    >
                        SEMP Portal
                    </a>

                </nav>


                {{-- Mobile SEMP link --}}
                <a
                    href="{{ url('/login') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 lg:hidden"
                >
                    SEMP
                </a>

            </div>

        </div>


        {{-- Mobile navigation --}}
        <div class="border-t border-slate-200 lg:hidden">

            <nav
                class="mx-auto flex max-w-7xl gap-1 overflow-x-auto px-4 py-2 sm:px-6 lg:px-8"
                aria-label="Mobile navigation"
            >

                <a
                    href="{{ url('/') }}"
                    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800"
                >
                    Home
                </a>

                <a
                    href="{{ route('public.elections.index') }}"
                    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800"
                >
                    Elections
                </a>

                <a
    href="{{ route('public.candidates.index') }}"
    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800"
>
    Candidates
</a>

                <span
                    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-500"
                >
                    Results
                </span>

                <a
    href="{{ route('public.content.index') }}"
    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800"
>
    Publications
</a>

                <a
    href="{{ route('public.content.index', ['type' => 'news']) }}"
    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800"
>
    News
</a>

                <a
    href="{{ route('public.about') }}"
    class="shrink-0 rounded-md px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800"
>
    About
</a>

            </nav>

        </div>

    </header>


    {{-- ==========================================================
         MAIN CONTENT
         ========================================================== --}}

    <main>

        @yield('content')

    </main>


{{-- ==========================================================
     FOOTER
     ========================================================== --}}

<footer
    class="relative mt-16 overflow-hidden border-t border-slate-800 bg-slate-950 text-white"
>

    {{-- Faded Olumo Rock background --}}
    <div
        class="pointer-events-none absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
        style="background-image: url('{{ asset('images/olumo_rock.jpg') }}');"
    ></div>

    {{-- Dark overlay for readability --}}
    <div
        class="pointer-events-none absolute inset-0 bg-slate-950/70"
    ></div>

    {{-- Subtle green tint --}}
    <div
        class="pointer-events-none absolute inset-0 bg-emerald-950/20"
    ></div>


    {{-- Main footer content --}}
    <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

        <div class="grid gap-10 md:grid-cols-4">

            {{-- ==================================================
                 COMMISSION
                 ================================================== --}}
            <div class="md:col-span-2">

                <div class="flex items-center gap-3">

                    {{-- Official logos --}}
<div class="flex shrink-0 items-center gap-2">

    {{-- Ogun State Logo --}}
    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-white p-1 shadow-sm">
        <img
            src="{{ asset('images/ogun_logo.png') }}"
            alt="Ogun State Government Logo"
            class="h-full w-full object-contain"
        >
    </div>

    {{-- OGSIEC Logo --}}
    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-white p-1 shadow-sm">
        <img
            src="{{ asset('images/ogsiec-logo.png') }}"
            alt="OGSIEC Logo"
            class="h-full w-full object-contain"
        >
    </div>

</div>

                    <div>

                        <h2 class="text-lg font-bold tracking-tight text-white">
                            OGSIEC
                        </h2>

                        <p class="text-xs font-normal text-slate-300">
                            Ogun State Independent Electoral Commission
                        </p>

                    </div>

                </div>


                <p class="mt-5 max-w-xl text-sm font-normal leading-6 text-slate-300">
                    Official public information on elections,
                    candidates, notices, publications, announcements
                    and electoral activities in Ogun State.
                </p>

            </div>


            {{-- ==================================================
                 QUICK LINKS
                 ================================================== --}}
            <div>

                <h2 class="text-sm font-semibold uppercase tracking-widest text-white">
                    Quick Links
                </h2>

                <div class="mt-5 space-y-3 text-sm">

                    <a
                        href="{{ route('public.elections.index') }}"
                        class="block font-normal text-slate-300 transition hover:text-emerald-300"
                    >
                        Elections
                    </a>

                    <a
                        href="{{ route('public.candidates.index') }}"
                        class="block font-normal text-slate-300 transition hover:text-emerald-300"
                    >
                        Candidates
                    </a>

                    <span class="block font-normal text-slate-400">
                        Results
                    </span>

                    <a
                        href="{{ route('public.content.index') }}"
                        class="block font-normal text-slate-300 transition hover:text-emerald-300"
                    >
                        Publications
                    </a>

                </div>

            </div>


            {{-- ==================================================
                 SEMP / POLITICAL PARTIES
                 ================================================== --}}
            <div>

                <h2 class="text-sm font-semibold uppercase tracking-widest text-white">
                    Political Parties
                </h2>

                <p class="mt-5 text-sm font-normal leading-6 text-slate-300">
                    Access SEMP for digital candidate nomination
                    and registration.
                </p>

                {{-- Accent button --}}
                <a
                    href="{{ url('/login') }}"
                    class="mt-5 inline-flex items-center rounded-lg bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-300"
                >
                    SEMP Portal
                    <span class="ml-2">→</span>
                </a>

            </div>

        </div>


        {{-- ==================================================
             FOOTER DIVIDER
             ================================================== --}}
        <div class="mt-10 border-t border-white/15 pt-6">

            <div
                class="flex flex-col gap-3 text-xs font-normal text-slate-400 sm:flex-row sm:items-center sm:justify-between"
            >

                <p>
                    © {{ date('Y') }}
                    Ogun State Independent Electoral Commission.
                    All rights reserved.
                </p>

                <p class="text-slate-400">
                    Official Public Information Portal
                </p>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         BOTTOM STRIP
         ========================================================== --}}
    <div class="relative border-t border-white/10 bg-slate-950/70">

        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">

            <div
                class="flex flex-col gap-2 text-xs font-normal text-slate-500 sm:flex-row sm:items-center sm:justify-between"
            >

                <span>
                    OGSIEC Public Information Portal
                </span>

                <span>
                    Elections • Candidates • Notices • Publications
                </span>

            </div>

        </div>

    </div>

</footer>
</body>
</html>
