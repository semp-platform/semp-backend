<header class="border-b bg-white px-8 py-4">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-xl font-bold">

                OGSIEC Staff Portal

            </h1>

        </div>

        <div class="text-right">

            <div class="font-semibold">

                {{ auth()->user()->name }}

            </div>

            <div class="text-sm text-slate-500">

                {{ auth()->user()->getRoleNames()->first() }}

            </div>

        </div>

    </div>

</header>
