<aside class="hidden w-72 bg-slate-900 text-white lg:flex lg:flex-col">

    <div class="border-b border-slate-700 p-6">

        <h2 class="text-xl font-bold">

            Finance

        </h2>

        <p class="mt-1 text-sm text-slate-400">

            OGSIEC Staff

        </p>

    </div>

    <nav class="flex-1 space-y-2 p-4">

        <a
    href="{{ route('finance.dashboard') }}"
    class="block rounded-lg px-4 py-3 hover:bg-slate-800"
>
    Dashboard
</a>

<a
    href="{{ route('finance.payments.index') }}"
    class="block rounded-lg px-4 py-3 hover:bg-slate-800"
>
    Batch Payments
</a>
<a
    href="{{ route('finance.reports.index') }}"
    class="flex items-center rounded-lg px-4 py-3 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white"
>
    Reports
</a>

    </nav>
<div class="mt-auto border-t border-slate-700 p-4">

    <div class="mb-3">

        <p class="font-medium text-white">
            {{ auth()->user()->name }}
        </p>

        <p class="text-sm text-slate-400">
            {{ auth()->user()->email }}
        </p>

    </div>

    <form
        method="POST"
        action="{{ route('logout') }}"
    >
        @csrf

        <button
            class="w-full rounded-lg bg-slate-800 px-4 py-2 text-left text-white hover:bg-slate-700"
        >
            Sign Out
        </button>

    </form>

</div>
</aside>
