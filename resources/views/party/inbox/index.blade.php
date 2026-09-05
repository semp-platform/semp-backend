@extends('layouts.party')

@section('title', 'Inbox | SEMP')

@section('content')

<div class="mx-auto max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">
            Inbox
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Communications and messages from OGSIEC.
        </p>
    </div>

   @if ($notices->count())

    <div class="space-y-4">

        @foreach ($notices as $notice)

            <a
                href="{{ route('party.election-notices.show', $notice) }}"
                class="block rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 hover:shadow"
            >

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            {{ $notice->title }}
                        </h2>

                        @if ($notice->election)
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $notice->election->name }}
                            </p>
                        @endif
                    </div>

                    <span class="shrink-0 text-xs text-slate-400">
                        {{ $notice->published_at?->format('d M Y, g:i A') }}
                    </span>

                </div>

                <p class="mt-3 text-sm text-slate-600">
                    {{ \Illuminate\Support\Str::limit(strip_tags($notice->content), 160) }}
                </p>

            </a>

        @endforeach

    </div>

@else

    <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">
        <p class="text-sm text-slate-500">
            No messages yet.
        </p>
    </div>

@endif

</div>

@endsection
