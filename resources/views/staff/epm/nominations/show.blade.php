@extends('layouts.staff')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-6">

        <div class="mb-6">
            <a
                href="{{ route('staff.epm.nominations.index') }}"
                class="text-sm text-blue-600 hover:underline"
            >
                     ← Back to EPM Nominations
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">

            <h1 class="text-2xl font-bold mb-6">
                EPM Nomination Review
            </h1>

            <div class="grid grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-500">Nomination ID</p>
                    <p class="font-semibold">
                        {{ $nomination->id }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Current Department</p>
                    <p class="font-semibold">
                        {{ strtoupper($nomination->current_department) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-semibold">
                        {{ $nomination->status }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Workflow Status</p>
                    <p class="font-semibold">
                        {{ $nomination->workflow_status }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Candidate</p>
                    <p class="font-semibold">
                        {{ $nomination->candidate->name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Political Party</p>
                    <p class="font-semibold">
                        {{ $nomination->politicalParty->name ?? 'N/A' }}
                    </p>
                </div>

            </div>

            <hr class="my-8">

            <h2 class="text-lg font-semibold mb-4">
                Forward to Legal
            </h2>

            <form
                method="POST"
                action="{{ route('staff.epm.nominations.forward', $nomination) }}"
            >
                @csrf

                <div class="mb-4">
                    <label
                        for="comment"
                        class="block text-sm font-medium mb-2"
                    >
                        Comment
                    </label>

                    <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        class="w-full border rounded-md p-3"
                        placeholder="Enter EPM review comments..."
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md"
                >
                    Forward to Legal
                </button>
            </form>

        </div>

    </div>
@endsection
