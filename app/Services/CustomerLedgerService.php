<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sell;
use App\Models\SellsReturn;
use Illuminate\Support\Collection;

class CustomerLedgerService
{
    /**
     * Build a normalized customer ledger statement.
     *
     * @return array{
     *     entries: Collection<int, object>,
     *     openingBalance: float,
     *     debitTotal: float,
     *     creditTotal: float,
     *     closingBalance: float,
     *     startDate: ?string,
     *     endDate: ?string,
     *     isFiltered: bool
     * }
     */
    public function build(int $customerId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?: null;
        $endDate = $endDate ?: null;
        $isFiltered = $startDate !== null || $endDate !== null;

        $openingBalance = $isFiltered
            ? $this->calculateOpeningBalance($customerId, $startDate)
            : 0.0;

        $sales = $this->salesQuery($customerId, $startDate, $endDate)->get();
        $returns = $this->returnsQuery($customerId, $startDate, $endDate)->get();
        $payments = $this->paymentsQuery($customerId, $startDate, $endDate)->get();

        $entries = collect();

        foreach ($sales as $sale) {
            $entries->push((object) [
                'type' => 'sale',
                'date' => $sale->date,
                'debit' => (float) $sale->total_price,
                'credit' => 0.0,
                'reference' => 'Invoice: ' . $sale->invoice_id,
                'invoice_id' => $sale->invoice_id,
                'source_id' => $sale->id,
                'payment_for' => null,
            ]);
        }

        foreach ($returns as $return) {
            $entries->push((object) [
                'type' => 'return',
                'date' => $return->date,
                'debit' => 0.0,
                'credit' => (float) $return->total_amount,
                'reference' => 'Product Return' . ($return->invoice_id ? ' (Inv: ' . $return->invoice_id . ')' : ''),
                'invoice_id' => $return->invoice_id,
                'source_id' => $return->id,
                'payment_for' => null,
            ]);
        }

        foreach ($payments as $payment) {
            $entries->push((object) [
                'type' => 'payment',
                'date' => $payment->date,
                'debit' => 0.0,
                'credit' => (float) $payment->amount,
                'reference' => $payment->payment_for
                    ? 'Payment — ' . $payment->payment_for
                    : 'Payment',
                'invoice_id' => null,
                'source_id' => $payment->id,
                'payment_for' => $payment->payment_for,
            ]);
        }

        $entries = $entries
            ->sort(function ($a, $b) {
                $dateCompare = strcmp((string) $a->date, (string) $b->date);
                if ($dateCompare !== 0) {
                    return $dateCompare;
                }

                return $a->source_id <=> $b->source_id;
            })
            ->values();

        $runningBalance = $openingBalance;
        $debitTotal = 0.0;
        $creditTotal = 0.0;

        foreach ($entries as $entry) {
            $debitTotal += $entry->debit;
            $creditTotal += $entry->credit;
            $runningBalance += $entry->debit - $entry->credit;
            $entry->running_balance = $runningBalance;
        }

        return [
            'entries' => $entries,
            'openingBalance' => $openingBalance,
            'debitTotal' => $debitTotal,
            'creditTotal' => $creditTotal,
            'closingBalance' => $runningBalance,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isFiltered' => $isFiltered,
        ];
    }

    /**
     * Lifetime due for a customer: sales - returns - payments.
     */
    public function calculateDue(int $customerId): float
    {
        $sales = (float) Sell::where('customer_id', $customerId)->sum('total_price');
        $returns = (float) SellsReturn::where('customer_id', $customerId)->sum('total_amount');
        $payments = (float) Payment::where('payable_id', $customerId)
            ->where('payable_type', Customer::class)
            ->sum('amount');

        return $sales - $returns - $payments;
    }

    private function calculateOpeningBalance(int $customerId, ?string $startDate): float
    {
        if ($startDate === null) {
            return 0.0;
        }

        $sales = (float) Sell::where('customer_id', $customerId)
            ->where('date', '<', $startDate)
            ->sum('total_price');

        $returns = (float) SellsReturn::where('customer_id', $customerId)
            ->where('date', '<', $startDate)
            ->sum('total_amount');

        $payments = (float) Payment::where('payable_id', $customerId)
            ->where('payable_type', Customer::class)
            ->where('date', '<', $startDate)
            ->sum('amount');

        return $sales - $returns - $payments;
    }

    private function salesQuery(int $customerId, ?string $startDate, ?string $endDate)
    {
        $query = Sell::where('customer_id', $customerId)
            ->select('id', 'invoice_id', 'customer_id', 'total_price', 'paid_amount', 'date');

        return $this->applyDateFilter($query, $startDate, $endDate);
    }

    private function returnsQuery(int $customerId, ?string $startDate, ?string $endDate)
    {
        $query = SellsReturn::where('customer_id', $customerId)
            ->select('id', 'invoice_id', 'customer_id', 'total_amount', 'date');

        return $this->applyDateFilter($query, $startDate, $endDate);
    }

    private function paymentsQuery(int $customerId, ?string $startDate, ?string $endDate)
    {
        $query = Payment::where('payable_id', $customerId)
            ->where('payable_type', Customer::class)
            ->select('id', 'accounts_id', 'amount', 'date', 'payment_for');

        return $this->applyDateFilter($query, $startDate, $endDate);
    }

    private function applyDateFilter($query, ?string $startDate, ?string $endDate)
    {
        if ($startDate !== null && $endDate !== null) {
            return $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($startDate !== null) {
            return $query->where('date', '>=', $startDate);
        }

        if ($endDate !== null) {
            return $query->where('date', '<=', $endDate);
        }

        return $query;
    }
}
