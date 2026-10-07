
@extends('layouts.staff')

@section('title', 'Legal Dashboard')

@section('content')

<div class="mx-auto max-w-7xl">

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-800">
            Legal Dashboard
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Overview of nomination reviews, legal records and recent activity.
        </p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">

        <a
            href="{{ route('staff.legal.nominations.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow"
        >
            <p class="text-sm font-medium text-slate-500">
                Pending Legal Reviews
            </p>

            <p class="mt-2 text-3xl font-semibold text-slate-900">
                {{ $pendingReview }}
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Nominations currently assigned to Legal
            </p>
        </a>

        <a
            href="{{ route('staff.legal.workflow-history.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow"
        >
            <p class="text-sm font-medium text-slate-500">
                Completed Reviews
            </p>

            <p class="mt-2 text-3xl font-semibold text-slate-900">
                {{ $completedReviews }}
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Recorded Legal forwarding and return actions
            </p>
        </a>

    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800">
                Legal Review
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Review nominations assigned to Legal and manage legal workflow activities.
            </p>

            <div class="mt-5 flex flex-wrap gap-3">

                <a
                    href="{{ route('staff.legal.nominations.index') }}"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                >
                    Review Nominations
                </a>

                <a
                    href="{{ route('staff.legal.workflow-history.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Workflow History
                </a>

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800">
                Legal Records
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Access candidate records, political party records and shared legal documents.
            </p>

            <div class="mt-5 flex flex-wrap gap-3">

                <a
                    href="{{ route('staff.legal.candidates.index') }}"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                >
                    Candidates
                </a>

                <a
                    href="{{ route('staff.legal.political-parties.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Political Parties
                </a>

                <a
                    href="{{ route('staff.legal.documents.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Legal Documents
                </a>

            </div>

        </div>

    </div>

    <div class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Recent Legal Activity
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                The five most recent workflow actions recorded for Legal.
            </p>
        </div>

        @if ($recentActivity->isNotEmpty())

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">

                    <thead>
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                            <th class="px-4 py-3">Nomination</th>
                            <th class="px-4 py-3">Candidate</th>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">Officer</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach ($recentActivity as $activity)

                            <tr class="text-sm text-slate-700">

                                <td class="px-4 py-3 font-medium">
                                    {{ $activity->nomination?->id ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $activity->nomination?->candidate?->full_name ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $activity->user?->name ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $activity->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

        @else

            <p class="py-6 text-sm text-slate-500">
                No recent Legal workflow activity is available.
            </p>

        @endif

    </div>

</div>

@endsection

