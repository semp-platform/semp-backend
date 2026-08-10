<div class="border-b border-slate-800 px-6 py-6">
    <div class="text-lg font-bold">
        Finance
    </div>

    <div class="mt-1 text-sm text-slate-400">
        OGSIEC Staff
    </div>
</div>

<nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

    {{-- Dashboard --}}
    <a
        href="{{ route('finance.dashboard') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-slate-800"
    >
        Dashboard
    </a>

    {{-- Finance --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Finance
    </div>

    <a
        href="{{ route('finance.payments.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-slate-800"
    >
        Batch Payments
    </a>

</nav>

<div class="border-t border-slate-800 p-4">

    <div class="mb-4 px-2">
        <p class="text-sm font-medium text-white">
            {{ auth()->user()->name }}
        </p>

        <p class="truncate text-xs text-slate-400">
            {{ auth()->user()->email }}
        </p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button
            type="submit"
            class="w-full rounded-lg border border-slate-800 bg-slate-800 px-4 py-2.5 text-left text-sm text-white transition hover:bg-slate-700"
        >
            Sign Out
        </button>
    </form>

</div>
