@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="max-w-3xl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
        <p class="mt-1 text-sm text-slate-600">
            Update user details.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-2 block text-sm font-medium">Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
                class="w-full rounded-lg border-slate-300">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                class="w-full rounded-lg border-slate-300">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Role</label>

            <select name="role" class="w-full rounded-lg border-slate-300">
                @foreach($roles as $role)
                    <option
                        value="{{ $role->name }}"
                        @selected(old('role', $user->roles->first()?->name) === $role->name)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                New Password
            </label>

            <input
                type="password"
                name="password"
                class="w-full rounded-lg border-slate-300">

            <p class="mt-1 text-xs text-slate-500">
                Leave blank to keep the current password.
            </p>

            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                Confirm Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                class="w-full rounded-lg border-slate-300">
        </div>

        <div class="flex gap-3">
            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-5 py-2 text-white hover:bg-emerald-700">
                Update User
            </button>

            <a
                href="{{ route('admin.users.index') }}"
                class="rounded-lg border px-5 py-2">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
