 {{-- Sidebar --}}
    <aside class="hidden w-72 shrink-0 bg-emerald-950 text-white lg:flex lg:flex-col">

        <div class="border-b border-white/10 px-6 py-6">
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-white font-bold text-emerald-900">
                    OG
                </div>

                <div>
                    <p class="font-bold tracking-wide">SEMP</p>
                    <p class="text-xs text-emerald-200">
                        Election Management Platform
                    </p>
                </div>

            </div>
        </div>

<nav class="flex-1 space-y-1 px-4 py-6">

    <a href="{{ route('dashboard') }}"
       class="block rounded-lg px-4 py-3 text-sm font-medium
       {{ request()->routeIs('dashboard')
            ? 'bg-white/10 text-white'
            : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
        Dashboard
    </a>

    {{-- Election Management --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
        Election Management
    </div>

    <a href="{{ route('elections.index') }}"
       class="ml-3 block rounded-lg px-4 py-2 text-sm
       {{ request()->routeIs('elections.*')
            ? 'bg-white/10 text-white'
            : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
        Elections
    </a>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Election Types
    </span>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Positions
    </span>

    {{-- Nominations --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
        Nominations
    </div>

    <a href="{{ route('nominations.index') }}"
       class="ml-3 block rounded-lg px-4 py-2 text-sm
       {{ request()->routeIs('nominations.*')
            ? 'bg-white/10 text-white'
            : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
        Nominations
    </a>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Documents
    </span>

    {{-- Political Parties --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
        Political Parties
    </div>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        <a href="{{ route('admin.political-parties.index') }}"
   class="ml-3 block rounded-lg px-4 py-2 text-sm
   {{ request()->routeIs('admin.political-parties.*')
        ? 'bg-white/10 text-white'
        : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
    Political Parties
</a>
    </span>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Party Officials
    </span>

    {{-- Candidates --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
        Candidates
    </div>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Candidates
    </span>

    {{-- Reference Data --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
        Reference Data
    </div>

    <a href="{{ route('admin.document-types.index') }}"
       class="ml-3 block rounded-lg px-4 py-2 text-sm
       {{ request()->routeIs('admin.document-types.*')
            ? 'bg-white/10 text-white'
            : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
        Document Types
    </a>

    {{-- Administration --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-emerald-400">
        Administration
    </div>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        <a href="{{ route('admin.users.index') }}"
   class="ml-3 block rounded-lg px-4 py-2 text-sm
   {{ request()->routeIs('admin.users.*')
        ? 'bg-white/10 text-white'
        : 'text-emerald-100 hover:bg-white/5 hover:text-white' }}">
    Users
</a>
    </span>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Roles
    </span>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Permissions
    </span>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        Audit Logs
    </span>

    <span class="ml-3 block rounded-lg px-4 py-2 text-sm text-emerald-300/60">
        System Settings
    </span>

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
                    class="w-full rounded-lg border border-white/10 px-4 py-2.5 text-left text-sm text-emerald-100 transition hover:bg-white/10 hover:text-white"
                >
                    Sign out
                </button>
            </form>
        </div>

    </aside>
