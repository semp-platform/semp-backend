<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portal Login | SEMP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    <main class="min-h-screen lg:grid lg:grid-cols-2">

        {{-- Branding panel --}}
        <section class="relative hidden overflow-hidden bg-emerald-950 px-12 py-12 text-white lg:flex lg:flex-col lg:justify-between">

            <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border border-white/10"></div>
            <div class="absolute -bottom-40 -left-24 h-96 w-96 rounded-full border border-white/10"></div>

            <div class="relative">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white text-xl font-bold text-emerald-900">
                        OG
                    </div>

                    <div>
                        <p class="text-lg font-bold tracking-wide">OGSIEC</p>
                        <p class="text-sm text-emerald-100">
                            Ogun State Independent Electoral Commission
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative max-w-xl">
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-300">
                    State Election Management Platform
                </p>

                <h1 class="text-4xl font-bold leading-tight xl:text-5xl">
                    Secure election administration in one platform.
                </h1>

                <p class="mt-6 max-w-lg text-base leading-7 text-emerald-100">
                    SEMP provides authorized election officials and stakeholders
                    with secure access to election setup, nominations and
                    electoral administration services.
                </p>
            </div>

            <div class="relative text-sm text-emerald-200">
                &copy; {{ date('Y') }} Ogun State Independent Electoral Commission.
            </div>

        </section>


        {{-- Login panel --}}
        <section class="flex min-h-screen items-center justify-center px-6 py-12 sm:px-10">

            <div class="w-full max-w-md">

                {{-- Mobile identity --}}
                <div class="mb-10 flex items-center gap-3 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-900 font-bold text-white">
                        OG
                    </div>

                    <div>
                        <p class="font-bold text-emerald-950">OGSIEC</p>
                        <p class="text-xs text-slate-500">
                            State Election Management Platform
                        </p>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="mb-2 text-sm font-semibold text-emerald-700">
                        SEMP PORTAL
                    </p>

                    <h2 class="text-3xl font-bold tracking-tight text-slate-950">
                        Welcome back
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Sign in with your authorized account to continue.
                    </p>
                </div>


                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif


                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">

                    @csrf

                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Email address
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                            placeholder="name@example.com"
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                        >
                    </div>


                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label
                                for="password"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Password
                            </label>
                        </div>

                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                autocomplete="current-password"
                                required
                                placeholder="Enter your password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 pr-20 text-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10"
                            >

                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-slate-500 hover:text-emerald-800"
                            >
                                Show
                            </button>
                        </div>
                    </div>


                    <div class="flex items-center">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600"
                            >

                            Remember me
                        </label>
                    </div>


                    <button
                        type="submit"
                        class="w-full rounded-lg bg-emerald-800 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-900 focus:outline-none focus:ring-4 focus:ring-emerald-700/20"
                    >
                        Sign in to SEMP
                    </button>

                </form>


                <div class="mt-8 border-t border-slate-200 pt-6">
                    <p class="text-center text-xs leading-5 text-slate-500">
                        Access to this system is restricted to authorized users.
                        Activities may be logged for security and audit purposes.
                    </p>
                </div>

            </div>

        </section>

    </main>


    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');

        togglePassword.addEventListener('click', function () {
            const hidden = passwordInput.type === 'password';

            passwordInput.type = hidden ? 'text' : 'password';
            this.textContent = hidden ? 'Hide' : 'Show';
        });
    </script>

</body>
</html>s
