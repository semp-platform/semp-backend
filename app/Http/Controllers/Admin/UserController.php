<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Administration\UserService;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->all();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => $this->userService->roles(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $this->userService->roles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update(
            $user,
            $request->validated()
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
}
