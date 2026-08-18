@extends('layouts.staff')

@section('title', 'Court Orders & Injunctions')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Court Orders & Injunctions
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Legal documents available for Commissioner review.
        </p>
    </div>

    {{-- Documents --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="font-semibold text-slate-900">
                Legal Documents
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Documents uploaded by the Legal team are available here.
            </p>

        </div>

        @if($documents->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Document
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Type
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Uploaded By
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @foreach($documents as $document)

                            <tr>

                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-900">
                                        {{ $document->title }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $document->original_name }}
                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $document->document_type }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $document->uploadedBy?->name ?? 'Unknown' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ optional($document->uploaded_at)->format('d M Y, H:i') }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-3">

                                        <a
                                            href="{{ route('staff.commissioner.documents.view', $document) }}"
                                            target="_blank"
                                            class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('staff.commissioner.documents.download', $document) }}"
                                            class="text-sm font-medium text-slate-700 hover:text-slate-900"
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

                <p class="font-semibold text-slate-900">
                    No legal documents yet.
                </p>

                <p class="mt-2 text-sm text-slate-500">
                    Documents uploaded by the Legal team will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
