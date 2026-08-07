<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        $today = Carbon::today()->toDateString();
        $monthStart = Carbon::now()->startOfMonth()->toDateString();
        $monthEnd = Carbon::now()->endOfMonth()->toDateString();

        $salesTotal = (float) DB::table('sells')->sum('total_price');
        $salesReturns = (float) DB::table('sells_returns')->sum('total_amount');
        $customerPayments = (float) DB::table('payments')
            ->where('payable_type', Customer::class)
            ->sum('amount');

        $purchasesTotal = $this->purchaseInvoiceTotal();
        $purchaseReturns = (float) DB::table('purchase_returns')->sum('total_amount');
        $supplierPayments = (float) DB::table('payments')
            ->where('payable_type', Supplier::class)
            ->sum('amount');

        return [
            'cash_in_hand' => (float) totalBalance(),
            'customer_due' => $salesTotal - $salesReturns - $customerPayments,
            'supplier_due' => $purchasesTotal - $purchaseReturns - $supplierPayments,
            'today_sales' => (float) DB::table('sells')->where('date', $today)->sum('total_price'),
            'today_collections' => (float) DB::table('payments')
                ->where('payment_for', 'cus_due_return')
                ->where('date', $today)
                ->sum('amount'),
            'today_supplier_payments' => (float) DB::table('payments')
                ->where('payable_type', Supplier::class)
                ->where('date', $today)
                ->sum('amount'),
            'loan_outstanding' => $this->loanOutstanding(),
            'accounts_total' => (float) DB::table('accounts')->sum('amount'),
            'total_sales' => $salesTotal,
            'total_purchases' => $purchasesTotal,
            'customer_count' => (int) DB::table('customers')->count(),
            'supplier_count' => (int) DB::table('suppliers')->count(),
            'purchase_invoice_count' => (int) DB::table('purchases')->selectRaw('COUNT(DISTINCT invoice_id) as c')->value('c'),
            'sales_invoice_count' => (int) DB::table('sells')->count(),
            'month_sales' => (float) DB::table('sells')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('total_price'),
            'month_collections' => (float) DB::table('payments')
                ->where('payment_for', 'cus_due_return')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount'),
            'month_expenses' => (float) DB::table('expenses')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount'),
            'chart_years' => $this->availableYears(),
            'current_year' => (int) Carbon::now()->year,
            'top_due_customers' => $this->topCustomersByDue(10),
            'top_due_suppliers' => $this->topSuppliersByDue(10),
            'account_balances' => $this->accountBalances(),
        ];
    }

    /**
     * @return array{sales: array<int, float>, purchase: array<int, float>, categories: array<int, string>, year: int}
     */
    public function salesPurchaseChart(int $year): array
    {
        $sellData = DB::table('sells')
            ->select(DB::raw('SUM(total_price) as total_price'), DB::raw('MONTH(date) as month'))
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        $purchaseInvoices = DB::table('purchases')
            ->select(
                DB::raw('MONTH(date) as month'),
                'invoice_id',
                DB::raw('MAX(total_price) as invoice_total')
            )
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'), 'invoice_id');

        $purchaseData = DB::query()
            ->fromSub($purchaseInvoices, 'purchase_invoices')
            ->select('month', DB::raw('SUM(invoice_total) as total_price'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $sales = array_fill(0, 12, 0.0);
        $purchases = array_fill(0, 12, 0.0);

        foreach ($sellData as $row) {
            $sales[(int) $row->month - 1] = (float) $row->total_price;
        }

        foreach ($purchaseData as $row) {
            $purchases[(int) $row->month - 1] = (float) $row->total_price;
        }

        return [
            'sales' => $sales,
            'purchase' => $purchases,
            'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'year' => $year,
        ];
    }

    /**
     * Daily due collections for the last 30 days.
     *
     * @return array{categories: array<int, string>, series: array<int, float>}
     */
    public function collectionsChart(): array
    {
        $end = Carbon::today();
        $start = Carbon::today()->subDays(29);

        $rows = DB::table('payments')
            ->select('date', DB::raw('SUM(amount) as total_amount'))
            ->where('payment_for', 'cus_due_return')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->groupBy('date')
            ->pluck('total_amount', 'date');

        $categories = [];
        $series = [];

        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $key = $day->toDateString();
            $categories[] = $day->format('d M');
            $series[] = (float) ($rows[$key] ?? 0);
        }

        return [
            'categories' => $categories,
            'series' => $series,
        ];
    }

    /**
     * @return array{labels: array<int, string>, series: array<int, int>}
     */
    public function paymentStatusChart(): array
    {
        $row = DB::table('sells')
            ->selectRaw('
                SUM(CASE WHEN paid_amount >= total_price THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN paid_amount > 0 AND paid_amount < total_price THEN 1 ELSE 0 END) as partial_count,
                SUM(CASE WHEN paid_amount = 0 AND total_price > 0 THEN 1 ELSE 0 END) as unpaid_count
            ')
            ->first();

        return [
            'labels' => ['Paid', 'Partial', 'Unpaid'],
            'series' => [
                (int) ($row->paid_count ?? 0),
                (int) ($row->partial_count ?? 0),
                (int) ($row->unpaid_count ?? 0),
            ],
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function topCustomersByDue(int $limit = 10): Collection
    {
        $sellAgg = DB::table('sells')
            ->select('customer_id', DB::raw('SUM(total_price) as sell_total'))
            ->groupBy('customer_id');

        $returnAgg = DB::table('sells_returns')
            ->select('customer_id', DB::raw('SUM(total_amount) as return_total'))
            ->groupBy('customer_id');

        $paymentAgg = DB::table('payments')
            ->select('payable_id', DB::raw('SUM(amount) as payment_total'))
            ->where('payable_type', Customer::class)
            ->groupBy('payable_id');

        return DB::table('customers')
            ->leftJoin('locations', 'locations.id', '=', 'customers.location_id')
            ->leftJoinSub($sellAgg, 'sell_agg', 'sell_agg.customer_id', '=', 'customers.id')
            ->leftJoinSub($returnAgg, 'return_agg', 'return_agg.customer_id', '=', 'customers.id')
            ->leftJoinSub($paymentAgg, 'payment_agg', 'payment_agg.payable_id', '=', 'customers.id')
            ->select([
                'customers.id',
                'customers.customer_name',
                'customers.phone',
                'locations.address as location_address',
                DB::raw('(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) as due_total'),
            ])
            ->havingRaw('due_total > 0')
            ->orderByDesc('due_total')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function topSuppliersByDue(int $limit = 10): Collection
    {
        $purchaseInvoices = DB::table('purchases')
            ->select('supplier_id', 'invoice_id', DB::raw('MAX(total_price) as invoice_total'))
            ->groupBy('supplier_id', 'invoice_id');

        $purchaseAgg = DB::query()
            ->fromSub($purchaseInvoices, 'purchase_invoices')
            ->select('supplier_id', DB::raw('SUM(invoice_total) as purchase_total'))
            ->groupBy('supplier_id');

        $returnAgg = DB::table('purchase_returns')
            ->select('supplier_id', DB::raw('SUM(total_amount) as return_total'))
            ->groupBy('supplier_id');

        $paymentAgg = DB::table('payments')
            ->select('payable_id', DB::raw('SUM(amount) as payment_total'))
            ->where('payable_type', Supplier::class)
            ->groupBy('payable_id');

        return DB::table('suppliers')
            ->leftJoin('locations', 'locations.id', '=', 'suppliers.location_id')
            ->leftJoinSub($purchaseAgg, 'purchase_agg', 'purchase_agg.supplier_id', '=', 'suppliers.id')
            ->leftJoinSub($returnAgg, 'return_agg', 'return_agg.supplier_id', '=', 'suppliers.id')
            ->leftJoinSub($paymentAgg, 'payment_agg', 'payment_agg.payable_id', '=', 'suppliers.id')
            ->select([
                'suppliers.id',
                'suppliers.supplier_name',
                'suppliers.phone',
                'locations.address as location_address',
                DB::raw('(COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) as due_total'),
            ])
            ->havingRaw('due_total > 0')
            ->orderByDesc('due_total')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function accountBalances(): Collection
    {
        return DB::table('accounts')
            ->select('id', 'bank_name', 'branch_name', 'account_number', 'amount')
            ->orderByDesc('amount')
            ->get();
    }

    /**
     * Monthly expense totals for the last 6 calendar months (including current).
     *
     * @return array{categories: array<int, string>, series: array<int, float>}
     */
    public function expenseTrendChart(): array
    {
        $end = Carbon::now()->startOfMonth();
        $start = $end->copy()->subMonths(5);

        $rows = DB::table('expenses')
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->whereBetween('date', [$start->toDateString(), Carbon::now()->endOfMonth()->toDateString()])
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->year, $row->month));

        $categories = [];
        $series = [];

        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addMonth()) {
            $key = $cursor->format('Y-m');
            $categories[] = $cursor->format('M Y');
            $series[] = (float) ($rows[$key]->total_amount ?? 0);
        }

        return [
            'categories' => $categories,
            'series' => $series,
        ];
    }

    /**
     * This-month sales totals grouped by customer road.
     *
     * @return array{categories: array<int, string>, series: array<int, float>}
     */
    public function roadSalesChart(): array
    {
        $monthStart = Carbon::now()->startOfMonth()->toDateString();
        $monthEnd = Carbon::now()->endOfMonth()->toDateString();

        $rows = DB::table('sells')
            ->leftJoin('customers', 'customers.id', '=', 'sells.customer_id')
            ->leftJoin('roads', 'roads.id', '=', 'customers.road_id')
            ->select(
                DB::raw("COALESCE(roads.name, 'Unassigned') as road_name"),
                DB::raw('SUM(sells.total_price) as total_sales')
            )
            ->whereBetween('sells.date', [$monthStart, $monthEnd])
            ->groupBy('roads.id', 'roads.name')
            ->orderByDesc('total_sales')
            ->limit(12)
            ->get();

        return [
            'categories' => $rows->pluck('road_name')->map(fn ($name) => (string) $name)->all(),
            'series' => $rows->pluck('total_sales')->map(fn ($amount) => (float) $amount)->all(),
        ];
    }

    private function loanOutstanding(): float
    {
        $loanPrincipal = (float) DB::table('loans')->sum('amount');
        $loanPaid = (float) DB::table('loan_payments')->sum('paid_amount');

        return $loanPrincipal - $loanPaid;
    }

    private function purchaseInvoiceTotal(): float
    {
        $invoices = DB::table('purchases')
            ->select('invoice_id', DB::raw('MAX(total_price) as invoice_total'))
            ->groupBy('invoice_id');

        return (float) DB::query()
            ->fromSub($invoices, 'purchase_invoices')
            ->sum('invoice_total');
    }

    /**
     * @return array<int, int>
     */
    private function availableYears(): array
    {
        $sellYears = DB::table('sells')
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->whereNotNull('date')
            ->pluck('year');

        $purchaseYears = DB::table('purchases')
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->whereNotNull('date')
            ->pluck('year');

        $years = $sellYears
            ->merge($purchaseYears)
            ->map(fn ($y) => (int) $y)
            ->filter(fn ($y) => $y > 0)
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        $currentYear = (int) Carbon::now()->year;
        if (!in_array($currentYear, $years, true)) {
            array_unshift($years, $currentYear);
        }

        return $years ?: [$currentYear];
    }
}
