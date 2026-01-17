<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Income', 'Expense', 'Maintenance', 'Utilities'];

        foreach ($types as $type) {
            AccountType::firstOrCreate(['name' => $type]);
        }
    }
}
