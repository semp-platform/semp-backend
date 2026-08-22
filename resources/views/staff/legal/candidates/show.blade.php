@extends('layouts.staff')

@section('title', 'Candidate — Legal & Compliance')

@section('content')

<div class="space-y-6">

    <div>
        <a
            href="{{ route('staff.legal.candidates.index') }}"
            class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
        >
            ← Back to Candidates
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-900">
            {{ $candidate->full_name }}
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Read-only candidate record.
        </p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="font-semibold text-slate-900">
                Candidate Information
            </h2>

            <dl class="mt-5 space-y-4 text-sm">

                <div>
                    <dt class="text-slate-500">Full Name</dt>
                    <dd class="mt-1 font-medium text-slate-900">
                        {{ $candidate->full_name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Gender</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $candidate->gender ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Date of Birth</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $candidate->date_of_birth?->format('d M Y') ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Qualification</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $candidate->qualification ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Disability</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $candidate->has_disability ? 'Yes' : 'No' }}
                    </dd>
                </div>

            </dl>

        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="font-semibold text-slate-900">
                Nomination Information
            </h2>

            <dl class="mt-5 space-y-4 text-sm">

                <div>
                    <dt class="text-slate-500">Political Party</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $nomination?->politicalParty?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Position</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $nomination?->position?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Election</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $nomination?->election?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Workflow Status</dt>
                    <dd class="mt-1 text-slate-900">
                        {{ $nomination?->workflow_status ?? '—' }}
                    </dd>
                </div>

            </dl>

        </div>

    </div>

</div>

@endsection
