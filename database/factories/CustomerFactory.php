<?php
/**
 * a Factory has the purpose of creating fast data following the scaffolding in the definition function
 * this example creates 100 diffrent users:
 * App\Models\User::factory(100)->create()
 */
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CustomerFactory extends Factory
{

    public function definition(): array
    {

        $startDate = '2024-01-01';
        $endDate = date('Y-m-d'); 

        return [ 
            'Name' => fake()->name(),
            'Address' => fake()->address(),
            'Age' => fake()->numberBetween($int1 = 10, $int2 = 60),
            'Signup_Date' => fake()->dateTimeBetween($startDate, $endDate)->format('d-m-y'),
        ];
    }
}
