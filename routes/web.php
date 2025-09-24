<?php

use App\Http\Controllers\Api\ExpenseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('expenses.index');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// Expense routes
Route::resource('expenses', ExpenseController::class);
Route::get('/expenses-stats', [ExpenseController::class, 'stats'])->name('expenses.stats');