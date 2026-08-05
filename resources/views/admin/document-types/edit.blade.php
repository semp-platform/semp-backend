@extends('layouts.app')

@section('title', 'Edit Document Type')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- Page Header --}}
    <div class="mb-8 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Edit Document Type
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update the document type configuration used in candidate nominations and election workflows.
            </p>
        </div>

        <a
            href="{{ route('admin.document-types.index') }}"
            class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm hover:bg-slate-50"
        >
            Back to Document Types
        </a>

    </div>

    {{-- Form Card --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-slate-900">
                Document Type Details
            </h2>
        </div>

        <form
            method="POST"
            action="{{ route('admin.document-types.update', $documentType) }}"
            class="p-6"
        >
            @include('admin.document-types._form')
        </form>

    </div>

</div>

@endsection
