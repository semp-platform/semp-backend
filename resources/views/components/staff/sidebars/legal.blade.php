<div class="border-b border-slate-800 px-6 py-6">
    <div class="text-lg font-bold">
    Legal & Compliance
</div>

    <div class="mt-1 text-sm text-slate-400">
        OGSIEC Staff
    </div>
</div>

<nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

    {{-- Dashboard --}}
    <a
        href="{{ route('staff.legal.dashboard') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium
            {{ request()->routeIs('staff.legal.dashboard')
                ? 'bg-white/10 text-white'
                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
    >
        Dashboard
    </a>


    {{-- Legal Review --}}
<div class="px-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
    Legal & Compliance
</div>

<a
    href="{{ route('staff.legal.nominations.index') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('staff.legal.nominations.*')
            ? 'bg-white/10 text-white'
            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
>
    Legal Review
</a>


    {{-- Legal & Compliance --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Legal & Compliance
    </div>

    <a
        href="{{ route('staff.legal.documents.index') }}"
        class="block rounded-lg px-4 py-3 text-sm
            {{ request()->routeIs('staff.legal.documents.*')
                ? 'bg-white/10 text-white'
                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
    >
        Court Orders & Injunctions
    </a>


    {{-- Records --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Records
    </div>

<a
    href="{{ route('staff.legal.candidates.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Candidates
</a>

<a
    href="{{ route('staff.legal.political-parties.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Political Parties
</a>

<a
    href="{{ route('staff.legal.workflow-history.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Workflow History & Activity Logs
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
