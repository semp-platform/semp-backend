@extends('layouts.party')

@section('title', 'New Candidate Nomination | SEMP')

@section('content')

<div class="mx-auto max-w-5xl">

    <div class="mb-8">

        <a
            href="{{ route('party.dashboard') }}"
            class="text-sm font-medium text-emerald-700 hover:text-emerald-800"
        >
            ← Back to dashboard
        </a>

        <div class="mt-4">
            <p class="text-sm font-medium text-emerald-700">
                Candidate Nominations
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">
                New Candidate Nomination
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Register a candidate for {{ $party->name }}.
                The nomination will initially be saved as a draft.
            </p>
        </div>

    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="font-semibold text-red-800">
                Please correct the following:
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    @include('party.nominations.partials.form', [
        'mode' => 'create'
    ])

</div>

@endsection
