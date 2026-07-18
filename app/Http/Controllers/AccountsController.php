<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountsData = Accounts::all();
        return view('accounts.accountsList', compact('accountsData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.accountsAdd');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'bank_name' => 'required',
            'branch_name' => 'nullable',
            'account_number' => 'nullable',
            'date' => 'required',
            'description' => 'nullable',
            'status' => 'required',
        ]);

        $bank_name = $request->bank_name;
        $branch_name = $request->branch_name;
        $account_number = $request->account_number;
        $date = $request->date;
        $description = $request->description;
        Accounts::create(['bank_name' => $bank_name, 'branch_name' => $branch_name, 'account_number' => $account_number, 'date' => $date, 'description' => $description, 'status' => $request->status]);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Accounts $accounts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accounts $account)
    {
        return view('accounts.accountsEdit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accounts $account)
    {

        $accounts = Accounts::find($account->id);
        $request->validate([
            'bank_name' => 'required',
            'branch_name' => 'nullable',
            'account_number' => 'nullable',
            'date' => 'required',
            'description' => 'nullable',
            'status' => 'required',
        ]);

        $bank_name = $request->bank_name;
        $branch_name = $request->branch_name;
        $account_number = $request->account_number;
        $date = $request->date;
        $description = $request->description;
        $status = $request->status;
        $accounts->update(['bank_name' => $bank_name, 'branch_name' => $branch_name, 'account_number' => $account_number, 'date' => $date, 'description' => $description, 'status' => $status]);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accounts $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully');
    }

    #make a function for balance adjustment 
    public function balanceAdjustmentView(Request $request)
    {
        $accounts = Accounts::all();
        return view('accounts.balanceAdjustment', compact('accounts'));


        // $accounts = Accounts::findOrFail($request->accounts_id);
        // $accounts->update(['amount' => $request->amount]);
        // return redirect()->route('accounts.index')->with('success', 'Balance adjusted successfully');
    }

    public function balanceAdjustment(Request $request)
    {


        $request->validate([
            'accounts_id' => 'required',
            'type' => 'required',
            'Balance' => 'required',
            'adjustAmount' => 'required',
            'status' => 'required',
            'date' => 'required',
        ]);
        // dd($request->all());
        $accounts = Accounts::findOrFail($request->accounts_id);


        if ($request->type == '1') {
            $accounts->update(['amount' => $accounts->amount + $request->adjustAmount]);
        } else {
            $accounts->update(['amount' => $accounts->amount - $request->adjustAmount]);
        }
        return redirect()->route('accounts.index')->with('success', 'Balance adjusted successfully');
    }
}
