<?php

namespace Database\Seeders;

use App\Models\ResourceBudget;
use Illuminate\Database\Seeder;

class ResourceBudgetSeeder extends Seeder
{
    public function run(): void
    {
        $budgets = ['General', 'Repair Fund', 'Utilities', 'Marketing'];

        foreach ($budgets as $budget) {
            ResourceBudget::firstOrCreate(['name' => $budget]);
        }
    }
}
