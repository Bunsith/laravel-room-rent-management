<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('users.view');

        $users = User::with('roles')->orderBy('name')->paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('users.manage');

        return view('users.create', [
            'user' => new User(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        Gate::authorize('users.manage');

        $payload = $request->validated();
        $payload['password'] = Hash::make($payload['password']);

        $roleName = $payload['role'];
        Role::findOrCreate($roleName);

        $user = User::create($payload);
        $user->syncRoles([$roleName]);

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        Gate::authorize('users.manage');

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('users.manage');

        $payload = $request->validated();

        if (!empty($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        } else {
            unset($payload['password']);
        }

        $user->update($payload);
        $roleName = $payload['role'];
        Role::findOrCreate($roleName);
        $user->syncRoles([$roleName]);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }
}
