<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionGroups = config('permissions.groups', []);
        $permissions = [];

        foreach ($permissionGroups as $group) {
            foreach ($group['permissions'] ?? [] as $key => $label) {
                Permission::findOrCreate($key);
                $permissions[] = $key;
            }
        }

        $roles = config('permissions.roles', []);
        foreach ($roles as $roleName => $label) {
            $role = Role::findOrCreate($roleName);

            if ($roleName === 'admin') {
                $role->syncPermissions($permissions);
                continue;
            }

            $defaults = config('permissions.defaults.' . $roleName, []);
            $role->syncPermissions(array_keys(array_filter($defaults)));
        }

        User::with('roles')->get()->each(function (User $user) {
            if ($user->roles->isNotEmpty()) {
                return;
            }

            if (!empty($user->role)) {
                $user->assignRole($user->role);
            }
        });
    }
}
