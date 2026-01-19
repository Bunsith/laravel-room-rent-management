<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function edit(): View
    {
        Gate::authorize('permissions.manage');

        $permissionGroups = config('permissions.groups', []);
        $allPermissions = $this->ensurePermissions($permissionGroups);

        $labels = config('permissions.roles', []);
        foreach (array_keys($labels) as $roleName) {
            Role::findOrCreate($roleName);
        }

        $roles = Role::with('permissions')->orderBy('name')->get();

        $current = [];
        foreach ($roles as $role) {
            if ($role->name === 'admin') {
                $current[$role->name] = array_keys($allPermissions);
                continue;
            }

            $current[$role->name] = $role->permissions->pluck('name')->all();
        }

        return view('users.permissions', [
            'roles' => $roles,
            'roleLabels' => $labels,
            'permissionGroups' => $permissionGroups,
            'currentPermissions' => $current,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('permissions.manage');

        $permissionGroups = config('permissions.groups', []);
        $allPermissions = array_keys($this->ensurePermissions($permissionGroups));
        $input = $request->input('permissions', []);

        $roles = Role::all();
        foreach ($roles as $role) {
            if ($role->name === 'admin') {
                $role->syncPermissions($allPermissions);
                continue;
            }

            $selected = array_values(array_intersect($allPermissions, $input[$role->name] ?? []));
            $role->syncPermissions($selected);
        }

        return back()->with('status', 'Role permissions updated successfully.');
    }

    private function ensurePermissions(array $groups): array
    {
        $permissions = [];

        foreach ($groups as $group) {
            foreach ($group['permissions'] ?? [] as $key => $label) {
                $permissions[$key] = $label;
                Permission::findOrCreate($key);
            }
        }

        return $permissions;
    }
}
