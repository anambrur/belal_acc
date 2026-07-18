<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sell;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Dashboard;
use App\Models\SellsReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with(['purchase' => function ($q) {
            $q->groupBy('invoice_id');
        }])->with('purchaseReturn')
            ->with('payment')
            ->orderBy('created_at', 'DESC')
            ->get();
        $purchase = [];
        foreach ($suppliers as $c) {
            // dd($c);
            $d = ['supplier' => ['id' => $c->id, 'supplier_name' => $c->supplier_name, 'phone' => $c->phone, 'email' => $c->email, 'address' => $c->address, 'photo' => $c->photo]];
            $d['purchase'] = 0;
            foreach ($c->purchase as $s) {
                $d['purchase'] += $s->total_price;
            }
            $d['return'] = 0;
            foreach ($c->purchaseReturn as $s) {
                $d['return'] += $s->total_amount;
            }
            $d['payment'] = 0;
            foreach ($c->payment as $s) {
                $d['payment'] += $s->amount;
            }
            // Calculate total purchase due
            $d['purchase_due'] = $d['purchase'] - ($d['payment'] + $d['return']);
            $purchase[] = $d;
        }
        ////////////////////////////////

        $customers = Customer::with(['sell' => function ($q) {
            $q->groupBy('invoice_id');
        }])->with('sellReturn')
            ->with('payment')
            ->orderBy('created_at', 'DESC')
            ->get();
        $sell_data = [];
        foreach ($customers as $c) {
            // dd($c);
            $d = ['customer' => ['id' => $c->id, 'customer_name' => $c->customer_name, 'phone' => $c->phone, 'email' => $c->email, 'address' => $c->address, 'photo' => $c->photo]];
            $d['sell'] = 0;
            foreach ($c->sell as $s) {
                $d['sell'] += $s->total_price;
            }
            $d['return'] = 0;
            foreach ($c->sellReturn as $s) {
                $d['return'] += $s->total_amount;
            }
            $d['payment'] = 0;
            foreach ($c->payment as $s) {
                $d['payment'] += $s->amount;
            }

            $d['sell_due'] = $d['sell'] - ($d['payment'] + $d['return']);
            $sell_data[] = $d;
        }
        $customerCount = Customer::count();
        $supplierCount = Supplier::count();
        $products = Product::orderBy('id', 'desc')->take(4)->get();

        return view('dashboard', compact('purchase', 'sell_data', 'customerCount', 'supplierCount', 'products'));
        // $chartData = 12;
        // return response()->json($chartData);
    }


    public function getSalesData()
    {
        $currentYear = Carbon::now()->year;
        $sellData = Sell::select(DB::raw('SUM(total_price) as total_price'), DB::raw('MONTH(date) as month'))
            ->whereYear('date', $currentYear)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        $purchaseData = Purchase::select(DB::raw('SUM(total_price) as total_price'), DB::raw('MONTH(date) as month'))
            ->whereYear('date', $currentYear)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        $sales = array_fill(0, 12, 0);
        $purchases = array_fill(0, 12, 0);
        foreach ($sellData as $data) {
            $sales[$data->month - 1] = $data->total_price;
        }
        foreach ($purchaseData as $data) {
            $purchases[$data->month - 1] = $data->total_price;
        }
        $categories = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        return response()->json([
            'sales' => $sales,
            'purchase' => $purchases,
            'categories' => $categories,
        ]);
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
