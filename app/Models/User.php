<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RolePermission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissions = self::rolePermissionsFor($this->role);

        return in_array($permission, $permissions, true);
    }

    private static function rolePermissionsFor(string $role): array
    {
        static $cache = [];

        if (array_key_exists($role, $cache)) {
            return $cache[$role];
        }

        $stored = RolePermission::where('role', $role)->value('permissions');
        if (is_array($stored)) {
            $cache[$role] = array_values(array_unique($stored));
            return $cache[$role];
        }

        $defaults = config('permissions.defaults.' . $role, []);
        $cache[$role] = array_keys(array_filter($defaults));

        return $cache[$role];
    }
}
