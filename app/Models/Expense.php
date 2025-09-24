<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'category',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Auto-categorize based on description
     */
    public static function categorizeDescription(string $description): string
    {
        $description = strtolower($description);
        
        // Transport categories
        if (str_contains($description, 'grab') || 
            str_contains($description, 'gojek') || 
            str_contains($description, 'uber') || 
            str_contains($description, 'taxi') ||
            str_contains($description, 'fuel') ||
            str_contains($description, 'gas station')) {
            return 'Transport';
        }

        // Food & Drink categories
        if (str_contains($description, 'starbucks') || 
            str_contains($description, 'mcdonald') || 
            str_contains($description, 'kfc') || 
            str_contains($description, 'restaurant') ||
            str_contains($description, 'coffee') ||
            str_contains($description, 'food') ||
            str_contains($description, 'lunch') ||
            str_contains($description, 'dinner') ||
            str_contains($description, 'breakfast')) {
            return 'Food & Drink';
        }

        // Shopping categories
        if (str_contains($description, 'shopee') || 
            str_contains($description, 'tokopedia') || 
            str_contains($description, 'mall') || 
            str_contains($description, 'store') ||
            str_contains($description, 'shopping')) {
            return 'Shopping';
        }

        // Entertainment categories
        if (str_contains($description, 'netflix') || 
            str_contains($description, 'spotify') || 
            str_contains($description, 'cinema') || 
            str_contains($description, 'movie') ||
            str_contains($description, 'game')) {
            return 'Entertainment';
        }

        // Health categories
        if (str_contains($description, 'hospital') || 
            str_contains($description, 'doctor') || 
            str_contains($description, 'pharmacy') || 
            str_contains($description, 'medicine')) {
            return 'Health';
        }

        return 'Others';
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('date', [$startDate, $endDate]);
        }
        return $query->whereDate('date', $startDate);
    }

    /**
     * Scope for filtering by month
     */
    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}