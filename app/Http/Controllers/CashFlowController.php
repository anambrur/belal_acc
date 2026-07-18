<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Expense;
use App\Models\LoanPayment;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $sales = Sell::select('id', 'date', 'total_price', 'paid_amount', DB::raw("'Sale' as type"))
            ->get();

        $purchases = Purchase::select('id', 'date', 'total_price', 'paid_amount', DB::raw("'Purchase' as type"))
            ->get();

        $loans = Loan::select('id', 'date', 'amount', DB::raw("'Loan' as type"))
            ->get();

        $loanReturns = LoanPayment::select('id', 'paid_date as date', 'paid_amount', DB::raw("'Loan Return' as type"))
            ->get();

        $expenses = Expense::select('id', 'date', 'amount', DB::raw("'Expense' as type"))
            ->get();

        $payments = Payment::select('id', 'date', 'amount', 'payment_for', DB::raw("'Payment' as type"))
            ->get();

        // Merge all data into one collection
        $transactions = $sales->merge($purchases)
            ->merge($loans)
            ->merge($loanReturns)
            ->merge($expenses)
            ->merge($payments)
            ->sortBy('date');

        // dd($transactions);
        return view('cash-flow.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
