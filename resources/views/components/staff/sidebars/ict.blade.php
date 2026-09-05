<div class="border-b border-slate-800 px-6 py-6">

    <div class="text-lg font-bold text-white">
        ICT
    </div>

    <div class="mt-1 text-sm text-slate-400">
        OGSIEC Staff
    </div>

</div>


<nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">

    <a
        href="{{ route('staff.ict.dashboard') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-slate-800"
    >
        Dashboard
    </a>


    <div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Nomination Management
    </div>

    <a
        href="{{ route('staff.ict.nomination-batches.index') }}"
        class="block rounded-lg px-4 py-3 text-sm text-white hover:bg-slate-800"
    >
        Nomination Batches
    </a>

    <a
        href="{{ route('staff.ict.nominations.index') }}"
        class="block rounded-lg px-4 py-3 text-sm text-white hover:bg-slate-800"
    >
        Nominations
    </a>


    <div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Candidate Records
    </div>

    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Candidate Documents
    </a>


    <div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Publication & Release
    </div>

    <a
    href="{{ route('web.public-content.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-white hover:bg-slate-800"
>
    Publication & Release
</a>
{{-- Election Notices --}}
<div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
    Communications
</div>

<a
    href="{{ route('staff.ict.election-notices.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-white hover:bg-slate-800"
>
    Election Notices
</a>
{{-- Results --}}
<div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
    Results
</div>

<a
    href="{{ route('staff.ict.results.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-white hover:bg-slate-800"
>
    Results Management
</a>

<a
    href="{{ route('staff.ict.results.create') }}"
    class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Upload Results
</a>

<a
    href="{{ route('staff.ict.results.index') }}"
    class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Imported Results
</a>


    <div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Production & Export
    </div>

    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Production & Export
    </a>


    <div class="px-2 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Workflow
    </div>

    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Workflow History
    </a>

</nav>


<div class="border-t border-slate-800 p-4">

    <div class="mb-4 px-2">

        <p class="text-sm font-medium text-white">
            {{ auth()->user()->name }}
        </p>

        <p class="mt-1 truncate text-xs text-slate-400">
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
            class="w-full rounded-lg bg-slate-800 px-4 py-3 text-left text-sm text-white transition hover:bg-slate-700"
        >
            Sign Out
        </button>

    </form>

</div>
