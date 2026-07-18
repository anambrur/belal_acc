<?php

namespace App\Http\Controllers;


use App\Models\Loan;
use App\Models\Accounts;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['loanPayment' => function ($query) {
            $query->select('loan_id', DB::raw('SUM(paid_amount) as total_paid_amount'))
                ->groupBy('loan_id');
        }])->get();
        return view('loan.loan_list', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Accounts::all();
        return view('loan.loan_add', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'loan_holder' => 'required',
            'amount' => 'required',
            'date' => 'required'
        ]);
        $loan_holder = $request->loan_holder;
        $amount = $request->amount;
        $date = $request->date;
        Loan::create(['loan_holder' => $loan_holder, 'amount' => $amount, 'date' => $date]);

        $accounts = Accounts::findOrFail($request->accounts_id);
        $accounts->update(['amount' => $accounts->amount + $request->amount]);

        return redirect()->route('loan.index')->with('success', 'Loan added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        // dd($loan);
        return view('loan/loan_edit', compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $loanUpdated = $loan;
        $loanUpdated->update($request->all());
        return redirect()->route('loan.index')->with('success', 'Loan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loan.index');
    }

    //loan payment
    public function loanPayment($id)
    {
        $loan = Loan::findOrFail($id)->with('loanPayment')->first();
        // dd($loan);
        // dd($loan->loanPayment->sum('paid_amount'));
        $accounts = Accounts::all();
        return view('loan.loan_payment', compact('loan' , 'accounts'));
    }

    //make loan payment
    public function makeLoanPayment(Request $request)
    {
        // dd($request->all());
        $loan_holder_id = $request->loan_id;
        $paid_amount = $request->paid_amount;
        $paid_date = $request->date;
        // dd($paid_amount);
        LoanPayment::create(['loan_id' => $loan_holder_id, 'paid_amount' => $paid_amount, 'paid_date' => $paid_date]);

        $accounts = Accounts::findOrFail($request->accounts_id);
        $accounts->update(['amount' => $accounts->amount - $request->paid_amount]);
        return redirect()->route('loan.index')->with('success', 'Loan Payment successfully');
    }
}
