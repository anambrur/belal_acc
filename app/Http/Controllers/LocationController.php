<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('locations.locationList');
    }

    public function getLocationList()
    {
        $data = Location::all();
        $sl = 0;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex align-center ">
                            </a> <a href="' . route('location.edit', $row->id) . '">
                                <span><i class="fa-solid fa-pen "></i></span>
                            </a>
                            
                            <form action="' . route('location.destroy', $row->id) . '" method="post">
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


        return view('locations.locationList', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.locationAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
        ]);

        Location::create($request->all());

        return redirect()->route('location.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('locations.locationEdit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'address' => 'required',
        ]);
        $location->update($request->all());
        return redirect()->route('location.index')
            ->with('success', 'Location Update successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('location.index')
            ->with('delete', 'Location deleted successfully.');
    }
}
