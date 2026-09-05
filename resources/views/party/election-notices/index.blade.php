@extends('layouts.party')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">
            Election Notices
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Official election notices published by OGSIEC.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($notices->count())

        <div class="space-y-4">

            @foreach ($notices as $notice)

                <a
                    href="{{ route('party.election-notices.show', $notice) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 hover:shadow"
                >

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">
                                {{ $notice->title }}
                            </h2>

                            @if ($notice->election)
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $notice->election->name }}
                                </p>
                            @endif
                        </div>

                        @if ($notice->published_at)
                            <span class="shrink-0 text-xs text-slate-500">
                                {{ $notice->published_at->format('d M Y') }}
                            </span>
                        @endif

                    </div>

                    <p class="mt-3 text-sm text-slate-600">
                        {{ \Illuminate\Support\Str::limit(strip_tags($notice->content), 180) }}
                    </p>

                    <div class="mt-4 text-sm font-medium text-slate-700">
                        Read notice →
                    </div>

                </a>

            @endforeach

        </div>

        <div class="mt-6">
            {{ $notices->links() }}
        </div>

    @else

        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">

            <h2 class="text-lg font-semibold text-slate-800">
                No Election Notices
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                There are currently no published election notices.
            </p>

        </div>

    @endif

</div>

@endsection
