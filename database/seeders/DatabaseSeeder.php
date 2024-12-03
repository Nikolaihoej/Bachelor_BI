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
        // Opret 10 kunder
        Customer::factory(10)->create();

        // Opret 3 medlemskabstyper (Basic, Premium, Student)
        MembershipType::factory()->create([
            'TypeName' => 'Basic',
        ]);
        MembershipType::factory()->create([
            'TypeName' => 'Premium',
        ]);
        MembershipType::factory()->create([
            'TypeName' => 'Student',
        ]);

        // Opret 10 aktivitet status for kunder
        CustomerActivityStatus::factory(10)->create();

        // Opret 10 fact-table records (forbindelse mellem kunder, medlemskabstyper og aktivitetstatus)
        FactTable::factory(10)->create();
    }
}

