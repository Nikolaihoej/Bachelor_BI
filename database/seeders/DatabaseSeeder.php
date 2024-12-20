<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\MembershipType;
use App\Models\CustomerActivityStatus;
use App\Models\FactTable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 100 Customer records
        Customer::factory(100)->create();

        // Create 3 MembershipTypes (Basic, Premium, Student)
        MembershipType::factory()->create([
            'TypeName' => 'Basic',
        ]);
        MembershipType::factory()->create([
            'TypeName' => 'Premium',
        ]);
        MembershipType::factory()->create([
            'TypeName' => 'Student',
        ]);

        // Create 100 CustomerActivityStatus records
        CustomerActivityStatus::factory(100)->create();

        // Create 100 fact-table records
        FactTable::factory(100)->create();
    }
}

