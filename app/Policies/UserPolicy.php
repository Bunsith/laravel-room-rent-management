<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $user, User $target): bool
    {
        return $user->hasPermission('users.manage') && $user->id !== $target->id;
    }
}
