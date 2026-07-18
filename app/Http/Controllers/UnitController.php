<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = Unit::orderBy('created_at', 'DESC')->get();
        return view('unit/unit_list', compact('unit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unit/unit_add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Unit::create($request->all());
        return redirect()->route('unit.index')->with('success', 'Unit added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('unit/unit_edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $unitupdated = $unit;
        $unitupdated->update($request->all());
        return redirect()->route('unit.index')->with('updated', 'Unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('unit.index');
    }
}
