<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Party Portal | SEMP')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">

<div class="min-h-screen lg:flex">

    @include('layouts.partials.party.sidebar')

    <div class="min-w-0 flex-1">

        <header class="border-b border-slate-200 bg-white">

            <div class="flex h-16 items-center justify-between px-6 lg:px-8">

                <div>
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $party->name }}
                    </p>

                    <p class="text-xs text-slate-500">
                        Political Party Portal
                    </p>
                </div>

               <div class="flex items-center gap-6">

    {{-- Notifications --}}
    <button
        type="button"
        class="relative rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"
            />

        </svg>

        <span
            class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white"
        >
            0
        </span>

    </button>

    {{-- User --}}
    <div class="text-right">

        <p class="text-sm font-semibold text-slate-900">
            {{ auth()->user()->name }}
        </p>

        <p class="text-xs text-slate-500">
            {{ auth()->user()->email }}
        </p>

    </div>

</div>

            </div>

        </header>


        <main class="p-6 lg:p-8">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>
