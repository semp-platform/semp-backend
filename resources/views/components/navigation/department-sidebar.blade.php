@php
    $navigation = config("navigation.$department");

    $currentRoute = request()->route()?->getName();
@endphp

@if ($navigation)

    <aside class="w-64 shrink-0 border-r border-slate-200 bg-slate-950 text-white">

        <div class="border-b border-slate-800 px-5 py-5">

            <div class="text-sm font-semibold">
                OGSIEC Staff Portal
            </div>

            <div class="mt-1 text-xs text-slate-400">
                {{ $navigation['title'] }}
            </div>

        </div>

        <nav class="px-3 py-5 space-y-6">

            {{-- Dashboard --}}

            @if (!empty($navigation['dashboard']['route']))
                <a
                    href="{{ route($navigation['dashboard']['route']) }}"
                    class="flex items-center rounded-lg px-3 py-2 text-sm font-medium transition
                        {{ $currentRoute === $navigation['dashboard']['route']
                            ? 'bg-white/10 text-white'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                >
                    {{ $navigation['dashboard']['label'] }}
                </a>
            @endif


            {{-- Department sections --}}

            @foreach ($navigation['sections'] as $section)

                <div>

                    <div class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        {{ $section['label'] }}
                    </div>

                    <div class="space-y-1">

                        @foreach ($section['items'] as $item)

                            @if (!empty($item['route']))

                                <a
                                    href="{{ route($item['route']) }}"
                                    class="block rounded-lg px-3 py-2 text-sm transition
                                        {{ $currentRoute === $item['route']
                                            ? 'bg-white/10 text-white'
                                            : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                                >
                                    {{ $item['label'] }}
                                </a>

                            @else

                                <div
                                    class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-slate-500"
                                >
                                    <span>
                                        {{ $item['label'] }}
                                    </span>

                                    <span class="text-[10px] uppercase tracking-wide">
                                        Soon
                                    </span>
                                </div>

                            @endif

                        @endforeach

                    </div>

                </div>

            @endforeach

        </nav>

    </aside>

@endif
