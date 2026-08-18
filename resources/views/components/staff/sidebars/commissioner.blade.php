<div class="border-b border-slate-800 px-6 py-6">
    <div class="text-lg font-bold">
        Commissioner
    </div>

    <div class="mt-1 text-sm text-slate-400">
        OGSIEC Staff
    </div>
</div>


<nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">


    {{-- Dashboard --}}
    <div class="px-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Dashboard / Work Queue
    </div>

    <a
        href="{{ route('staff.commissioner.nominations.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-slate-800"
    >
        Commissioner Dashboard
    </a>



    {{-- Decisions --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Decisions & Approvals
    </div>


    <a
        href="{{ route('staff.commissioner.nominations.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-slate-800"
    >
        Nominations Awaiting Decision
    </a>


    <a
        href="{{ route('staff.commissioner.withdrawals.index') }}"
        class="block rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-slate-800"
    >
        Withdrawals & Substitutions Awaiting Decision
    </a>


    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Candidate Changes Awaiting Decision
    </a>


    <a
        href="{{ route('staff.commissioner.decisions.index') }}"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Approved / Returned Decisions
    </a>




    {{-- Legal Documents --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Legal Documents (Read Only)
    </div>


    <a
    href="{{ route('staff.commissioner.documents.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Court Orders & Injunctions
</a>


    {{-- Document Review --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Document Review
    </div>


    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Document Corrections
    </a>




    {{-- Records --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Records
    </div>


    <a
    href="{{ route('staff.commissioner.candidates.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Candidates
</a>


    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Political Parties
    </a>




    {{-- Publication --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Publication
    </div>


    <a
    href="{{ route('staff.commissioner.approved-candidates.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Approved Candidates Pool
</a>

    <a
    href="{{ route('staff.commissioner.final-publication.index') }}"
    class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Final Publication Approval
</a>




    {{-- Reporting --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Reporting
    </div>


    <a
        href="#"
        class="block rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"
    >
        Reports
    </a>




    {{-- Audit --}}
    <div class="px-4 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Audit
    </div>


    <a
        href="#"
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
