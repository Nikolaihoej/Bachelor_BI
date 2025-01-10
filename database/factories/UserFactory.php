<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [ 
            'email' => 'fitcenter@email.com',
            'password' => Hash::make('notsafe') // Generer et hash af "notsafe"
        ];
    }
}
