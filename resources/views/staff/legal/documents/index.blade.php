@extends('layouts.staff')

@section('content')

<div class="mx-auto max-w-7xl px-6 py-8">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between gap-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Court Orders & Injunctions
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Shared legal document mailbox for Legal and Commissioner review.
            </p>
        </div>

    </div>


    {{-- Success message --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Validation errors --}}
    @if($errors->any())

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-4">

            <p class="font-semibold text-red-800">
                Please correct the following:
            </p>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Legal upload area --}}
    @if(auth()->user()->hasRole('Legal Officer'))

        <div class="mb-8 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Upload Legal Document
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Upload a court order, injunction, or other legal document.
                </p>

            </div>


            <form
                method="POST"
                action="{{ route('staff.legal.documents.store') }}"
                enctype="multipart/form-data"
                class="p-6"
            >

                @csrf

                <div class="grid gap-6 md:grid-cols-2">

                    {{-- Title --}}
                    <div>

                        <label
                            for="title"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Document Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                            placeholder="e.g. High Court Injunction Order"
                        >

                    </div>


                    {{-- Document Type --}}
                    <div>

                        <label
                            for="document_type"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Document Type
                        </label>

                        <select
                            name="document_type"
                            id="document_type"
                            required
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                        >

                            <option value="">
                                Select document type
                            </option>

                            <option
                                value="court_order"
                                @selected(old('document_type') === 'court_order')
                            >
                                Court Order
                            </option>

                            <option
                                value="injunction"
                                @selected(old('document_type') === 'injunction')
                            >
                                Injunction
                            </option>

                            <option
                                value="judgment"
                                @selected(old('document_type') === 'judgment')
                            >
                                Judgment
                            </option>

                            <option
                                value="legal_notice"
                                @selected(old('document_type') === 'legal_notice')
                            >
                                Legal Notice
                            </option>

                            <option
                                value="other"
                                @selected(old('document_type') === 'other')
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Description --}}
                    <div class="md:col-span-2">

                        <label
                            for="description"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                            placeholder="Optional description or note about this document"
                        >{{ old('description') }}</textarea>

                    </div>


                    {{-- File --}}
                    <div class="md:col-span-2">

                        <label
                            for="document"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Document File
                        </label>

                        <input
                            type="file"
                            name="document"
                            id="document"
                            required
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm"
                        >

                        <p class="mt-2 text-xs text-slate-500">
                            PDF, JPG, or PNG. Maximum file size: 20 MB.
                        </p>

                    </div>

                </div>


                <div class="mt-6 flex justify-end">

                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Upload Document
                    </button>

                </div>

            </form>

        </div>

    @endif


    {{-- Document mailbox --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Legal Documents
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Documents available to authorized Legal and Commissioner staff.
            </p>

        </div>


        @if($documents->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Document
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Type
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Uploaded By
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach($documents as $document)

                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $document->title }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $document->original_name }}
                                    </div>

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-600">

                                    {{ str_replace('_', ' ', ucfirst($document->document_type)) }}

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-600">

                                    {{ $document->uploadedBy?->name ?? '—' }}

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-600">

                                    {{ $document->uploaded_at?->format('d M Y H:i') ?? '—' }}

                                </td>


                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ auth()->user()->hasRole('Legal Officer')
                                                ? route('staff.legal.documents.view', $document)
                                                : route('staff.commissioner.documents.view', $document) }}"
                                            target="_blank"
                                            class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ auth()->user()->hasRole('Legal Officer')
                                                ? route('staff.legal.documents.download', $document)
                                                : route('staff.commissioner.documents.download', $document) }}"
                                            class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                        >
                                            Download
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="border-t border-slate-200 px-6 py-4">

                {{ $documents->links() }}

            </div>

        @else

            <div class="px-6 py-16 text-center">

                <div class="text-sm font-medium text-slate-900">
                    No legal documents yet.
                </div>

                <p class="mt-2 text-sm text-slate-500">
                    Court orders and injunctions uploaded by authorized staff will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
