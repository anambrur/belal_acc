<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierDueReportService
{
    public function baseQuery(?string $startDate = null, ?string $endDate = null): Builder
    {
        $purchaseInvoices = DB::table('purchases')
            ->select('supplier_id', 'invoice_id', DB::raw('MAX(total_price) as invoice_total'))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->groupBy('supplier_id', 'invoice_id');

        $purchaseAgg = DB::query()
            ->fromSub($purchaseInvoices, 'purchase_invoices')
            ->select('supplier_id', DB::raw('SUM(invoice_total) as purchase_total'))
            ->groupBy('supplier_id');

        $returnAgg = DB::table('purchase_returns')
            ->select('supplier_id', DB::raw('SUM(total_amount) as return_total'))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->groupBy('supplier_id');

        $paymentAgg = DB::table('payments')
            ->select('payable_id', DB::raw('SUM(amount) as payment_total'))
            ->where('payable_type', Supplier::class)
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->groupBy('payable_id');

        return DB::table('suppliers')
            ->leftJoin('roads', 'roads.id', '=', 'suppliers.road_id')
            ->leftJoin('locations', 'locations.id', '=', 'suppliers.location_id')
            ->leftJoinSub($purchaseAgg, 'purchase_agg', 'purchase_agg.supplier_id', '=', 'suppliers.id')
            ->leftJoinSub($returnAgg, 'return_agg', 'return_agg.supplier_id', '=', 'suppliers.id')
            ->leftJoinSub($paymentAgg, 'payment_agg', 'payment_agg.payable_id', '=', 'suppliers.id')
            ->select([
                'suppliers.id',
                'suppliers.supplier_name',
                'suppliers.phone',
                'roads.name as road_name',
                'locations.address as location_address',
                DB::raw('COALESCE(purchase_agg.purchase_total, 0) as purchase_total'),
                DB::raw('COALESCE(return_agg.return_total, 0) as return_total'),
                DB::raw('COALESCE(payment_agg.payment_total, 0) as payment_total'),
                DB::raw('(COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) as due_total'),
            ]);
    }

    public function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('filter_road') && $request->filter_road !== 'All Road') {
            $query->where('suppliers.road_id', $request->filter_road);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('suppliers.location_id', $request->filter_address);
        }

        $this->applyDueStatusFilter($query, $request->input('filter_status'));

        $search = trim((string) $request->input('search.value', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($inner) use ($like) {
                $inner->where('suppliers.supplier_name', 'like', $like)
                    ->orWhere('suppliers.phone', 'like', $like)
                    ->orWhere('roads.name', 'like', $like)
                    ->orWhere('locations.address', 'like', $like);
            });
        }

        return $query;
    }

    /**
     * @return array{supplier_count: int, total_purchases: float, total_returns: float, total_payments: float, total_due: float, due_count: int, cleared_count: int, advance_count: int}
     */
    public function totals(Builder $query): array
    {
        $row = (clone $query)
            ->reorder()
            ->select([
                DB::raw('COUNT(*) as supplier_count'),
                DB::raw('COALESCE(SUM(COALESCE(purchase_agg.purchase_total, 0)), 0) as total_purchases'),
                DB::raw('COALESCE(SUM(COALESCE(return_agg.return_total, 0)), 0) as total_returns'),
                DB::raw('COALESCE(SUM(COALESCE(payment_agg.payment_total, 0)), 0) as total_payments'),
                DB::raw('COALESCE(SUM(COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)), 0) as total_due'),
                DB::raw('SUM(CASE WHEN (COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) > 0 THEN 1 ELSE 0 END) as due_count'),
                DB::raw('SUM(CASE WHEN (COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) = 0 THEN 1 ELSE 0 END) as cleared_count'),
                DB::raw('SUM(CASE WHEN (COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) < 0 THEN 1 ELSE 0 END) as advance_count'),
            ])
            ->first();

        return [
            'supplier_count' => (int) ($row->supplier_count ?? 0),
            'total_purchases' => (float) ($row->total_purchases ?? 0),
            'total_returns' => (float) ($row->total_returns ?? 0),
            'total_payments' => (float) ($row->total_payments ?? 0),
            'total_due' => (float) ($row->total_due ?? 0),
            'due_count' => (int) ($row->due_count ?? 0),
            'cleared_count' => (int) ($row->cleared_count ?? 0),
            'advance_count' => (int) ($row->advance_count ?? 0),
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

    public function applyDueStatusFilter(Builder $query, ?string $status): void
    {
        if ($status === null || $status === '' || $status === 'All Status') {
            return;
        }

        $dueExpr = '(COALESCE(purchase_agg.purchase_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0))';

        match ($status) {
            'due' => $query->whereRaw("{$dueExpr} > 0"),
            'cleared' => $query->whereRaw("{$dueExpr} = 0"),
            'advance' => $query->whereRaw("{$dueExpr} < 0"),
            default => null,
        };
    }
}
