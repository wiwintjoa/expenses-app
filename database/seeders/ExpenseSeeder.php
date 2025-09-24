<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 sample expenses
        Expense::factory()->count(50)->create();

        // Create some specific test cases
        Expense::create([
            'amount' => 25.50,
            'description' => 'Grab ride to downtown',
            'category' => 'Transport',
            'date' => now()->subDays(1)->format('Y-m-d'),
        ]);

        Expense::create([
            'amount' => 12.00,
            'description' => 'Starbucks latte',
            'category' => 'Food & Drink',
            'date' => now()->format('Y-m-d'),
        ]);

        Expense::create([
            'amount' => 150.00,
            'description' => 'Shopee electronics',
            'category' => 'Shopping',
            'date' => now()->subDays(3)->format('Y-m-d'),
        ]);

        Expense::create([
            'amount' => 45.75,
            'description' => 'Random expense',
            'category' => 'Others',
            'date' => now()->subDays(2)->format('Y-m-d'),
        ]);
    }
}