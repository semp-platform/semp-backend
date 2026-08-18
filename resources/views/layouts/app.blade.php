<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SEMP')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">

<div
    class="min-h-screen lg:flex"
    x-data="{ mobileSidebarOpen: false }"
    @close-mobile-sidebar.window="mobileSidebarOpen = false"
>
    <x-admin.sidebar />


    {{-- Main application --}}
    <div class="min-w-0 flex-1">

        {{-- Top bar --}}
        <header class="border-b border-slate-200 bg-white">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- Mobile menu button --}}
        <button
            type="button"
            @click="mobileSidebarOpen = true"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-xl text-slate-700 shadow-sm transition hover:bg-slate-50 lg:hidden"
            aria-label="Open navigation"
        >
            ☰
        </button>

        {{-- Portal name --}}
        <div class="min-w-0 flex-1">

            <p class="truncate text-sm font-semibold text-slate-900 sm:text-base">
                Ogun State Independent Electoral Commission
            </p>

        </div>

        {{-- Desktop user name --}}
        <div class="hidden text-right text-sm text-slate-500 sm:block">
            {{ auth()->user()->name }}
        </div>

    </div>
</header>

       {{-- Page content --}}
<main class="p-6 lg:p-8">

    <x-admin.alerts />

    @yield('content')

</main>

    </div>

</div>
@stack('scripts')
</body>
</html>
