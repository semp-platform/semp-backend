<header class="border-b bg-white px-4 py-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between gap-4">

        {{-- Mobile menu button --}}
      <button
    type="button"
    @click="mobileSidebarOpen = true"
    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-lg text-slate-700 shadow-sm lg:hidden"
    aria-label="Open navigation"
>
    ☰
</button>


        {{-- Portal title --}}
        <div class="min-w-0 flex-1">

            <h1 class="truncate text-base font-bold sm:text-xl">
                OGSIEC Staff Portal
            </h1>

        </div>

        {{-- User --}}
        <div class="hidden text-right sm:block">

            <div class="font-semibold">
                {{ auth()->user()->name }}
            </div>

            <div class="text-sm text-slate-500">
                {{ auth()->user()->getRoleNames()->first() }}
            </div>

        </div>

    </div>

</header>
