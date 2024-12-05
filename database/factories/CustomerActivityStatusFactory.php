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
        // Generer en tilfældig værdi for om kunden har trænet sidste måned
        $hasTrainedLastMonth = fake()->boolean();
    
        // Hvis kunden har trænet sidste måned, sættes DaysSinceLastVisit til et lavt tal og TrainingSessionsThisMonth til et tal større end 0
        if ($hasTrainedLastMonth) {
            $daysSinceLastVisit = fake()->numberBetween(1, 30); // Kunden har besøgt nylig
            $trainingSessionsThisMonth = fake()->numberBetween(1, 10); // Kunden har trænet flere gange denne måned
        } else {
            // Hvis kunden ikke har trænet sidste måned, sættes DaysSinceLastVisit til et højere tal og TrainingSessionsThisMonth til 0
            $daysSinceLastVisit = fake()->numberBetween(31, 365); // Kunden har ikke besøgt på lang tid
            $trainingSessionsThisMonth = 0; // Ingen træning denne måned
        }
    
        return [
            'MemberSinceMonths' => $memberSinceMonths,
            'HasTrainedLastMonth' => $hasTrainedLastMonth,
            'DaysSinceLastVisit' => $daysSinceLastVisit,
            'TrainingSessionsThisMonth' => $trainingSessionsThisMonth,
        ];
    }
}