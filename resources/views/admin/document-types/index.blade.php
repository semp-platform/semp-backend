@extends('layouts.app')

@section('title', 'Document Types')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Document Types
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Manage candidate documents and official form definitions.
            </p>
        </div>

        <a
            href="{{ route('admin.document-types.create') }}"
            class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-800"
        >
            New Document Type
        </a>

    </div>


    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

            <tr>

                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Name
                </th>

                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Code
                </th>

                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Category
                </th>

                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Stage
                </th>

                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Required
                </th>

                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Active
                </th>

                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Actions
                </th>

            </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

            @forelse($documentTypes as $documentType)

                <tr class="hover:bg-slate-50">

                    <td class="px-6 py-4 font-medium text-slate-900">
                        {{ $documentType->name }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $documentType->code }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $documentType->category->value }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $documentType->workflow_stage->value }}
                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($documentType->required)

                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">
                                Yes
                            </span>

                        @else

                            <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
                                No
                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($documentType->is_active)

                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">
                                Active
                            </span>

                        @else

                            <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4 text-right">

                        <a
                            href="{{ route('admin.document-types.edit', $documentType) }}"
                            class="text-sm font-medium text-emerald-700 hover:underline"
                        >
                            Edit
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">

                        No document types found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
