@extends('layouts.party')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a
            href="{{ route('party.election-notices.index') }}"
            class="text-sm font-medium text-slate-600 hover:text-slate-900"
        >
            ← Back to Election Notices
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h1 class="text-2xl font-semibold text-slate-800">
                {{ $electionNotice->title }}
            </h1>

            @if ($electionNotice->election)
                <p class="mt-2 text-sm text-slate-500">
                    Election: {{ $electionNotice->election->name }}
                </p>
            @endif

            @if ($electionNotice->published_at)
                <p class="mt-1 text-xs text-slate-400">
                    Published {{ $electionNotice->published_at->format('d M Y, g:i A') }}
                </p>
            @endif

        </div>

        <div class="px-6 py-6">

            <div class="prose max-w-none text-slate-700">
                {!! nl2br(e($electionNotice->content)) !!}
            </div>

            @if ($electionNotice->attachment_path)
                <div class="mt-8 border-t border-slate-200 pt-6">

                    <a
                        href="{{ asset('storage/' . $electionNotice->attachment_path) }}"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
                    >
                        View Attachment
                    </a>

                </div>
            @endif

        </div>

    </div>

</div>

@endsection
