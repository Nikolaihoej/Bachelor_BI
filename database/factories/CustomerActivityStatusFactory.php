<?php

namespace Database\Factories;

use App\Models\CustomerActivityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerActivityStatusFactory extends Factory
{
    protected $model = CustomerActivityStatus::class;

    public function definition(): array
    {
        $memberSinceMonths = fake()->numberBetween(1,12);
        $hasTrainedLastMonth = fake()->boolean();
    
        // Hvis $hasTrainedLastMonth = true, er DaysSinceLastVisit = et tal mellem 1 og 30
        if ($hasTrainedLastMonth) {
            $daysSinceLastVisit = fake()->numberBetween(1, 30); 
            $trainingSessionsThisMonth = fake()->numberBetween(1, 10); 
        } else {
            // Hvis $hasTrainedLastMonth = false, er DaysSinceLastVisit = et tal mellem 31 og 365
            $daysSinceLastVisit = fake()->numberBetween(31, 365); 
            $trainingSessionsThisMonth = 0; 
        }
    
        return [
            'MemberSinceMonths' => $memberSinceMonths,
            'HasTrainedLastMonth' => $hasTrainedLastMonth,
            'DaysSinceLastVisit' => $daysSinceLastVisit,
            'TrainingSessionsThisMonth' => $trainingSessionsThisMonth,
        ];
    }
}