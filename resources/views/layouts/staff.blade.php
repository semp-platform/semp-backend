<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    @yield('title') | SEMP
</title>

@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])

<div
    class="flex min-h-screen bg-slate-100"
    x-data="{ mobileSidebarOpen: false }"
>
    @include('components.staff.sidebar')

    <div class="flex min-h-screen flex-1 flex-col">

        @include('components.staff.header')

        <main class="flex-1 bg-slate-100 p-8">

            @if(session('success'))

                <div class="mb-6 rounded-lg bg-emerald-100 p-4 text-emerald-700">
                    {{ session('success') }}
                </div>

            @endif

            @if($errors->any())

                <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                    {{ $errors->first() }}
                </div>

            @endif

            @yield('content')

        </main>

    </div>

</div>
