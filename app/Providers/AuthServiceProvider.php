<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user) {
            return $user->isAdmin() ? true : null;
        });

        Gate::define('admin-only', function (User $user): bool {
            return $user->isAdmin();
        });

        foreach ($this->permissionKeys() as $permission) {
            Gate::define($permission, function (User $user) use ($permission): bool {
                return $user->hasPermission($permission);
            });
        }
    }

    private function permissionKeys(): array
    {
        $groups = config('permissions.groups', []);
        $permissions = [];

        foreach ($groups as $group) {
            foreach ($group['permissions'] ?? [] as $key => $label) {
                $permissions[] = $key;
            }
        }

        return $permissions;
    }
}
