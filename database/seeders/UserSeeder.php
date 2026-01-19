<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('admin');
        Role::findOrCreate('staff');

        User::updateOrCreate(
            ['email' => 'admin@roomrental.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        )->syncRoles(['admin']);

        User::updateOrCreate(
            ['email' => 'staff@roomrental.test'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        )->syncRoles(['staff']);
    }
}
