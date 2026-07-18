<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::orderBy('created_at', 'DESC')->get();
        return view('expense.expCatList', compact('expenseCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense.expCatAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_category' => 'required'
        ]);
        $expense_category = $request->expense_category;
        ExpenseCategory::create(['expense_category' => $expense_category]);
        return redirect()->route('expenseCategory.index')->with('success', 'Expense Category added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense.expCatEdit', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->update($request->all());
        return redirect()->route('expenseCategory.index')->with('update', 'Expense Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // dd($expenseCategory);
        $expenseCategory->delete();
        return redirect()->route('expenseCategory.index')->with('delete', 'Expense Category delete successfully');
    }
}
