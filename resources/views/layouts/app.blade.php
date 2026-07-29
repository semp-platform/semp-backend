<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SEMP')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">

<div class="min-h-screen lg:flex">

    {{-- Sidebar --}}
    <aside class="hidden w-72 shrink-0 bg-emerald-950 text-white lg:flex lg:flex-col">

        <div class="border-b border-white/10 px-6 py-6">
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-white font-bold text-emerald-900">
                    OG
                </div>

                <div>
                    <p class="font-bold tracking-wide">SEMP</p>
                    <p class="text-xs text-emerald-200">
                        Election Management Platform
                    </p>
                </div>

            </div>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-6">

            <a href="{{ route('dashboard') }}"
               class="block rounded-lg px-4 py-3 text-sm font-medium
               {{ request()->routeIs('dashboard')
                    ? 'bg-white/10 text-white'
                    : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
                Dashboard
            </a>

           <a href="{{ route('elections.index') }}"
   class="block rounded-lg px-4 py-3 text-sm font-medium
   {{ request()->routeIs('elections.*')
        ? 'bg-white/10 text-white'
        : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
    Elections
</a>

            <a href="{{ route('nominations.index') }}"
   class="block rounded-lg px-4 py-3 text-sm font-medium
   {{ request()->routeIs('nominations.*')
        ? 'bg-white/10 text-white'
        : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
    Nominations
</a>

            <a href="#"
               class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-100 hover:bg-white/5 hover:text-white">
                Political Parties
            </a>

            <a href="#"
               class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-100 hover:bg-white/5 hover:text-white">
                Candidates
            </a>

            <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
                System
            </div>

            <a href="#"
               class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-100 hover:bg-white/5 hover:text-white">
                Reference Data
            </a>

            <a href="#"
               class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-100 hover:bg-white/5 hover:text-white">
                Administration
            </a>

        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="mb-3 px-2">
                <p class="text-sm font-medium text-white">
                    {{ auth()->user()->name }}
                </p>

                <p class="truncate text-xs text-emerald-300">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-lg border border-white/10 px-4 py-2.5 text-left text-sm text-emerald-100 transition hover:bg-white/10 hover:text-white"
                >
                    Sign out
                </button>
            </form>
        </div>

    </aside>


    {{-- Main application --}}
    <div class="min-w-0 flex-1">

        {{-- Top bar --}}
        <header class="border-b border-slate-200 bg-white">
            <div class="flex h-16 items-center justify-between px-6 lg:px-8">

                <div>
                    <p class="text-sm font-semibold text-slate-900">
                        Ogun State Independent Electoral Commission
                    </p>
                </div>

                <div class="text-sm text-slate-500">
                    {{ auth()->user()->name }}
                </div>

            </div>
        </header>


        {{-- Page content --}}
        <main class="p-6 lg:p-8">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>
