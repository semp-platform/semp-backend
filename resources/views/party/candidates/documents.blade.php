@extends('layouts.party')

@section('title', 'Candidate Documents')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    <div class="flex items-center justify-between">

        <div>

            <a
    href="{{ route('party.nominations.index') }}"
    class="text-sm text-emerald-600 hover:text-emerald-700"
>
    ← Back to Nominations
</a>

            <p class="mt-4 text-sm font-semibold text-emerald-600">
                Candidate Documents
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                {{ $candidate->full_name }}
            </h1>

        </div>

    </div>

    @if(session('success'))

        <div class="rounded-lg bg-emerald-100 p-4 text-emerald-700">

            {{ session('success') }}

        </div>

    @endif

    @if($errors->any())

        <div class="rounded-lg bg-red-100 p-4 text-red-700">

            <ul class="list-disc pl-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="overflow-hidden rounded-xl border bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                        Document
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase">
                        Status
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase">
                        Upload
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

            @foreach($documentTypes as $documentType)

                @php

                    $document = $candidate->documents
                        ->firstWhere(
                            'document_type_id',
                            $documentType->id
                        );

                @endphp

                <tr>

                    <td class="px-6 py-5">

                        <div class="font-semibold">

                            {{ $documentType->name }}

                        </div>

                        @if($documentType->description)

                            <div class="text-sm text-slate-500">

                                {{ $documentType->description }}

                            </div>

                        @endif

                    </td>

                    <td class="px-6 py-5 text-center">

                        @if($document)

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">

                                Uploaded

                            </span>

                        @else

                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">

                                Not Uploaded

                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-5">

                        <form
                            method="POST"
                            action="{{ route('party.candidates.documents.store', $candidate) }}"
                            enctype="multipart/form-data"
                            class="flex items-center gap-3"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="document_type_id"
                                value="{{ $documentType->id }}"
                            >

                            <input
                                type="file"
                                name="document"
                                required
                                class="text-sm"
                            >

                            <button
                                type="submit"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                            >
                                {{ $document ? 'Replace' : 'Upload' }}
                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>
<div class="mt-8 flex items-center justify-between border-t pt-6">

    <p class="text-sm text-slate-600">
        Uploaded:
        <strong>{{ $uploadedDocuments }} / {{ $requiredDocuments }}</strong>
    </p>

    <form
        method="POST"
        action="{{ route('party.candidates.documents.complete', $candidate) }}"
    >
        @csrf

        <button
            type="submit"
            class="rounded-lg bg-emerald-600 px-5 py-2 font-semibold text-white hover:bg-emerald-700"
        >
            Save & Continue
        </button>

    </form>

</div>

@endsection
