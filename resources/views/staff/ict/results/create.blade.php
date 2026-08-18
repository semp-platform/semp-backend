@extends('layouts.staff')

@section('title', 'Upload Election Results')

@section('content')

<div class="mx-auto max-w-4xl">

    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
            ICT Department
        </p>

        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
            Upload Election Results
        </h1>

        <p class="mt-2 text-sm leading-6 text-slate-600">
            Upload an election results spreadsheet for analysis and validation.
        </p>
    </div>


    <form
        method="POST"
        action="{{ route('staff.ict.results.analyse') }}"
        enctype="multipart/form-data"
        class="mt-8"
    >

        @csrf

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-lg font-bold text-slate-950">
                    Result Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Select the election, result position and upload the result spreadsheet.
                </p>

            </div>


            <div class="space-y-6 p-6">

                <div>
                    <label
                        for="election_id"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Election
                    </label>

                    <select
    id="election_id"
    name="election_id"
    required
    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
>
    <option value="">
        Select election
    </option>

    @foreach($elections as $election)

        <option value="{{ $election->id }}">
            {{ $election->name }}
            @if($election->election_date)
                — {{ $election->election_date->format('d M Y') }}
            @endif
        </option>

    @endforeach

</select>
                </div>


                                {{-- Result Position --}}
                <div>
                    <label
                        for="position_id"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Result Position
                    </label>

                    <p class="mt-1 text-xs text-slate-500">
                        Select the position represented by this result workbook.
                    </p>

                    <select
                        id="position_id"
                        name="position_id"
                        required
                        class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    >
                        <option value="">
                            Select result position
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(old('position_id') == $position->id)
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('position_id')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


{{-- Result Spreadsheet --}}
<div>
    <label
        for="result_file"
        class="block text-sm font-semibold text-slate-700"
    >
        Result Spreadsheet
    </label>

    <p class="mt-1 text-xs text-slate-500">
        Excel or CSV file containing the official result data.
    </p>

    <input
        id="result_file"
        name="result_file"
        type="file"
        required
        accept=".xlsx,.xls,.csv"
        class="mt-3 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
    >

    @error('result_file')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">

                    <p class="text-sm font-semibold text-amber-900">
                        Before you upload
                    </p>

                    <ul class="mt-2 space-y-1 text-sm leading-6 text-amber-800">
                        <li>• The spreadsheet will be analysed before it is saved.</li>
                        <li>• Missing wards or incomplete data will be reported.</li>
                        <li>• Uploading does not publish the results.</li>
                        <li>• ICT will review the import before publication.</li>
                    </ul>

                </div>

            </div>


            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('staff.ict.results.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800"
                >
                    Upload & Analyse
                </button>

            </div>

        </div>

    </form>

</div>

@endsection
