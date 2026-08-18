@extends('layouts.staff')

@section('title', 'Commissioner Decisions')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold">
        Approved / Returned Decisions
    </h1>

    <p class="mt-2 text-sm text-slate-600">
        Commissioner decision history.
    </p>

    <div class="mt-6">
        @forelse($decisions as $decision)

            <div class="mb-4 rounded-lg border p-4">

                <div class="font-semibold">
                    {{ $decision->nomination->candidate_name ?? 'Candidate' }}
                </div>

                <div class="text-sm text-slate-600">
                    Action:
                    {{ ucfirst($decision->action) }}
                </div>

            </div>

        @empty

            <p>
                No decisions recorded yet.
            </p>

        @endforelse
    </div>

    {{ $decisions->links() }}

</div>

@endsection
