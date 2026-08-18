@extends('layouts.party')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-semibold text-slate-900">
        Returned Nominations
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Nominations returned by OGSIEC for document corrections and correction history.
    </p>
</div>


<div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">

    @forelse($requests as $request)

        <div class="border-b border-slate-100 p-6">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="font-semibold text-slate-900">
                        {{ $request->nomination->candidate->full_name ?? 'Candidate' }}
                    </h2>

                    <p class="text-sm text-slate-500">
                        Nomination #{{ $request->nomination_id }}
                    </p>
                </div>


                @if($request->status === \App\Models\Candidate\CandidateDocumentReviewRequest::STATUS_REQUESTED)

                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        Action Required
                    </span>

                @else

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Correction Submitted
                    </span>

                @endif

            </div>


            <div class="mt-4 rounded-lg bg-slate-50 p-4">

                <h3 class="text-sm font-semibold text-slate-800">
                    Document requiring correction
                </h3>

                <p class="mt-1 text-sm text-slate-700">
                    {{ $request->candidateDocument->documentType->name ?? 'Document' }}
                </p>


                @if($request->reason)

                    <h3 class="mt-4 text-sm font-semibold text-slate-800">
                        Reason
                    </h3>

                    <p class="mt-1 text-sm text-slate-700">
                        {{ $request->reason }}
                    </p>

                @endif


                @if($request->status === \App\Models\Candidate\CandidateDocumentReviewRequest::STATUS_RESOLVED)

                    <div class="mt-4 border-t border-slate-200 pt-4">

                        <h3 class="text-sm font-semibold text-slate-800">
                            Resolution
                        </h3>

                        <p class="mt-1 text-sm text-slate-700">
                            Corrected document uploaded by party.
                        </p>

                        @if($request->resolved_at)

                            <p class="mt-1 text-xs text-slate-500">
                                Resolved:
                                {{ $request->resolved_at->format('d M Y, H:i') }}
                            </p>

                        @endif

                    </div>

                @endif


            </div>


            <div class="mt-4">

                <a
                    href="{{ route('party.nominations.show', $request->nomination) }}"
                    class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
                >
                    View Nomination
                </a>

            </div>


        </div>


    @empty

        <div class="p-8 text-center text-sm text-slate-500">
            No returned nominations found.
        </div>

    @endforelse

</div>

@endsection
