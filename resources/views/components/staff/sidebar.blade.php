<aside class="hidden min-h-screen w-64 shrink-0 flex-col bg-slate-900 text-white lg:flex">

    @role('ICT Officer')

        @include('components.staff.sidebars.ict')

    @elserole('EPM Officer')

        @include('components.staff.sidebars.epm')

    @elserole('Legal Officer')

        @include('components.staff.sidebars.legal')

    @elserole('Approving Officer')

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
