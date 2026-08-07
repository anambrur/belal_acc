<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerDueReportService
{
    public function baseQuery(?string $startDate = null, ?string $endDate = null): Builder
    {
        $sellAgg = DB::table('sells')
            ->select('customer_id', DB::raw('SUM(total_price) as sell_total'))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->groupBy('customer_id');

        $returnAgg = DB::table('sells_returns')
            ->select('customer_id', DB::raw('SUM(total_amount) as return_total'))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->groupBy('customer_id');

        $paymentAgg = DB::table('payments')
            ->select('payable_id', DB::raw('SUM(amount) as payment_total'))
            ->where('payable_type', Customer::class)
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->groupBy('payable_id');

        return DB::table('customers')
            ->leftJoin('roads', 'roads.id', '=', 'customers.road_id')
            ->leftJoin('locations', 'locations.id', '=', 'customers.location_id')
            ->leftJoinSub($sellAgg, 'sell_agg', 'sell_agg.customer_id', '=', 'customers.id')
            ->leftJoinSub($returnAgg, 'return_agg', 'return_agg.customer_id', '=', 'customers.id')
            ->leftJoinSub($paymentAgg, 'payment_agg', 'payment_agg.payable_id', '=', 'customers.id')
            ->select([
                'customers.id',
                'customers.customer_name',
                'customers.phone',
                'roads.name as road_name',
                'locations.address as location_address',
                DB::raw('COALESCE(sell_agg.sell_total, 0) as sell_total'),
                DB::raw('COALESCE(return_agg.return_total, 0) as return_total'),
                DB::raw('COALESCE(payment_agg.payment_total, 0) as payment_total'),
                DB::raw('(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) as due_total'),
            ]);
    }

    public function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('filter_road') && $request->filter_road !== 'All Road') {
            $query->where('customers.road_id', $request->filter_road);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('customers.location_id', $request->filter_address);
        }

        $this->applyDueStatusFilter($query, $request->input('filter_status'));

        $search = trim((string) $request->input('search.value', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($inner) use ($like) {
                $inner->where('customers.customer_name', 'like', $like)
                    ->orWhere('customers.phone', 'like', $like)
                    ->orWhere('roads.name', 'like', $like)
                    ->orWhere('locations.address', 'like', $like);
            });
        }

        return $query;
    }

    /**
     * @return array{customer_count: int, total_sales: float, total_returns: float, total_payments: float, total_due: float, due_count: int, cleared_count: int, advance_count: int}
     */
    public function totals(Builder $query): array
    {
        $row = (clone $query)
            ->reorder()
            ->select([
                DB::raw('COUNT(*) as customer_count'),
                DB::raw('COALESCE(SUM(COALESCE(sell_agg.sell_total, 0)), 0) as total_sales'),
                DB::raw('COALESCE(SUM(COALESCE(return_agg.return_total, 0)), 0) as total_returns'),
                DB::raw('COALESCE(SUM(COALESCE(payment_agg.payment_total, 0)), 0) as total_payments'),
                DB::raw('COALESCE(SUM(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)), 0) as total_due'),
                DB::raw('SUM(CASE WHEN (COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) > 0 THEN 1 ELSE 0 END) as due_count'),
                DB::raw('SUM(CASE WHEN (COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) = 0 THEN 1 ELSE 0 END) as cleared_count'),
                DB::raw('SUM(CASE WHEN (COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) < 0 THEN 1 ELSE 0 END) as advance_count'),
            ])
            ->first();

        return [
            'customer_count' => (int) ($row->customer_count ?? 0),
            'total_sales' => (float) ($row->total_sales ?? 0),
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

        $dueExpr = '(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0))';

        match ($status) {
            'due' => $query->whereRaw("{$dueExpr} > 0"),
            'cleared' => $query->whereRaw("{$dueExpr} = 0"),
            'advance' => $query->whereRaw("{$dueExpr} < 0"),
            default => null,
        };
    }
}
