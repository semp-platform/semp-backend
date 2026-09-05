{{-- Mobile backdrop --}}
<div
    x-cloak
    x-show="mobileSidebarOpen"
    class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden"
    @click="mobileSidebarOpen = false"
    aria-hidden="true"
></div>


{{-- Party sidebar --}}
<aside
    class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 shrink-0 -translate-x-full flex-col bg-slate-950 text-white shadow-2xl transition-transform duration-200 lg:static lg:z-auto lg:h-auto lg:translate-x-0 lg:shadow-none"
    :class="{ 'translate-x-0': mobileSidebarOpen }"
>
        <div class="border-b border-white/10 px-6 py-6">

<div class="flex items-center justify-between gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-white font-bold text-slate-900">
                    {{ $party->acronym }}
                </div>

                <div class="min-w-0">
                    <p class="font-bold tracking-wide">SEMP</p>

                    <p class="truncate text-xs text-slate-300">
                        Political Party Portal
                    </p>
                </div>

            </div>
<button
    type="button"
    @click="mobileSidebarOpen = false"
    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/10 text-xl font-bold text-white lg:hidden"
    aria-label="Close navigation"
>
    ×
</button>
            <div class="mt-5 rounded-lg bg-white/5 px-4 py-3">
                <p class="text-xs uppercase tracking-wider text-slate-400">
                    Representing
                </p>

                <p class="mt-1 text-sm font-semibold text-white">
                    {{ $party->name }}
                </p>
            </div>

        </div>


<nav class="min-h-0 flex-1 overflow-y-auto px-4 py-6">
    {{-- Dashboard --}}
    <a
        href="{{ route('party.dashboard') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('party.dashboard')
            ? 'bg-white/10 text-white'
            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
    >
        Dashboard
    </a>

    {{-- Candidate Nominations --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Candidate Nominations
    </div>

    <a
        href="{{ route('party.nominations.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('party.nominations.*')
            ? 'bg-white/10 text-white'
            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
    >
        Candidate Nominations
    </a>

    <a
        href="{{ route('party.nomination-batches.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white"
    >
        Nomination Batches
    </a>
<a
    href="{{ route('party.candidates.documents.list') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium
    {{ request()->routeIs('party.candidates.documents.*')
        ? 'bg-white/10 text-white'
        : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
>
    Candidate Documents
</a>
    {{-- Candidate Changes --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Candidate Changes
    </div>

    <a
    href="{{ route('party.withdrawals.index') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('party.withdrawals.*')
            ? 'bg-white/10 text-white'
            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
>
    Candidate Withdrawals
</a>

    <a
    href="{{ route('party.replacements.index') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium
    {{ request()->routeIs('party.replacements.*')
        ? 'bg-white/10 text-white'
        : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
>
    Candidate Replacements
</a>
        <a
        href="{{ route('party.returned-nominations.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('party.returned-nominations.*')
            ? 'bg-white/10 text-white'
            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
    >
        Returned Nominations
    </a>
{{-- Party Activities --}}
<div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
    Party Activities
</div>

<a
    href="{{ route('party.primary-notices.index') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium
    {{ request()->routeIs('party.primary-notices.*')
        ? 'bg-white/10 text-white'
        : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
>
    Party Primaries
</a>
   {{-- PAYMENTS --}}
<div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
    Payments
</div>

<a
    href="{{ route('party.payments.index') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium
    {{ request()->routeIs('party.payments.*')
        ? 'bg-white/10 text-white'
        : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
>
    Payment Dashboard
</a>

<a
    href="#"
    class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-500 cursor-not-allowed"
>
    Receipts
</a>
    {{-- Communications --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Communications
    </div>

    <a

    href="{{ route('party.inbox.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white"
    >
        Inbox
    </a>

    <a
    href="{{ route('party.election-notices.index') }}"
    class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white"
>
    Election Notices
</a>


    {{-- Reports --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Reports
    </div>

    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white"
    >
        Reports
    </a>

    {{-- Party --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Party
    </div>

    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white"
    >
        Party Profile
    </a>

</nav>

        <div class="border-t border-white/10 p-4">

            <div class="mb-3 px-2">

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
                    class="w-full rounded-lg border border-white/10 px-4 py-2.5 text-left text-sm text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    Sign out
                </button>
            </form>

        </div>

    </aside>
