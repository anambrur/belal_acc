<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('batch.batchList');
    }



    public function getBatchList()
    {
        $data = Batch::all();
        $sl = 0;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex align-center ">
                            </a> <a href="' . route('batch.edit', $row->id) . '">
                                <span><i class="fa-solid fa-pen "></i></span>
                            </a>
                            
                            <form action="' . route('batch.destroy', $row->id) . '" method="post">
                            <input type="hidden" name ="_token" value="' . csrf_token() . '"/>
                            <input type="hidden" name ="_method" value="delete"/>
                            <button onclick="return confirm(\'Are you sure you want to delete?\')" class="btn"><i class="fas fa-trash"
                            style="color: #FF0000;"></i></button>
                            </form>
                        </div>';

                return $btn;
            })

            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })
            ->rawColumns(['action',])
            ->make(true);


        // return view('road.roadList', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('batch.batchAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'batch_name' => 'required',
        ]);
        Batch::create([
            'batch_name' => $request->batch_name,
        ]);

        return redirect()->route('batch.index')
            ->with('success', 'Batch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch)
    {
        return view('batch.batchEdit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'batch_name' => 'required',
        ]);

        $batch->update([
            'batch_name' => $request->batch_name,
        ]);

        return redirect()->route('batch.index')
            ->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect()->route('batch.index')
            ->with('success', 'Batch deleted successfully.');
    }
}
