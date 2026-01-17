<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'Sok',
                'last_name' => 'Dara',
                'gender' => 'Male',
                'dob' => '1995-04-12',
                'email' => 'sok@example.com',
                'country' => 'Cambodia',
                'member_count' => 2,
                'address1' => 'Phnom Penh',
            ],
            [
                'first_name' => 'Lina',
                'last_name' => 'Chan',
                'gender' => 'Female',
                'dob' => '1992-09-20',
                'email' => 'lina@example.com',
                'country' => 'Thailand',
                'member_count' => 1,
                'address1' => 'Bangkok',
            ],
        ];

        foreach ($customers as $data) {
            $customer = Customer::firstOrCreate(['email' => $data['email']], $data);

            $customer->phones()->updateOrCreate(['phone' => '012-345-678'], ['phone' => '012-345-678']);
            $customer->document()->updateOrCreate([], [
                'national_id' => 'NAT-'.$customer->id,
                'national_valid_until' => now()->addYear()->toDateString(),
                'passport_id' => 'PASS-'.$customer->id,
                'passport_valid_until' => now()->addYears(2)->toDateString(),
                'visa_id' => 'VISA-'.$customer->id,
                'visa_valid_until' => now()->addMonths(6)->toDateString(),
            ]);
        }
    }
}
