<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellsReturn;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellsReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $sellsReturn = SellsReturn::groupBy('customer_id')->orderBy('id', 'DESC')->get();
        $sellsReturn = SellsReturn::select('id', 'customer_id', 'invoice_id', 'date', DB::raw('SUM(total_amount) AS sum_of_total'),)
            ->groupBy('customer_id')
            ->orderBy('id', 'DESC')
            ->get();
        // dd($sellsReturn);
        return view('sellReturn/sellReturnlist', compact('sellsReturn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $invoice_id = Sell::groupBy('invoice_id')->orderBy('invoice_id', 'DESC')->get();
        // dd($invoice_id);
        $customer = Customer::all();
        $product = Product::all();
        return view('sellReturn/sellReturnAdd', compact('customer', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'date' => 'required',
            'total_price' => 'required',
            'paid_amount' => 'required',
            'product_id' => 'required',
            'sub_total' => 'required',
            'discount_price' => 'required',
            'invoice_number' => 'required',

        ]);
        $invoice_number = $request->invoice_number;
        $customer_id = $request->customer_id;
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $sell_price = $request->sell_price;
        $row_total_price = $request->row_total_price;
        $date = $request->date;
        $total_price = $request->total_price;
        // dd($row_total_price);
        foreach ($product_id as $k => $p) {
            $d = [
                'product_id' => $p, 'customer_id' => $customer_id, 'quantity' => $quantity[$k], 'sell_price' => $sell_price[$k], 'date' => $date, 'invoice_id' => $invoice_number, 'total_amount' => $row_total_price[$k]
            ];
            $data[] = $d;
        }
        SellsReturn::insert($data);
        return redirect()->route('sellsReturn.index')->with('success', 'Sell Return added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($sellsReturn)
    {
        $sellReturnData = SellsReturn::where('customer_id', $sellsReturn)->with('customer')->with('product')->get();
        return view('sellReturn/sellReturnShow', compact('sellReturnData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sellsReturn)
    {
        // dd($sellsReturn);
        $sellReturnData = SellsReturn::findOrFail($sellsReturn);

        return view('sellReturn/sellReturnEdit', compact('sellReturnData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sellsReturn)
    {
        $request->validate([

            'customer_name' => "required",
            'date' => "required",
            'product_name' => "required",
            'quantity' => "required",
            'sell_price' => "required",
            'sell_total_price' => "required",

        ]);
        $sellReturnData = SellsReturn::findOrFail($sellsReturn);
        $qty = $request->quantity;
        $quantity = (int) $qty[0];
        $sell_price = $request->sell_price;
        $sell_total_price = $request->sell_total_price;

        $sellReturnData->update(['quantity' => $quantity, 'sell_price' => $sell_price, 'total_amount' => $sell_total_price]);
        return redirect()->route('sellsReturn.index')->with('update', 'Sell Return updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SellsReturn $sellsReturn)
    {
        $invoice_id = $sellsReturn->invoice_id;
        
        SellsReturn::whereIn('invoice_id', [$invoice_id])->delete();
        return redirect()->route('sellsReturn.index')->with('delete', 'Sell Return deleted successfully');
    }
    /**
     * custom show function.
     */
    public function sellsReturnData($invoice_number)
    {
        $sell = Sell::where('invoice_id', $invoice_number)->with('customer')->with('product')->get();
        // dd($sell);
        return response()->json($sell);
    }
}
