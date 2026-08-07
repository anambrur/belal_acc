<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossReportService
{
    /**
     * @return array{
     *     start_date: ?string,
     *     end_date: ?string,
     *     sales_total: float,
     *     sales_discount: float,
     *     sales_returns: float,
     *     net_sales: float,
     *     purchases_total: float,
     *     purchase_returns: float,
     *     net_purchases: float,
     *     expenses_total: float,
     *     expense_by_category: array<int, object>,
     *     gross_profit: float,
     *     net_profit: float
     * }
     */
    public function summary(Request $request): array
    {
        [$startDate, $endDate] = $this->parseDateRange($request);

        $salesQuery = DB::table('sells');
        $salesReturnQuery = DB::table('sells_returns');
        $purchaseInvoices = DB::table('purchases')
            ->select('invoice_id', DB::raw('MAX(total_price) as invoice_total'), DB::raw('MAX(total_discount) as invoice_discount'));
        $purchaseReturnQuery = DB::table('purchase_returns');
        $expenseQuery = DB::table('expenses')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id');

        if ($startDate && $endDate) {
            $salesQuery->whereBetween('date', [$startDate, $endDate]);
            $salesReturnQuery->whereBetween('date', [$startDate, $endDate]);
            $purchaseInvoices->whereBetween('date', [$startDate, $endDate]);
            $purchaseReturnQuery->whereBetween('date', [$startDate, $endDate]);
            $expenseQuery->whereBetween('expenses.date', [$startDate, $endDate]);
        }

        $salesRow = (clone $salesQuery)
            ->selectRaw('COALESCE(SUM(total_price), 0) as sales_total, COALESCE(SUM(total_discount), 0) as sales_discount')
            ->first();

        $salesReturns = (float) (clone $salesReturnQuery)->sum('total_amount');

        $purchaseRow = DB::query()
            ->fromSub($purchaseInvoices->groupBy('invoice_id'), 'purchase_invoices')
            ->selectRaw('COALESCE(SUM(invoice_total), 0) as purchases_total, COALESCE(SUM(invoice_discount), 0) as purchase_discount')
            ->first();

        $purchaseReturns = (float) (clone $purchaseReturnQuery)->sum('total_amount');

        $expensesTotal = (float) (clone $expenseQuery)->sum('expenses.amount');

        $expenseByCategory = (clone $expenseQuery)
            ->select([
                'expense_categories.id as category_id',
                DB::raw('COALESCE(expense_categories.expense_category, \'Uncategorized\') as category_name'),
                DB::raw('COALESCE(SUM(expenses.amount), 0) as total_amount'),
                DB::raw('COUNT(expenses.id) as expense_count'),
            ])
            ->groupBy('expense_categories.id', 'expense_categories.expense_category')
            ->orderByDesc('total_amount')
            ->get()
            ->all();

        $salesTotal = (float) ($salesRow->sales_total ?? 0);
        $salesDiscount = (float) ($salesRow->sales_discount ?? 0);
        $netSales = $salesTotal - $salesReturns;
        $purchasesTotal = (float) ($purchaseRow->purchases_total ?? 0);
        $netPurchases = $purchasesTotal - $purchaseReturns;
        $grossProfit = $netSales - $netPurchases;
        $netProfit = $grossProfit - $expensesTotal;

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sales_total' => $salesTotal,
            'sales_discount' => $salesDiscount,
            'sales_returns' => $salesReturns,
            'net_sales' => $netSales,
            'purchases_total' => $purchasesTotal,
            'purchase_returns' => $purchaseReturns,
            'net_purchases' => $netPurchases,
            'expenses_total' => $expensesTotal,
            'expense_by_category' => $expenseByCategory,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
        ];
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    public function parseDateRange(Request $request): array
    {
        if (!$request->filled('date_range')) {
            return [null, null];
        }

        $dates = explode(' - ', $request->date_range);
        if (count($dates) !== 2) {
            return [null, null];
        }

        return [trim($dates[0]), trim($dates[1])];
    }
}
