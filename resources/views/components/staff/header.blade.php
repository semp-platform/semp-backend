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

        {{-- Notifications --}}
        <div class="relative">

            <details class="relative">

                <summary
                    class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50"
                    title="Notifications"
                >
                    <span class="text-lg">🔔</span>

                    @php
                        $unreadNotifications = auth()->user()
                            ->unreadNotifications()
                            ->latest()
                            ->get();
                    @endphp

                    @if($unreadNotifications->count())
                        <span
                            class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs font-bold text-white"
                        >
                            {{ $unreadNotifications->count() }}
                        </span>
                    @endif

                </summary>

                <div
                    class="absolute right-0 z-50 mt-2 w-96 max-w-[calc(100vw-2rem)] overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg"
                >

                    <div class="border-b border-slate-200 px-4 py-3">
                        <div class="font-semibold text-slate-800">
                            Notifications
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ $unreadNotifications->count() }} unread
                        </div>
                    </div>

                    @forelse($unreadNotifications as $notification)

                        <a
    href="{{ route('notifications.read', $notification->id) }}"
    class="block border-b border-slate-100 px-4 py-3 hover:bg-slate-50"
>

                            <div class="font-semibold text-sm text-slate-800">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </div>

                            <div class="mt-1 text-sm text-slate-600">
                                {{ $notification->data['message'] ?? '' }}
                            </div>

                        </a>

                    @empty

                        <div class="px-4 py-6 text-center text-sm text-slate-500">
                            No new notifications.
                        </div>

                    @endforelse

                </div>

            </details>

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
