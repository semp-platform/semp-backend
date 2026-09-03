@extends('layouts.staff')

@section('title', 'Create Election Notice')
@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">
            Create Election Notice
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Create a notice that will be published to political parties.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
            <ul class="list-disc pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('staff.ict.election-notices.store') }}"
        class="space-y-6 rounded-xl border border-slate-200 bg-white p-6"
    >
        @csrf

        <div>
            <label
                for="election_id"
                class="block text-sm font-medium text-slate-700"
            >
                Election
            </label>

            <select
                id="election_id"
                name="election_id"
                required
                class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500"
            >
                <option value="">Select Election</option>

                @foreach ($elections as $election)
                    <option
                        value="{{ $election->id }}"
                        @selected(old('election_id') == $election->id)
                    >
                        {{ $election->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label
                for="title"
                class="block text-sm font-medium text-slate-700"
            >
                Notice Title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                required
                maxlength="255"
                class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500"
                placeholder="Enter notice title"
            >
        </div>

        <div>
            <label
                for="content"
                class="block text-sm font-medium text-slate-700"
            >
                Notice
            </label>

            <textarea
                id="content"
                name="content"
                rows="10"
                required
                class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500"
                placeholder="Enter the notice content"
            >{{ old('content') }}</textarea>
        </div>

        <div>
            <label
                for="attachment_path"
                class="block text-sm font-medium text-slate-700"
            >
                Attachment Path
            </label>

            <input
                type="text"
                id="attachment_path"
                name="attachment_path"
                value="{{ old('attachment_path') }}"
                maxlength="255"
                class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500"
                placeholder="Optional"
            >
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">

            <a
                href="{{ route('staff.ict.election-notices.index') }}"
                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
            >
                Save Draft
            </button>

        </div>

    </form>

</div>

@endsection
