<?php

use App\Models\Loan;
use App\Models\Sell;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Accounts;
use App\Models\SellsReturn;
use Illuminate\Support\Facades\DB;

if (!function_exists('sumTwoNumbers')) {
    function sumTwoNumbers($a, $b)
    {
        return $a + $b;
    }



    function getOpeningBalance()
    {
        $today = date('Y-m-d');

        //opening balance calculation
        $opening_total_sell_price = Sell::where('date', "<", $today)->groupBy('invoice_id')->pluck('total_price');
        $opening_total_discount_price = Sell::where('date', "<", $today)->groupBy('invoice_id')->pluck('total_discount');
        $opening_total_paid_amount = Sell::where('date', "<", $today)->groupBy('invoice_id')->pluck('paid_amount');
        $opening_total_sell_product = Sell::where('date', "<", $today)->with('product')->get();
        $opening_total_sell_return = SellsReturn::where('date', "<",  $today)->with('product')->get();
        $opening_total_expenses = Expense::where('date', "<", $today)->sum('amount');
        $opening_total_due_collection = Payment::where('date', "<", $today)->where('payment_for', 'cus_due_return')->sum('amount');
        $opening_total_supplier_payment = Payment::where('date', "<", $today)->where('payable_type', 'App\Models\Supplier')->sum('amount');

        //sum_of_day_sell price
        $opening_sum_of_sell = 0;
        foreach ($opening_total_sell_price as $total_sell) {
            // dd($day_sell);
            $opening_sum_of_sell += $total_sell;
        }

        //total_paid price
        $opening_sum_paid_amount = 0;
        foreach ($opening_total_paid_amount as $opening_paid) {
            $opening_sum_paid_amount += $opening_paid;
        }
        //return price of product
        $opening_sum_return_sell_price = 0;
        $opening_sum_return_pur_price = 0;
        foreach ($opening_total_sell_return as $ss) {
            $opening_qty = $ss->quantity;
            $opening_sell_price = $ss->sell_price;
            $opening_purchase_price = $ss->product->price;

            $opening_sum_return_sell_price += $opening_qty * $opening_sell_price;
            $opening_sum_return_pur_price += $opening_qty * $opening_purchase_price;
        }

        //opening_balance
        $opening_sell_amount = ($opening_sum_of_sell - $opening_sum_return_sell_price);
        $opening_paid_amount = ($opening_sum_paid_amount - $opening_sum_return_sell_price);
        $opening_due_amount = ($opening_sell_amount - $opening_paid_amount);

        $opening_balance = ($opening_sell_amount + $opening_total_due_collection) - ($opening_total_expenses + $opening_due_amount);
        // dd($opening_balance);

        return $opening_balance;
    }


    function getOpeningBalanceFilter($dateFrom, $previousDate)
    {
        //opening balance calculation
        $opening_total_sell_price = Sell::whereBetween('date', [$dateFrom, $previousDate])->groupBy('invoice_id')->pluck('total_price');
        $opening_total_paid_amount = Sell::whereBetween('date', [$dateFrom, $previousDate])->groupBy('invoice_id')->pluck('paid_amount');
        $opening_total_sell_return = SellsReturn::whereBetween('date', [$dateFrom, $previousDate])->with('product')->get();
        $opening_total_expenses = Expense::whereBetween('date', [$dateFrom, $previousDate])->sum('amount');
        $opening_total_due_collection = Payment::whereBetween('date', [$dateFrom, $previousDate])->where('payment_for', 'cus_due_return')->sum('amount');
        $opening_total_supplier_payment = Payment::whereBetween('date', [$dateFrom, $previousDate])->where('payable_type', 'App\Models\Supplier')->sum('amount');

        //sum_of_day_sell price
        $opening_sum_of_sell = 0;
        foreach ($opening_total_sell_price as $total_sell) {
            $opening_sum_of_sell += $total_sell;
        }

        //total_paid price
        $opening_sum_paid_amount = 0;
        foreach ($opening_total_paid_amount as $opening_paid) {
            $opening_sum_paid_amount += $opening_paid;
        }
        //return price of product
        $opening_sum_return_sell_price = 0;
        $opening_sum_return_pur_price = 0;
        foreach ($opening_total_sell_return as $ss) {
            $opening_qty = $ss->quantity;
            $opening_sell_price = $ss->sell_price;
            $opening_purchase_price = $ss->product->price;

            $opening_sum_return_sell_price += $opening_qty * $opening_sell_price;
            $opening_sum_return_pur_price += $opening_qty * $opening_purchase_price;
        }

        //opening_balance
        $opening_sell_amount = ($opening_sum_of_sell - $opening_sum_return_sell_price);
        $opening_paid_amount = ($opening_sum_paid_amount - $opening_sum_return_sell_price);
        $opening_due_amount = ($opening_sell_amount - $opening_paid_amount);

        $opening_balance = ($opening_sell_amount + $opening_total_due_collection) - ($opening_total_supplier_payment + $opening_total_expenses + $opening_due_amount);

        return $opening_balance;
    }


    function totalBalance()
    {
        $total_sell_price = Sell::groupBy('invoice_id')->pluck('total_price');
        $total_paid_amount = Sell::groupBy('invoice_id')->pluck('paid_amount');

        $sum_of_sell = 0;
        foreach ($total_sell_price as $total_sell) {
            $sum_of_sell += $total_sell;
        }

        //total_paid price
        $sum_paid_amount = 0;
        foreach ($total_paid_amount as $opening_paid) {
            $sum_paid_amount += $opening_paid;
        }

        $total_due_collection = Payment::where('payment_for', 'cus_due_return')->sum('amount');
        $total_supplier_payment = Payment::where('payable_type', 'App\Models\Supplier')->sum('amount');

        $total_expenses = Expense::sum('amount');

        $get_loan = Loan::with(['loanPayment' => function ($query) {
            $query->select('loan_id', DB::raw('SUM(paid_amount) as total_paid_amount'))
                ->groupBy('loan_id');
        }])->get();

        $loan_in_hand = 0;

        foreach ($get_loan as $d) {
            $paid_amount = $d->amount;
            $total_paid_amount = isset($d->loanPayment[0]) ? $d->loanPayment[0]->total_paid_amount : 0;
            $loan_in_hand = $paid_amount - $total_paid_amount;
        }

        $dr_amount = $sum_paid_amount + $total_due_collection + $loan_in_hand;

        $cr_amount = $total_supplier_payment + $total_expenses;

        $total_cash = $dr_amount - $cr_amount;

        return $total_cash;
    }



    function getAccountsBalance()
    {
        // Retrieve Debit Data (Customer)
        $debit = App\Models\Payment::where('payable_type', 'App\Models\Customer')
            ->select('accounts_id', DB::raw('SUM(amount) as total_debit'))
            ->groupBy('accounts_id')
            ->get()
            ->keyBy('accounts_id');

        // Retrieve Credit Data (Supplier)
        $credit = App\Models\Payment::where('payable_type', 'App\Models\Supplier')
            ->select('accounts_id', DB::raw('SUM(amount) as total_credit'))
            ->groupBy('accounts_id')
            ->get()
            ->keyBy('accounts_id');

        // Combine Debit and Credit and calculate net amount
        $netAmount = [];

        // Loop through debit data
        foreach ($debit as $accountId => $debitData) {
            $totalDebit = $debitData->total_debit;
            $totalCredit = isset($credit[$accountId]) ? $credit[$accountId]->total_credit : 0; // If no credit, use 0

            // Calculate net (debit - credit) and store account_id as key
            $netAmount[$accountId] = [
                'account_id' => $accountId,
                'net_amount' => $totalDebit - $totalCredit
            ];
        }

        // Handle accounts that only have credit entries
        foreach ($credit as $accountId => $creditData) {
            if (!isset($netAmount[$accountId])) {
                $netAmount[$accountId] = [
                    'account_id' => $accountId,
                    'net_amount' => 0 - $creditData->total_credit // If no debit, net is negative credit
                ];
            }
        }

        // dd($netAmount);
        return $netAmount;
    }
}
