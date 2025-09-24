<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    /**
     * Display expense dashboard
     */
    public function index(Request $request)
    {
        $query = Expense::query();

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                $query->byDateRange($request->start_date, $request->end_date);
            } else {
                $query->whereDate('date', '>=', $request->start_date);
            }
        } elseif ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $expenses = $query->orderBy('date', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);

        // Get categories for filter dropdown
        $categories = Expense::select('category')
                            ->distinct()
                            ->orderBy('category')
                            ->pluck('category');

        // Get expense summary by category
        $categoryStats = Expense::selectRaw('category, SUM(amount) as total, COUNT(*) as count')
                               ->groupBy('category')
                               ->orderBy('total', 'desc')
                               ->get();

        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'categories' => $categories,
            'categoryStats' => $categoryStats,
            'filters' => $request->only(['category', 'date', 'start_date', 'end_date']),
            'totalExpenses' => $expenses->total(),
            'currentTotal' => $query->sum('amount'),
        ]);
    }

    /**
     * Show the form for creating a new expense
     */
    public function create()
    {
        return Inertia::render('Expenses/Create');
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0|max:999999999.99',
                'description' => 'required|string|max:255',
                'date' => 'required|date|before_or_equal:today',
            ]);

            // Auto-categorize based on description
            $category = Expense::categorizeDescription($validated['description']);

            $expense = Expense::create([
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'date' => $validated['date'],
                'category' => $category,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense created successfully',
                    'data' => $expense,
                ], 201);
            }

            return redirect()->route('expenses.index')
                           ->with('success', 'Expense created successfully');

        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Display the specified expense
     */
    public function show(Expense $expense)
    {
        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    /**
     * Show the form for editing the specified expense
     */
    public function edit(Expense $expense)
    {
        return Inertia::render('Expenses/Edit', [
            'expense' => $expense,
        ]);
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0|max:999999999.99',
                'description' => 'required|string|max:255',
                'date' => 'required|date|before_or_equal:today',
                'category' => 'sometimes|string|max:100',
            ]);

            // Auto-categorize if category not provided
            if (!isset($validated['category'])) {
                $validated['category'] = Expense::categorizeDescription($validated['description']);
            }

            $expense->update($validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense updated successfully',
                    'data' => $expense,
                ]);
            }

            return redirect()->route('expenses.index')
                           ->with('success', 'Expense updated successfully');

        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Request $request, Expense $expense)
    {
        $expense->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully',
            ]);
        }

        return redirect().route('expenses.index')
                       ->with('success', 'Expense deleted successfully');
    }

    /**
     * Get expense statistics
     */
    public function stats()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $currentMonthTotal = Expense::byMonth($currentMonth->month, $currentMonth->year)->sum('amount');
        $lastMonthTotal = Expense::byMonth($lastMonth->month, $lastMonth->year)->sum('amount');

        $categoryStats = Expense::selectRaw('category, SUM(amount) as total, COUNT(*) as count')
                               ->groupBy('category')
                               ->orderBy('total', 'desc')
                               ->get();

        $monthlyStats = Expense::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(amount) as total')
                              ->groupBy('year', 'month')
                              ->orderBy('year', 'desc')
                              ->orderBy('month', 'desc')
                              ->limit(12)
                              ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'currentMonthTotal' => $currentMonthTotal,
                'lastMonthTotal' => $lastMonthTotal,
                'categoryStats' => $categoryStats,
                'monthlyStats' => $monthlyStats,
                'totalExpenses' => Expense::count(),
                'totalAmount' => Expense::sum('amount'),
            ],
        ]);
    }
}
