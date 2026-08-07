<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellReportService
{
    public function baseQuery(): Builder
    {
        return DB::table('sells')
            ->leftJoin('customers', 'customers.id', '=', 'sells.customer_id')
            ->leftJoin('locations', 'locations.id', '=', 'customers.location_id')
            ->select([
                'sells.id',
                'sells.invoice_id',
                'sells.customer_id',
                'sells.total_price',
                'sells.paid_amount',
                'sells.total_discount',
                'sells.date',
                'sells.location_id',
                'sells.created_at',
                'customers.customer_name',
                'locations.address as location_address',
                DB::raw('(sells.total_price - sells.paid_amount) as due_amount'),
            ]);
    }

    public function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('filter_customer') && $request->filter_customer !== 'All Customer') {
            $query->where('sells.customer_id', $request->filter_customer);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('sells.location_id', $request->filter_address);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('sells.date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        $this->applyPaymentStatusFilter($query, $request->input('filter_status'), 'sells');

        $search = trim((string) $request->input('search.value', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($inner) use ($like) {
                $inner->where('customers.customer_name', 'like', $like)
                    ->orWhere('locations.address', 'like', $like)
                    ->orWhere('sells.invoice_id', 'like', $like)
                    ->orWhere('sells.date', 'like', $like);
            });
        }

        return $query;
    }

    /**
     * @return array{
     *     invoice_count: int,
     *     total_sales: float,
     *     total_discount: float,
     *     total_paid: float,
     *     total_due: float,
     *     paid_count: int,
     *     unpaid_count: int,
     *     partial_count: int
     * }
     */
    public function totals(Builder $query): array
    {
        $row = (clone $query)
            ->reorder()
            ->select([
                DB::raw('COUNT(*) as invoice_count'),
                DB::raw('COALESCE(SUM(sells.total_price), 0) as total_sales'),
                DB::raw('COALESCE(SUM(sells.total_discount), 0) as total_discount'),
                DB::raw('COALESCE(SUM(sells.paid_amount), 0) as total_paid'),
                DB::raw('COALESCE(SUM(sells.total_price - sells.paid_amount), 0) as total_due'),
                DB::raw('SUM(CASE WHEN sells.paid_amount >= sells.total_price THEN 1 ELSE 0 END) as paid_count'),
                DB::raw('SUM(CASE WHEN sells.paid_amount = 0 AND sells.total_price > 0 THEN 1 ELSE 0 END) as unpaid_count'),
                DB::raw('SUM(CASE WHEN sells.paid_amount > 0 AND sells.paid_amount < sells.total_price THEN 1 ELSE 0 END) as partial_count'),
            ])
            ->first();

        return [
            'invoice_count' => (int) ($row->invoice_count ?? 0),
            'total_sales' => (float) ($row->total_sales ?? 0),
            'total_discount' => (float) ($row->total_discount ?? 0),
            'total_paid' => (float) ($row->total_paid ?? 0),
            'total_due' => (float) ($row->total_due ?? 0),
            'paid_count' => (int) ($row->paid_count ?? 0),
            'unpaid_count' => (int) ($row->unpaid_count ?? 0),
            'partial_count' => (int) ($row->partial_count ?? 0),
        ];
    }

    public function applyPaymentStatusFilter(Builder $query, ?string $status, string $table): void
    {
        if ($status === null || $status === '' || $status === 'All Status') {
            return;
        }

        $price = "{$table}.total_price";
        $paid = "{$table}.paid_amount";

        match ($status) {
            'paid' => $query->whereRaw("{$paid} >= {$price}"),
            'unpaid' => $query->whereRaw("{$paid} = 0 AND {$price} > 0"),
            'partial' => $query->whereRaw("{$paid} > 0 AND {$paid} < {$price}"),
            default => null,
        };
    }
}
