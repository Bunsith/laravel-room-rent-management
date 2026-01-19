<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            FloorSeeder::class,
            RoomTypeSeeder::class,
            FacilitySeeder::class,
            RoomSeeder::class,
            CustomerSeeder::class,
            AccountTypeSeeder::class,
            ResourceBudgetSeeder::class,
            RentalSeeder::class,
        ]);
    }
}
