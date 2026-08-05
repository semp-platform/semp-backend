@role('Finance Officer')

    @include('components.staff.sidebars.finance-officer')

@elserole('Nomination Officer')

    @include('components.staff.sidebars.nomination-officer')

@elserole('Senior Nomination Officer')

    @include('components.staff.sidebars.senior-nomination-officer')

@elserole('Operations Officer')

    @include('components.staff.sidebars.operations-officer')

@elserole('Communications Officer')

    @include('components.staff.sidebars.communications-officer')

@elserole('Commissioner')

    @include('components.staff.sidebars.commissioner')

@elserole('Election Administrator')

    @include('components.staff.sidebars.election-administrator')

@else

    <aside class="hidden w-72 bg-slate-900 text-white lg:flex items-center justify-center">

        <div class="text-center">

            No sidebar configured.

        </div>

    </aside>

@endrole
