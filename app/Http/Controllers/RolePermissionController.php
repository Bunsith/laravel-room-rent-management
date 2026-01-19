<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function edit(): View
    {
        Gate::authorize('permissions.manage');

        $roles = config('permissions.roles', []);
        $permissionGroups = config('permissions.groups', []);
        $allPermissions = $this->flattenPermissions($permissionGroups);
        $stored = RolePermission::whereIn('role', array_keys($roles))->get()->keyBy('role');

        $current = [];
        foreach ($roles as $role => $label) {
            if ($role === 'admin') {
                $current[$role] = array_keys($allPermissions);
                continue;
            }

            if ($stored->has($role)) {
                $current[$role] = array_values(array_unique($stored->get($role)->permissions ?? []));
                continue;
            }

            $defaults = config('permissions.defaults.' . $role, []);
            $current[$role] = array_keys(array_filter($defaults));
        }

        return view('users.permissions', [
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
            'currentPermissions' => $current,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('permissions.manage');

        $roles = config('permissions.roles', []);
        $permissionGroups = config('permissions.groups', []);
        $allPermissions = array_keys($this->flattenPermissions($permissionGroups));
        $input = $request->input('permissions', []);

        foreach ($roles as $role => $label) {
            if ($role === 'admin') {
                continue;
            }

            $selected = array_values(array_intersect($allPermissions, $input[$role] ?? []));
            RolePermission::updateOrCreate(
                ['role' => $role],
                ['permissions' => $selected]
            );
        }

        return back()->with('status', 'Role permissions updated successfully.');
    }

    private function flattenPermissions(array $groups): array
    {
        $permissions = [];

        foreach ($groups as $group) {
            foreach ($group['permissions'] ?? [] as $key => $label) {
                $permissions[$key] = $label;
            }
        }

        return $permissions;
    }
}
