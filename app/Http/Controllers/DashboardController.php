<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $summary = $this->dashboardService->summary();

        return view('dashboard', compact('summary'));
    }

    public function getSalesData(Request $request)
    {
        $year = (int) $request->input('year', Carbon::now()->year);
        if ($year < 2000 || $year > 2100) {
            $year = (int) Carbon::now()->year;
        }

        return response()->json($this->dashboardService->salesPurchaseChart($year));
    }

    public function collectionsChart()
    {
        return response()->json($this->dashboardService->collectionsChart());
    }

    public function paymentStatusChart()
    {
        return response()->json($this->dashboardService->paymentStatusChart());
    }

    public function expenseTrendChart()
    {
        return response()->json($this->dashboardService->expenseTrendChart());
    }

    public function roadSalesChart()
    {
        return response()->json($this->dashboardService->roadSalesChart());
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
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
