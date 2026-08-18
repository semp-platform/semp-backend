@extends('layouts.public')

@section('title', 'Publications')

@section('meta_description')
    Official news, notices, announcements and press releases from the Ogun State Independent Electoral Commission.
@endsection

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Page heading --}}
    <div class="max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">
            OGSIEC Publications
        </p>

        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
            Publications
        </h1>

        <p class="mt-3 text-base leading-7 text-slate-600">
            Official news, notices, announcements and press releases
            from the Ogun State Independent Electoral Commission.
        </p>
    </div>


    {{-- Filters --}}
    <div class="mt-8 border-b border-slate-200 pb-4">

        <div class="flex flex-wrap gap-2">

            @php
                $filters = [
                    null => 'All',
                    'news' => 'News',
                    'notice' => 'Notices',
                    'announcement' => 'Announcements',
                    'press_release' => 'Press Releases',
                ];
            @endphp

            @foreach($filters as $value => $label)

                @php
                    $active = request('type') === $value;
                @endphp

                <a
                    href="{{ $value ? request()->fullUrlWithQuery(['type' => $value, 'page' => null]) : route('public.content.index') }}"
                    class="rounded-md px-4 py-2 text-sm font-medium transition
                        {{ $active
                            ? 'bg-emerald-700 text-white'
                            : 'bg-white text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50'
                        }}"
                >
                    {{ $label }}
                </a>

            @endforeach

        </div>

    </div>


    {{-- Content --}}
    @if($contents->isEmpty())

        <div class="mt-8 rounded-lg border border-slate-200 bg-white px-6 py-12 text-center">

            <h2 class="text-lg font-semibold text-slate-900">
                No publications available
            </h2>

            <p class="mt-2 text-sm text-slate-600">
                There are currently no published items in this category.
            </p>

        </div>

    @else

        <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200">

            @foreach($contents as $content)

                <article class="py-7 first:pt-0 last:pb-0">

                    <div class="grid gap-6 md:grid-cols-[180px_1fr]">

                        {{-- Image --}}
                        <div class="overflow-hidden rounded-lg bg-slate-100">

                            @if($content->image_path)

                                <img
                                    src="{{ asset('storage/' . $content->image_path) }}"
                                    alt="{{ $content->title }}"
                                    class="h-36 w-full object-cover md:h-28"
                                >

                            @else

                                <div class="flex h-36 items-center justify-center text-sm font-medium text-slate-400 md:h-28">
                                    OGSIEC
                                </div>

                            @endif

                        </div>


                        {{-- Content --}}
                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                                    @switch($content->type)

                                        @case('news')
                                            News
                                            @break

                                        @case('notice')
                                            Notice
                                            @break

                                        @case('announcement')
                                            Announcement
                                            @break

                                        @case('press_release')
                                            Press Release
                                            @break

                                        @default
                                            Publication

                                    @endswitch
                                </span>

                                @if($content->published_at)

                                    <span class="text-xs text-slate-500">
                                        {{ $content->published_at->format('d M Y') }}
                                    </span>

                                @endif

                                @if($content->is_featured)

                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        Featured
                                    </span>

                                @endif

                            </div>


                            <h2 class="mt-2 text-xl font-semibold text-slate-900">

                                <a
                                    href="{{ route('public.content.show', $content->slug) }}"
                                    class="hover:text-emerald-700"
                                >
                                    {{ $content->title }}
                                </a>

                            </h2>


                            @if($content->summary)

                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                                    {{ $content->summary }}
                                </p>

                            @endif


                            <div class="mt-4">

                                <a
                                    href="{{ route('public.content.show', $content->slug) }}"
                                    class="text-sm font-semibold text-slate-800 hover:text-emerald-700"
                                >
                                    Read publication
                                    <span aria-hidden="true">→</span>
                                </a>

                            </div>

                        </div>

                    </div>

                </article>

            @endforeach

        </div>


        {{-- Pagination --}}
        @if($contents->hasPages())

            <div class="mt-8">
                {{ $contents->links() }}
            </div>

        @endif

    @endif

</div>

@endsection
