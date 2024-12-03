<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MembershipTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Vælg et tilfældigt tal mellem 1 og 3
        $membershipTypeID = $this->faker->numberBetween(1, 3);

        // Brug tallet til at vælge det tilsvarende navn
        $typeName = match ($membershipTypeID) {
            1 => 'Basic',
            2 => 'Premium',
            3 => 'Student',
        };

        return [
            'MembershipTypeID' => $membershipTypeID,
            'TypeName' => $typeName,
        ];
    }
}
