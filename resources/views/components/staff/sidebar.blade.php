{{-- =========================================================
     DESKTOP SIDEBAR
     ========================================================= --}}

<aside class="hidden min-h-screen w-64 shrink-0 flex-col bg-slate-900 text-white lg:flex">

    @role('ICT Officer')
        @include('components.staff.sidebars.ict')

    @elserole('EPM Officer')
        @include('components.staff.sidebars.epm')

    @elserole('Legal Officer')
        @include('components.staff.sidebars.legal')

    @elserole('Commissioner')
        @include('components.staff.sidebars.commissioner')

    @elserole('Finance Officer')
        @include('components.staff.sidebars.finance-officer')

    @elserole('Political Party Officer')
        @include('components.staff.sidebars.political-party')

    @elserole('Election Administrator')
        @include('components.staff.sidebars.election-administrator')

    @elserole('Super Admin')
        @include('components.staff.sidebars.admin')

    @else

        <div class="flex flex-1 items-center justify-center px-6">

            <div class="text-center">

                <p class="font-semibold">
                    No sidebar configured.
                </p>

                <p class="mt-2 text-sm text-slate-400">
                    Please contact the system administrator.
                </p>

            </div>

        </div>

    @endrole

</aside>

{{-- =========================================================
     MOBILE SIDEBAR
     ========================================================= --}}

<div
    x-cloak
    x-show="mobileSidebarOpen"
    class="fixed inset-0 z-[100] lg:hidden"
    aria-label="Mobile navigation"
>

    {{-- Backdrop --}}
    <button
        type="button"
        class="absolute inset-0 h-full w-full bg-slate-950/60"
        @click="mobileSidebarOpen = false"
        aria-label="Close navigation"
    ></button>

    {{-- Mobile drawer --}}
    <aside
        class="relative z-10 flex h-full w-72 max-w-[85vw] flex-col bg-slate-900 text-white shadow-2xl"
    >

        {{-- Mobile header --}}
        <div class="flex shrink-0 items-center justify-between border-b border-white/10 px-5 py-4">

            <div>
                <p class="text-sm font-bold">
                    SEMP
                </p>

                <p class="text-xs text-slate-400">
                    OGSIEC Staff Portal
                </p>
            </div>

            <button
                type="button"
                @click="mobileSidebarOpen = false"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/10 text-xl font-bold text-white transition hover:bg-white/20"
                aria-label="Close navigation"
            >
                ×
            </button>

        </div>

        {{-- Same Commissioner / role navigation --}}
        <div class="min-h-0 flex-1 overflow-y-auto">

            @role('ICT Officer')

                @include('components.staff.sidebars.ict')

            @elserole('EPM Officer')

                @include('components.staff.sidebars.epm')

            @elserole('Legal Officer')

                @include('components.staff.sidebars.legal')

            @elserole('Commissioner')

                @include('components.staff.sidebars.commissioner')

            @elserole('Finance Officer')

                @include('components.staff.sidebars.finance-officer')

            @elserole('Political Party Officer')

                @include('components.staff.sidebars.political-party')

            @elserole('Election Administrator')

                @include('components.staff.sidebars.election-administrator')

            @elserole('Super Admin')

                @include('components.staff.sidebars.admin')

            @else

                <div class="flex items-center justify-center px-6 py-10">

                    <div class="text-center">

                        <p class="font-semibold">
                            No sidebar configured.
                        </p>

                        <p class="mt-2 text-sm text-slate-400">
                            Please contact the system administrator.
                        </p>

                    </div>

                </div>

            @endrole

        </div>

    </aside>

</div>
