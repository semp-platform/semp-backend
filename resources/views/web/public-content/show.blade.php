@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a
                href="{{ route('web.public-content.index') }}"
                class="text-sm font-semibold text-blue-800 hover:text-blue-900 hover:underline"
            >
                ← Back to Public Content
            </a>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-black">
                Content Preview
            </h1>

            <p class="mt-1 text-sm font-medium text-gray-700">
                Review this public content before making changes.
            </p>
        </div>

        <a
            href="{{ route('web.public-content.edit', $publicContent) }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"
        >
            Edit Content
        </a>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="mb-6 rounded-lg border-2 border-green-300 bg-green-50 px-4 py-3 text-sm font-semibold text-green-900">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main content --}}
    <article class="overflow-hidden rounded-xl border-2 border-gray-300 bg-white shadow-sm">

        {{-- Featured image --}}
        @if($publicContent->image_path)
            <div class="border-b-2 border-gray-300 bg-gray-100">
                <img
                    src="{{ asset('storage/' . $publicContent->image_path) }}"
                    alt="{{ $publicContent->title }}"
                    class="mx-auto max-h-[450px] w-full object-cover"
                >
            </div>
        @endif

        <div class="p-6 md:p-8">

            {{-- Status badges --}}
            <div class="mb-5 flex flex-wrap items-center gap-2">

                <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-bold text-black">
                    {{ ucwords(str_replace('_', ' ', $publicContent->type)) }}
                </span>

                @if($publicContent->isCurrentlyPublished())
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-900">
                        Published
                    </span>
                @else
                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-900">
                        Draft / Inactive
                    </span>
                @endif

                @if($publicContent->is_featured)
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-900">
                        Featured
                    </span>
                @endif

                @if($publicContent->is_ticker)
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-900">
                        Ticker
                    </span>
                @endif

            </div>

            {{-- Title --}}
            <h2 class="text-3xl font-bold leading-tight tracking-tight text-black md:text-4xl">
                {{ $publicContent->title }}
            </h2>

            {{-- Publication date --}}
            <div class="mt-4 text-sm font-semibold text-gray-700">
                @if($publicContent->published_at)
                    Published {{ $publicContent->published_at->format('d M Y \a\t H:i') }}
                @else
                    Not yet published
                @endif
            </div>

            {{-- Summary --}}
            @if($publicContent->summary)
                <div class="mt-7 rounded-lg border-2 border-gray-200 bg-gray-50 p-5">
                    <p class="text-base font-semibold leading-7 text-gray-900">
                        {{ $publicContent->summary }}
                    </p>
                </div>
            @endif

            {{-- Content --}}
            <div class="mt-8 whitespace-normal text-base leading-8 text-black md:text-lg md:leading-9">
                {!! nl2br(e($publicContent->content)) !!}
            </div>

            {{-- Attachment --}}
            @if($publicContent->attachment_path)
                <div class="mt-8 border-t-2 border-gray-300 pt-6">
                    <h3 class="text-base font-bold text-black">
                        Attachment
                    </h3>

                    <a
                        href="{{ asset('storage/' . $publicContent->attachment_path) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-3 inline-flex items-center rounded-lg border-2 border-gray-400 px-4 py-2.5 text-sm font-bold text-blue-800 transition hover:bg-gray-100"
                    >
                        View / Download Attachment
                    </a>
                </div>
            @endif

        </div>
    </article>

    {{-- Publication Controls --}}
    <div class="mt-6 rounded-xl border-2 border-gray-300 bg-white p-6 shadow-sm">

        <h2 class="mb-1 text-xl font-bold text-black">
            Publication Controls
        </h2>

        <p class="mb-5 text-sm font-medium text-gray-700">
            Manage the publication status and visibility of this content.
        </p>

        <div class="flex flex-wrap gap-3">

            @if($publicContent->isCurrentlyPublished())
                <form
                    method="POST"
                    action="{{ route('web.public-content.unpublish', $publicContent) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-bold text-black shadow-sm transition hover:bg-yellow-400"
                    >
                        Unpublish
                    </button>
                </form>
            @else
                <form
                    method="POST"
                    action="{{ route('web.public-content.publish', $publicContent) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-green-800"
                    >
                        Publish
                    </button>
                </form>
            @endif

            <form
                method="POST"
                action="{{ route('web.public-content.featured', $publicContent) }}"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="rounded-lg border-2 border-blue-400 bg-white px-4 py-2.5 text-sm font-bold text-blue-900 transition hover:bg-blue-50"
                >
                    {{ $publicContent->is_featured ? 'Remove Featured' : 'Make Featured' }}
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('web.public-content.ticker', $publicContent) }}"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="rounded-lg border-2 border-orange-400 bg-white px-4 py-2.5 text-sm font-bold text-orange-900 transition hover:bg-orange-50"
                >
                    {{ $publicContent->is_ticker ? 'Remove Ticker' : 'Make Ticker' }}
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('web.public-content.destroy', $publicContent) }}"
                onsubmit="return confirm('Delete this content? This cannot be undone.');"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-lg border-2 border-red-400 bg-white px-4 py-2.5 text-sm font-bold text-red-800 transition hover:bg-red-50"
                >
                    Delete
                </button>
            </form>

        </div>
    </div>

    {{-- Audit information --}}
    <div class="mt-6 rounded-xl border-2 border-gray-300 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-black">
            Record Information
        </h2>

        <div class="space-y-2 text-sm text-gray-800">

            <div>
                <span class="font-bold text-black">Created by:</span>
                <span class="font-medium">
                    {{ $publicContent->creator?->name ?? 'System' }}
                </span>
            </div>

            @if($publicContent->updated_by)
                <div>
                    <span class="font-bold text-black">Last updated by:</span>
                    <span class="font-medium">
                        {{ $publicContent->updater?->name ?? 'System' }}
                    </span>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
