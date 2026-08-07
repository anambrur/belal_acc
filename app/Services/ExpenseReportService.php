<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseReportService
{
    public function baseQuery(): Builder
    {
        return DB::table('expenses')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->select([
                'expenses.id',
                'expenses.date',
                'expenses.amount',
                'expenses.description',
                'expenses.expense_category_id',
                'expense_categories.expense_category as category_name',
            ]);
    }

    public function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('filter_category') && $request->filter_category !== 'All Category') {
            $query->where('expenses.expense_category_id', $request->filter_category);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('expenses.date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        $search = trim((string) $request->input('search.value', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($inner) use ($like) {
                $inner->where('expense_categories.expense_category', 'like', $like)
                    ->orWhere('expenses.description', 'like', $like)
                    ->orWhere('expenses.date', 'like', $like)
                    ->orWhere('expenses.amount', 'like', $like);
            });
        }

        return $query;
    }

    /**
     * @return array{expense_count: int, total_amount: float, category_count: int}
     */
    public function totals(Builder $query): array
    {
        $row = (clone $query)
            ->reorder()
            ->select([
                DB::raw('COUNT(*) as expense_count'),
                DB::raw('COALESCE(SUM(expenses.amount), 0) as total_amount'),
                DB::raw('COUNT(DISTINCT expenses.expense_category_id) as category_count'),
            ])
            ->first();

        return [
            'expense_count' => (int) ($row->expense_count ?? 0),
            'total_amount' => (float) ($row->total_amount ?? 0),
            'category_count' => (int) ($row->category_count ?? 0),
        ];
    }
}
