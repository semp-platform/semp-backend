@extends('layouts.app')

@section('title', 'Create User')

@section('content')

<div class="max-w-3xl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Create User</h1>
        <p class="mt-1 text-sm text-slate-600">
            Add a new system user.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-medium">Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full rounded-lg border-slate-300"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="w-full rounded-lg border-slate-300"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Role</label>

            <select
                name="role"
                class="w-full rounded-lg border-slate-300"
            >
                <option value="">Select Role</option>

                @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                        @selected(old('role') == $role->name)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Password</label>

            <input
                type="password"
                name="password"
                class="w-full rounded-lg border-slate-300"
            >

            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">Confirm Password</label>

            <input
                type="password"
                name="password_confirmation"
                class="w-full rounded-lg border-slate-300"
            >
        </div>

        <div class="flex gap-3">

            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-5 py-2 text-white hover:bg-emerald-700">
                Create User
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
