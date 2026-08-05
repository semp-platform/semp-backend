@extends('layouts.app')

@section('title', 'Users')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Users</h1>
        <p class="mt-1 text-sm text-slate-600">
            Manage system users and their roles.
        </p>
    </div>

    <a href="{{ route('admin.users.create') }}"
       class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
        New User
    </a>
</div>

<div class="overflow-hidden rounded-lg bg-white shadow">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Created</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-200 bg-white">

        @forelse($users as $user)
            <tr>

                <td class="px-6 py-4">
                    {{ $user->name }}
                </td>

                <td class="px-6 py-4">
                    {{ $user->email }}
                </td>

                <td class="px-6 py-4">
                    {{ $user->roles->pluck('name')->implode(', ') ?: '-' }}
                </td>

                <td class="px-6 py-4">
                    {{ $user->created_at->format('d M Y') }}
                </td>

                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="text-emerald-600 hover:underline">
                        Edit
                    </a>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                    No users found.
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $users->links() }}
</div>

@endsection
