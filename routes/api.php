<?php

use App\Http\Controllers\Api\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Expense API routes
    Route::apiResource('expenses', ExpenseController::class);
    Route::get('expenses-stats', [ExpenseController::class, 'stats']);
    
    // Health check
    Route::get('health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
        ]);
    });
});