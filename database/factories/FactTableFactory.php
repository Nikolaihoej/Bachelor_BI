<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\MembershipType;
use App\Models\CustomerActivityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactTableFactory extends Factory
{
    protected $model = \App\Models\FactTable::class;

    public function definition()
    {
        return [
            'CustomerID' => Customer::inRandomOrder()->first()->CustomerID, // Relateret til customers
            'MembershipTypeID' => MembershipType::inRandomOrder()->first()->MembershipTypeID, // Relateret til membership_types
            'ActivityStatusID' => CustomerActivityStatus::inRandomOrder()->first()->ActivityStatusID, // Relateret til customer_activity_status
        ];
    }
}
