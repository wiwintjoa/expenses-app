<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        $descriptions = [
            'Grab ride to office',
            'Starbucks coffee',
            'Lunch at McDonald\'s',
            'Shopee online shopping',
            'Fuel at gas station',
            'Netflix subscription',
            'Grocery shopping',
            'Doctor consultation',
            'Cinema ticket',
            'KFC dinner',
            'Uber ride home',
            'Coffee with friends',
            'Pharmacy medicine',
            'Tokopedia purchase',
            'Restaurant dinner',
            'Taxi to airport',
            'Spotify subscription',
            'Food delivery',
            'Shopping mall',
            'Hospital checkup'
        ];

        $description = $this->faker->randomElement($descriptions);
        
        return [
            'amount' => $this->faker->randomFloat(2, 5, 500),
            'description' => $description,
            'category' => Expense::categorizeDescription($description),
            'date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}