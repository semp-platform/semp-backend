<aside class="hidden w-72 bg-slate-900 text-white lg:flex lg:flex-col">

    <div class="border-b border-slate-700 p-6">

        <h2 class="text-xl font-bold">
            Finance
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            OGSIEC Staff
        </p>

    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6">

        {{-- Dashboard --}}
        <a
            href="{{ route('finance.dashboard') }}"
            class="block rounded-lg px-4 py-3 text-sm font-medium
            {{ request()->routeIs('finance.dashboard')
                ? 'bg-white/10 text-white'
                : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
        >
            Dashboard
        </a>

        {{-- Payments --}}
        <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
            Payments
        </div>

        <a
            href="{{ route('finance.payments.index') }}"
            class="block rounded-lg px-4 py-3 text-sm font-medium
            {{ request()->routeIs('finance.payments.*')
                ? 'bg-white/10 text-white'
                : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
        >
            Batch Payments
        </a>

        <a
            href="#"
            class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-500 cursor-not-allowed"
        >
            Receipts
        </a>

        {{-- Reports --}}
        <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
            Reports
        </div>

        <a
            href="{{ route('finance.reports.index') }}"
            class="block rounded-lg px-4 py-3 text-sm font-medium
            {{ request()->routeIs('finance.reports.*')
                ? 'bg-white/10 text-white'
                : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
        >
            Payment Reports
        </a>

        <a
            href="#"
            class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-500 cursor-not-allowed"
        >
            Payments by Party
        </a>

        <a
            href="#"
            class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-500 cursor-not-allowed"
        >
            Transaction History
        </a>

    </nav>

    <div class="border-t border-slate-700 p-4">

        <div class="mb-3 px-2">

            <p class="text-sm font-medium text-white">
                {{ auth()->user()->name }}
            </p>

            <p class="truncate text-xs text-slate-400">
                {{ auth()->user()->email }}
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="w-full rounded-lg border border-slate-700 px-4 py-2.5 text-left text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                Sign Out
            </button>

        </form>

    </div>

</aside>
