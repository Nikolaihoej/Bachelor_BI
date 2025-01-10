<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\MembershipType;
use App\Models\CustomerActivityStatus;
use App\Models\FactTable;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a user
        User::factory(1)->create();

        // Create 100 Customer records
        Customer::factory(100)->create();

        // Create 3 MembershipTypes (Basic, Premium, Student) without specifying the ID
        $membershipTypes = ['Basic', 'Premium', 'Student'];
        foreach ($membershipTypes as $type) {
            MembershipType::firstOrCreate(['TypeName' => $type]);
        }

        // Create 100 CustomerActivityStatus records
        CustomerActivityStatus::factory(100)->create();

        // Create 100 fact-table records
        FactTable::factory(100)->create();


    }
}

