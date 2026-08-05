@extends('layouts.app')

@section('title', 'Political Parties')

@section('content')

<div class="flex items-center justify-between mb-6">

    <div>
        <h1 class="text-2xl font-bold">
            Political Parties
        </h1>

        <p class="text-sm text-slate-500">
            Manage registered political parties.
        </p>
    </div>

    <a
        href="{{ route('admin.political-parties.create') }}"
        class="rounded-lg bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">

        Register Party

    </a>

</div>

<div class="overflow-hidden rounded-lg bg-white shadow">

    <table class="min-w-full">

        <thead class="bg-slate-50">

            <tr>

                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Acronym</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-right">Actions</th>

            </tr>

        </thead>

        <tbody>

        @forelse($parties as $party)

            <tr class="border-t">

                <td class="px-6 py-4">
                    {{ $party->name }}
                </td>

                <td class="px-6 py-4">
                    {{ $party->acronym }}
                </td>

                <td class="px-6 py-4">

                    @if($party->is_active)
                        <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">
                            Active
                        </span>
                    @else
                        <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">
                            Inactive
                        </span>
                    @endif

                </td>

                <td class="px-6 py-4 text-right flex justify-end gap-2">

                    <a
                        href="{{ route('admin.political-parties.edit', $party) }}"
                        class="text-emerald-600 hover:underline">

                        Edit

                    </a>

                    @if($party->is_active)

                        <form method="POST"
                              action="{{ route('admin.political-parties.deactivate', $party) }}">

                            @csrf
                            @method('PATCH')

                            <button
                                class="text-red-600 hover:underline">

                                Deactivate

                            </button>

                        </form>

                    @else

                        <form method="POST"
                              action="{{ route('admin.political-parties.activate', $party) }}">

                            @csrf
                            @method('PATCH')

                            <button
                                class="text-green-600 hover:underline">

                                Activate

                            </button>

                        </form>

                    @endif

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="4"
                    class="px-6 py-8 text-center text-slate-500">

                    No political parties found.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div class="mt-6">

    {{ $parties->links() }}

</div>

@endsection
