<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::all();
        return view('company/companyList', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company/companyAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required'
        ]);
        $company = $request->company;
        Company::create(['company_name' => $company]);
        return redirect()->route('company.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('company/companyEdit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'company' => 'required'
        ]);
        // dd($request->all());
        $updateCompany = $request->company;
        $company->update(['company_name' => $updateCompany]);
        return redirect()->route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('company.index');
    }
}
