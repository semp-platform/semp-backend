@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-950">
                Public Content
            </h1>

            <p class="mt-2 text-base font-medium text-gray-700">
                Manage news, notices, announcements and press releases.
            </p>
        </div>

        <a
            href="{{ route('web.public-content.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
        >
            + Create Content
        </a>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div
            class="mb-6 rounded-lg border border-green-300 bg-green-50 px-5 py-4 text-sm font-semibold text-green-900"
        >
            {{ session('success') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div
            class="mb-6 rounded-lg border border-red-300 bg-red-50 px-5 py-4 text-sm font-semibold text-red-900"
        >
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Filters --}}
    <div class="mb-8 rounded-xl border border-gray-300 bg-white p-5 shadow-sm">
        <form
            method="GET"
            action="{{ route('web.public-content.index') }}"
            class="grid grid-cols-1 gap-5 md:grid-cols-3"
        >

            {{-- Content Type --}}
            <div>
                <label
                    for="type"
                    class="mb-2 block text-sm font-bold text-gray-950"
                >
                    Content Type
                </label>

                <select
                    name="type"
                    id="type"
                    class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 text-sm font-medium text-gray-950 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-100"
                >
                    <option value="">All Types</option>

                    @foreach([
                        'news' => 'News',
                        'notice' => 'Notice',
                        'announcement' => 'Announcement',
                        'press_release' => 'Press Release',
                    ] as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(request('type') === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Status --}}
            <div>
                <label
                    for="status"
                    class="mb-2 block text-sm font-bold text-gray-950"
                >
                    Status
                </label>

                <select
                    name="status"
                    id="status"
                    class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 text-sm font-medium text-gray-950 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-100"
                >
                    <option value="">All Statuses</option>

                    <option
                        value="published"
                        @selected(request('status') === 'published')
                    >
                        Published
                    </option>

                    <option
                        value="draft"
                        @selected(request('status') === 'draft')
                    >
                        Draft
                    </option>
                </select>
            </div>


            {{-- Filter Buttons --}}
            <div class="flex items-end gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-gray-950 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200"
                >
                    Filter
                </button>

                <a
                    href="{{ route('web.public-content.index') }}"
                    class="rounded-lg border border-gray-400 bg-white px-5 py-3 text-sm font-bold text-gray-900 transition hover:bg-gray-100"
                >
                    Reset
                </a>

            </div>
        </form>
    </div>


    {{-- Content Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm">

        @if($contents->count())

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    {{-- Table Header --}}
                    <thead class="border-b border-gray-300 bg-gray-100">
                        <tr>

                            <th
                                class="px-5 py-4 text-left text-xs font-extrabold uppercase tracking-wider text-gray-950"
                            >
                                Title
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-extrabold uppercase tracking-wider text-gray-950"
                            >
                                Type
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-extrabold uppercase tracking-wider text-gray-950"
                            >
                                Status
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-extrabold uppercase tracking-wider text-gray-950"
                            >
                                Published
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-extrabold uppercase tracking-wider text-gray-950"
                            >
                                Flags
                            </th>

                            <th
                                class="px-5 py-4 text-right text-xs font-extrabold uppercase tracking-wider text-gray-950"
                            >
                                Actions
                            </th>

                        </tr>
                    </thead>


                    {{-- Table Body --}}
                    <tbody class="divide-y divide-gray-300">

                        @foreach($contents as $content)

                            <tr class="transition hover:bg-gray-50">

                                {{-- Title --}}
                                <td class="px-5 py-5 align-top">

                                    <div class="max-w-md text-base font-bold leading-6 text-gray-950">
                                        {{ $content->title }}
                                    </div>

                                    @if($content->summary)
                                        <div class="mt-1.5 max-w-md text-sm font-medium leading-5 text-gray-700">
                                            {{ $content->summary }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Type --}}
                                <td class="whitespace-nowrap px-5 py-5 align-top">

                                    <span class="inline-flex rounded-full bg-gray-200 px-3 py-1 text-xs font-bold text-gray-900">
                                        {{ ucwords(str_replace('_', ' ', $content->type)) }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="whitespace-nowrap px-5 py-5 align-top">

                                    @if($content->isCurrentlyPublished())

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-900">
                                            Published
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-900">
                                            Draft / Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Published --}}
                                <td class="whitespace-nowrap px-5 py-5 align-top text-sm font-semibold text-gray-800">

                                    {{ $content->published_at?->format('d M Y H:i') ?? '—' }}

                                </td>


                                {{-- Flags --}}
                                <td class="whitespace-nowrap px-5 py-5 align-top">

                                    <div class="flex flex-wrap gap-2">

                                        @if($content->is_featured)

                                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-900">
                                                Featured
                                            </span>

                                        @endif


                                        @if($content->is_ticker)

                                            <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-900">
                                                Ticker
                                            </span>

                                        @endif


                                        @if(!$content->is_featured && !$content->is_ticker)

                                            <span class="text-sm font-semibold text-gray-500">
                                                —
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-5 py-5 align-top">

                                    <div class="flex flex-wrap justify-end gap-2">

                                        {{-- View --}}
                                        <a
                                            href="{{ route('web.public-content.show', $content) }}"
                                            class="rounded-md border border-gray-400 bg-white px-3 py-2 text-xs font-bold text-gray-950 transition hover:bg-gray-100"
                                        >
                                            View
                                        </a>


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('web.public-content.edit', $content) }}"
                                            class="rounded-md bg-blue-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-800"
                                        >
                                            Edit
                                        </a>


                                        {{-- Publish / Unpublish --}}
                                        @if($content->isCurrentlyPublished())

                                            <form
                                                method="POST"
                                                action="{{ route('web.public-content.unpublish', $content) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-yellow-500 px-3 py-2 text-xs font-bold text-gray-950 transition hover:bg-yellow-600"
                                                >
                                                    Unpublish
                                                </button>
                                            </form>

                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('web.public-content.publish', $content) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-green-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-green-800"
                                                >
                                                    Publish
                                                </button>
                                            </form>

                                        @endif


                                        {{-- Delete --}}
                                        <form
                                            method="POST"
                                            action="{{ route('web.public-content.destroy', $content) }}"
                                            onsubmit="return confirm('Delete this content? This cannot be undone.');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-md bg-red-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-red-800"
                                            >
                                                Delete
                                            </button>
                                        </form>

                                    </div>


                                    {{-- Featured / Ticker Actions --}}
                                    <div class="mt-3 flex flex-wrap justify-end gap-4">

                                        <form
                                            method="POST"
                                            action="{{ route('web.public-content.featured', $content) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="text-xs font-bold text-blue-800 hover:text-blue-950 hover:underline"
                                            >
                                                {{ $content->is_featured ? 'Remove Featured' : 'Make Featured' }}
                                            </button>
                                        </form>


                                        <form
                                            method="POST"
                                            action="{{ route('web.public-content.ticker', $content) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="text-xs font-bold text-orange-800 hover:text-orange-950 hover:underline"
                                            >
                                                {{ $content->is_ticker ? 'Remove Ticker' : 'Make Ticker' }}
                                            </button>
                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="border-t border-gray-300 px-5 py-5">
                {{ $contents->links() }}
            </div>

        @else

            {{-- Empty State --}}
            <div class="px-6 py-16 text-center">

                <h2 class="text-xl font-bold text-gray-950">
                    No public content yet
                </h2>

                <p class="mt-2 text-base font-medium text-gray-700">
                    Create your first news item, notice, announcement or press release.
                </p>

                <a
                    href="{{ route('web.public-content.create') }}"
                    class="mt-6 inline-flex rounded-lg bg-blue-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-800"
                >
                    Create Content
                </a>

            </div>

        @endif

    </div>

</div>
@endsection
