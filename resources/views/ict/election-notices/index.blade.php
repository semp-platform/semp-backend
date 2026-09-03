@extends('layouts.staff')

@section('title', 'Election Notices')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">
                Election Notices
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Create and manage election notices for political parties.
            </p>
        </div>

        <a
            href="{{ route('staff.ict.election-notices.create') }}"
            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
        >
            Create Notice
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Title
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Election
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Published
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">

                @forelse ($notices as $notice)

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-800">
                                {{ $notice->title }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600">
                                {{ $notice->election?->name ?? '—' }}
                            </div>
                        </td>

                        <td class="px-6 py-4">

                            @if ($notice->status === 'published')

                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                    Published
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    Draft
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600">
                                {{ $notice->published_at?->format('d M Y H:i') ?? '—' }}
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="4"
                            class="px-6 py-12 text-center text-sm text-slate-500"
                        >
                            No election notices have been created yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
