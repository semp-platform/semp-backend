@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="mb-8">
        <a
            href="{{ route('web.public-content.index') }}"
            class="inline-flex items-center text-base font-semibold text-blue-800 hover:text-blue-900 hover:underline"
        >
            ← Back to Public Content
        </a>

        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-950">
            Create Public Content
        </h1>

        <p class="mt-2 text-base text-gray-700">
            Create a news item, notice, announcement or press release.
        </p>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-8 rounded-xl border-2 border-red-300 bg-red-50 px-5 py-4 text-base text-red-900">
            <div class="mb-2 font-bold">
                Please correct the following errors:
            </div>

            <ul class="list-disc space-y-1 pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form
        method="POST"
        action="{{ route('web.public-content.store') }}"
        enctype="multipart/form-data"
        class="space-y-8"
    >
        @csrf


        {{-- =========================================================
             CONTENT DETAILS
        ========================================================== --}}
        <section class="rounded-2xl border-2 border-gray-300 bg-white p-6 shadow-sm sm:p-8">

            <div class="mb-7 border-b-2 border-gray-200 pb-5">
                <h2 class="text-xl font-bold text-gray-950">
                    Content Details
                </h2>

                <p class="mt-1 text-base text-gray-700">
                    Enter the main information that will appear on the public portal.
                </p>
            </div>


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label
                        for="title"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Title
                        <span class="text-red-700">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title') }}"
                        required
                        maxlength="255"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base text-gray-950 placeholder-gray-500 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        placeholder="Enter the content title"
                    >
                </div>


                {{-- Content Type --}}
                <div>
                    <label
                        for="type"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Content Type
                        <span class="text-red-700">*</span>
                    </label>

                    <select
                        name="type"
                        id="type"
                        required
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base text-gray-950 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        <option value="">Select type</option>

                        @foreach($types as $type)
                            <option
                                value="{{ $type }}"
                                @selected(old('type') === $type)
                            >
                                {{ ucwords(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Slug --}}
                <div>
                    <label
                        for="slug"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        value="{{ old('slug') }}"
                        maxlength="255"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base text-gray-950 placeholder-gray-500 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        placeholder="Optional — generated automatically if empty"
                    >

                    <p class="mt-2 text-sm text-gray-600">
                        Leave empty to generate the slug automatically.
                    </p>
                </div>


                {{-- Summary --}}
                <div class="md:col-span-2">
                    <label
                        for="summary"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Summary
                    </label>

                    <textarea
                        name="summary"
                        id="summary"
                        rows="4"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base leading-7 text-gray-950 placeholder-gray-500 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        placeholder="Short summary for cards, listings and previews"
                    >{{ old('summary') }}</textarea>

                    <p class="mt-2 text-sm text-gray-600">
                        Keep this short and suitable for previews and content listings.
                    </p>
                </div>


                {{-- Content --}}
                <div class="md:col-span-2">
                    <label
                        for="content"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Content
                        <span class="text-red-700">*</span>
                    </label>

                    <textarea
                        name="content"
                        id="content"
                        rows="16"
                        required
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base leading-7 text-gray-950 placeholder-gray-500 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        placeholder="Enter the full public content..."
                    >{{ old('content') }}</textarea>

                    <p class="mt-2 text-sm text-gray-600">
                        Enter the complete text that should appear on the public portal.
                    </p>
                </div>

            </div>
        </section>



        {{-- =========================================================
             MEDIA & DOCUMENTS
        ========================================================== --}}
        <section class="rounded-2xl border-2 border-gray-300 bg-white p-6 shadow-sm sm:p-8">

            <div class="mb-7 border-b-2 border-gray-200 pb-5">
                <h2 class="text-xl font-bold text-gray-950">
                    Media & Documents
                </h2>

                <p class="mt-1 text-base text-gray-700">
                    Add an optional featured image or supporting document.
                </p>
            </div>


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Featured Image --}}
                <div>
                    <label
                        for="image"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Featured Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                        class="block w-full cursor-pointer rounded-lg border-2 border-gray-400 bg-white text-base text-gray-900 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-gray-900 hover:file:bg-gray-200"
                    >

                    <p class="mt-2 text-sm text-gray-600">
                        Maximum size: 5 MB.
                    </p>
                </div>


                {{-- Attachment --}}
                <div>
                    <label
                        for="attachment"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Attachment
                    </label>

                    <input
                        type="file"
                        name="attachment"
                        id="attachment"
                        class="block w-full cursor-pointer rounded-lg border-2 border-gray-400 bg-white text-base text-gray-900 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-gray-900 hover:file:bg-gray-200"
                    >

                    <p class="mt-2 text-sm text-gray-600">
                        Maximum size: 10 MB.
                    </p>
                </div>

            </div>
        </section>



        {{-- =========================================================
             PUBLICATION SETTINGS
        ========================================================== --}}
        <section class="rounded-2xl border-2 border-gray-300 bg-white p-6 shadow-sm sm:p-8">

            <div class="mb-7 border-b-2 border-gray-200 pb-5">
                <h2 class="text-xl font-bold text-gray-950">
                    Publication Settings
                </h2>

                <p class="mt-1 text-base text-gray-700">
                    Control when and where this content appears.
                </p>
            </div>


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Publish Date --}}
                <div>
                    <label
                        for="published_at"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Publish Date & Time
                    </label>

                    <input
                        type="datetime-local"
                        name="published_at"
                        id="published_at"
                        value="{{ old('published_at') }}"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base text-gray-950 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                    <p class="mt-2 text-sm text-gray-600">
                        Set the date and time when this content should be published.
                    </p>
                </div>


                {{-- Display Until --}}
                <div>
                    <label
                        for="display_until"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Display Until
                    </label>

                    <input
                        type="datetime-local"
                        name="display_until"
                        id="display_until"
                        value="{{ old('display_until') }}"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base text-gray-950 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                    <p class="mt-2 text-sm text-gray-600">
                        Optional date and time after which the content should stop displaying.
                    </p>
                </div>


                {{-- Sort Order --}}
                <div>
                    <label
                        for="sort_order"
                        class="mb-2 block text-base font-bold text-gray-900"
                    >
                        Sort Order
                    </label>

                    <input
                        type="number"
                        name="sort_order"
                        id="sort_order"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-base text-gray-950 shadow-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >

                    <p class="mt-2 text-sm text-gray-600">
                        Lower numbers can be used to display content earlier in ordered lists.
                    </p>
                </div>


                {{-- Publication Options --}}
                <div class="rounded-xl border-2 border-gray-300 bg-gray-50 p-5">

                    <h3 class="mb-4 text-base font-bold text-gray-950">
                        Publication Options
                    </h3>

                    <div class="space-y-4">

                        {{-- Published --}}
                        <label class="flex cursor-pointer items-start gap-3">
                            <input
                                type="checkbox"
                                name="is_published"
                                value="1"
                                @checked(old('is_published'))
                                class="mt-1 h-5 w-5 rounded border-2 border-gray-400 text-blue-700 focus:ring-2 focus:ring-blue-300"
                            >

                            <span>
                                <span class="block text-base font-semibold text-gray-900">
                                    Publish immediately
                                </span>

                                <span class="mt-1 block text-sm text-gray-600">
                                    Make this content available on the public portal immediately.
                                </span>
                            </span>
                        </label>


                        {{-- Featured --}}
                        <label class="flex cursor-pointer items-start gap-3">
                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                @checked(old('is_featured'))
                                class="mt-1 h-5 w-5 rounded border-2 border-gray-400 text-blue-700 focus:ring-2 focus:ring-blue-300"
                            >

                            <span>
                                <span class="block text-base font-semibold text-gray-900">
                                    Feature on homepage
                                </span>

                                <span class="mt-1 block text-sm text-gray-600">
                                    Mark this content as featured.
                                </span>
                            </span>
                        </label>


                        {{-- Ticker --}}
                        <label class="flex cursor-pointer items-start gap-3">
                            <input
                                type="checkbox"
                                name="is_ticker"
                                value="1"
                                @checked(old('is_ticker'))
                                class="mt-1 h-5 w-5 rounded border-2 border-gray-400 text-blue-700 focus:ring-2 focus:ring-blue-300"
                            >

                            <span>
                                <span class="block text-base font-semibold text-gray-900">
                                    Show in important notice ticker
                                </span>

                                <span class="mt-1 block text-sm text-gray-600">
                                    Include this content in the important notice ticker.
                                </span>
                            </span>
                        </label>

                    </div>
                    <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">

    <label class="flex items-start gap-3">

        <input
            type="checkbox"
            name="is_home_service"
            value="1"
            class="mt-1 h-4 w-4 rounded border-gray-300 text-emerald-700 focus:ring-emerald-500"
            @checked(old('is_home_service'))
        >

        <span>
            <span class="block text-sm font-bold text-slate-950">
                Show on Homepage
            </span>

            <span class="mt-1 block text-xs leading-5 text-slate-600">
                Display this content in the OGSIEC Information section
                on the public homepage.
            </span>
        </span>

    </label>

</div>
                </div>

            </div>
        </section>



        {{-- =========================================================
             ACTIONS
        ========================================================== --}}
        <div class="flex flex-col-reverse gap-3 border-t-2 border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-end">

            <a
                href="{{ route('web.public-content.index') }}"
                class="inline-flex items-center justify-center rounded-lg border-2 border-gray-400 bg-white px-6 py-3 text-base font-bold text-gray-900 hover:bg-gray-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-blue-800 px-6 py-3 text-base font-bold text-white shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
                Create Content
            </button>

        </div>

    </form>

</div>
@endsection
