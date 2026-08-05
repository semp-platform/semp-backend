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

    <x-admin.sidebar />


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

    <x-admin.alerts />

    @yield('content')

</main>

    </div>

</div>
@stack('scripts')
</body>
</html>
