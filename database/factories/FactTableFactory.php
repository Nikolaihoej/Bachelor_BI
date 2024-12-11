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
        static $customerID = 1;
        static $activityStatusID = 1;

        if ($customerID > 100) {
            $customerID = 1;
        }

        if ($activityStatusID > 100) {
            $activityStatusID = 1;
        }

        return [
            'CustomerID' => $customerID++, // Unique number between 1 and 100
            'ActivityStatusID' => $activityStatusID++, // Another unique number between 1 and 100
            'MembershipTypeID' => MembershipType::orderBy('MembershipTypeID')->first()->MembershipTypeID, // Relateret til membership_types
        ];
    }
}
