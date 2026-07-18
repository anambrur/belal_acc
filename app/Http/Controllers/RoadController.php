<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Road;
use Illuminate\Http\Request;

class RoadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('road.roadList');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('road.roadAdd');
    }

    public function getRoadList()
    {
        $data = Road::all();
        $sl = 0;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex align-center ">
                            </a> <a href="' . route('road.edit', $row->id) . '">
                                <span><i class="fa-solid fa-pen "></i></span>
                            </a>
                            
                            <form action="' . route('road.destroy', $row->id) . '" method="post">
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'road' => 'required',
        ]);

        Road::create([
            'name' => $request->road,
        ]);

        return redirect()->route('road.index')
            ->with('success', 'Road created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Road $road)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Road $road)
    {
        return view('road.roadEdit', compact('road'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Road $road)
    {
        $request->validate([
            'road' => 'required',
        ]);

        $road->update([
            'name' => $request->road,
        ]);

        return redirect()->route('road.index')
            ->with('success', 'Road updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Road $road)
    {
        $road->delete();

        return redirect()->route('road.index')
            ->with('success', 'Road deleted successfully.'); 
    }
}
