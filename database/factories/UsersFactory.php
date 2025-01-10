<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [ 
            'email' => 'fitcenter@email.com',
            'password' => 'notsafe', // Generer et hash af "notsafe"
        ];
    }
}
