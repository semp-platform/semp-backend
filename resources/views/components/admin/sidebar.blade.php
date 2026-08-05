<aside class="hidden w-72 shrink-0 bg-emerald-950 text-white lg:flex lg:flex-col">

    <div class="border-b border-white/10 px-6 py-6">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-white font-bold text-emerald-900">
                OG
            </div>

            <div>

                <p class="font-bold tracking-wide">
                    SEMP
                </p>

                <p class="text-xs text-emerald-200">
                    OGSIEC Staff Portal
                </p>

            </div>

        </div>

    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

        <a
            href="{{ route('dashboard') }}"
            class="block rounded-lg px-4 py-3 text-sm font-medium
            {{ request()->routeIs('dashboard')
                ? 'bg-white/10 text-white'
                : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}"
        >
            Dashboard
        </a>

        @can('elections.view')

            <div class="px-4 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-emerald-400">
                Election Management
            </div>

            <a
                href="{{ route('elections.index') }}"
                class="block rounded-lg px-4 py-2 text-sm text-emerald-100 hover:bg-white/5"
            >
                Elections
            </a>

        @endcan

        @can('nominations.view')

            <div class="px-4 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-emerald-400">
                Nominations
            </div>

            <a
                href="{{ route('nominations.index') }}"
                class="block rounded-lg px-4 py-2 text-sm text-emerald-100 hover:bg-white/5"
            >
                Candidate Nominations
            </a>

        @endcan

        @can('payments.view')

            <div class="px-4 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-emerald-400">
                Finance
            </div>

            <a
                href="{{ route('finance.payments.index') }}"
                class="block rounded-lg px-4 py-2 text-sm text-emerald-100 hover:bg-white/5"
            >
                Batch Payments
            </a>

        @endcan

        @can('reports.view')

            <a
                href="{{ route('finance.reports.index') }}"
                class="block rounded-lg px-4 py-2 text-sm text-emerald-100 hover:bg-white/5"
            >
                Reports
            </a>

        @endcan

        @can('users.manage')

            <div class="px-4 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-emerald-400">
                Administration
            </div>

            <a
                href="{{ route('admin.users.index') }}"
                class="block rounded-lg px-4 py-2 text-sm text-emerald-100 hover:bg-white/5"
            >
                Users
            </a>

        @endcan

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
                class="w-full rounded-lg border border-white/10 px-4 py-2.5 text-left text-sm text-emerald-100 hover:bg-white/10"
            >
                Sign out
            </button>

        </form>

    </div>

</aside>
