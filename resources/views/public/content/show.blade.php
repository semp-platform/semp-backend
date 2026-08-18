@extends('layouts.public')

@section('title', $content->title)

@section('meta_description')
    {{ $content->summary ?: $content->title }}
@endsection

@section('content')

<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Back --}}
    <div class="mb-8">

        <a
            href="{{ route('public.content.index') }}"
            class="text-sm font-medium text-slate-600 hover:text-emerald-700"
        >
            <span aria-hidden="true">←</span>
            Back to Publications
        </a>

    </div>


    {{-- Header --}}
    <header class="border-b border-slate-200 pb-8">

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

        </div>


        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
            {{ $content->title }}
        </h1>


        @if($content->summary)

            <p class="mt-4 text-lg leading-8 text-slate-600">
                {{ $content->summary }}
            </p>

        @endif

    </header>


    {{-- Featured image --}}
    @if($content->image_path)

        <div class="mt-8 overflow-hidden rounded-lg bg-slate-100">

            <img
                src="{{ asset('storage/' . $content->image_path) }}"
                alt="{{ $content->title }}"
                class="max-h-[520px] w-full object-cover"
            >

        </div>

    @endif


    {{-- Publication body --}}
    <article class="mt-10">

        <div class="prose prose-slate max-w-none leading-7">
            {!! $content->content !!}
        </div>

    </article>


    {{-- Attachment --}}
    @if($content->attachment_path)

        <section class="mt-10 border-t border-slate-200 pt-8">

            <h2 class="text-lg font-semibold text-slate-900">
                Related Document
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                Download the official document associated with this publication.
            </p>

            <a
                href="{{ asset('storage/' . $content->attachment_path) }}"
                target="_blank"
                rel="noopener"
                class="mt-4 inline-flex items-center rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
            >
                Download Document
                <span class="ml-2" aria-hidden="true">↓</span>
            </a>

        </section>

    @endif


    {{-- Back link --}}
    <div class="mt-12 border-t border-slate-200 pt-6">

        <a
            href="{{ route('public.content.index') }}"
            class="text-sm font-semibold text-slate-700 hover:text-emerald-700"
        >
            <span aria-hidden="true">←</span>
            Back to Publications
        </a>

    </div>

</div>

@endsection
