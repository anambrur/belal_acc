<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Accounts;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::all();
        return view('expense.expenseList',compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expense_categories = ExpenseCategory::orderBy('created_at', 'DESC')->get();
        $accounts = Accounts::all();
        return view('expense.expenseAdd', compact('expense_categories', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_categories' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'accounts_id' => 'required'
        ]);
        $expense_categories = $request->expense_categories;
        $date = $request->date;
        $amount = $request->amount;
        $description = $request->description;
        Expense::create(['expense_category_id' => $expense_categories, 'date' => $date, 'amount' => $amount, 'description' => $description]);

        $accounts = Accounts::findOrFail($request->accounts_id);
        $accounts->update(['amount' => $accounts->amount - $request->amount]);
        return redirect()->route('expense.index')->with('success', 'Expense added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $expense_categories = ExpenseCategory::orderBy('created_at', 'DESC')->get();
        
        return view('expense.expenseEdit',compact('expense','expense_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_categories' => 'required',
            'date' => 'required',
            'amount' => 'required'
        ]);
        
        $expense->update($request->all());
        return redirect()->route('expense.index')->with('update', 'Expense Category updated successfully');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        
        $expense->delete();
        return redirect()->route('expense.index')->with('delete', 'Expense Category delete successfully');
    }
}
