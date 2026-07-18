<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $purchaseReturn = PurchaseReturn::groupBy('supplier_id')->orderBy('id', 'DESC')->get();

        $purchaseReturn = PurchaseReturn::select('id', 'supplier_id', 'invoice_id', 'date', DB::raw('SUM(total_amount) AS sum_of_total'),)
            ->groupBy('supplier_id')
            ->orderBy('id', 'DESC')
            ->get();

        // dd($purchaseReturn);
        return view('purchaseReturn.purReturnList', compact('purchaseReturn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplier = Supplier::all();
        $product = Product::all();
        return view('purchaseReturn.purReturnAdd', compact('supplier', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'invoice_number' => 'required',
            'date' => 'required',
            'sub_total' => 'required',
            'paid_amount' => 'required',
            'total_price' => 'required',
        ]);
        $invoice_id = $request->invoice_number;
        $supplier_id = $request->supplier_id;
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $purchase_price = $request->purchase_price;
        $row_total_price = $request->row_total_price;
        $date = $request->date;
        // dd($purchase_price);
        foreach ($product_id as $k => $p) {
            $d = [
                'product_id' => $p, 'invoice_id' => $invoice_id, 'supplier_id' => $supplier_id, 'quantity' => $quantity[$k], 'purchase_price' => $purchase_price[$k], 'total_amount' => $row_total_price[$k], 'date' => $date
            ];
            $data[] = $d;
        }
        PurchaseReturn::insert($data);
        return redirect()->route('purchaseReturn.index')->with('success', 'Purchase Return added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($purchaseReturn)
    {
        $purchaseReturnData = PurchaseReturn::where('supplier_id', $purchaseReturn)->with('supplier')->with('product')->get();
        // dd($purchaseReturnData);

        return view('purchaseReturn.purReturnShow', compact('purchaseReturnData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($purchaseReturn)
    {
        $purchaseReturnData = PurchaseReturn::findOrFail($purchaseReturn);

        return view('purchaseReturn.purReturnEdit', compact('purchaseReturnData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $purchaseReturn)
    {
        // dd($purchaseReturn);
        $request->validate([

            'supplier_name' => "required",
            'date' => "required",
            'product_name' => "required",
            'quantity' => "required",
            'purchase_price' => "required",
            'purchase_total_price' => "required",
        ]);
        $purchaseReturnData = PurchaseReturn::findOrFail($purchaseReturn);
        $qty = $request->quantity;
        $quantity = (int) $qty[0];
        $purchase_price = $request->purchase_price;
        $purchase_total_price = $request->purchase_total_price;

        $purchaseReturnData->update(['quantity' => $quantity, 'purchase_price' => $purchase_price, 'total_amount' => $purchase_total_price]);
        return redirect()->route('purchaseReturn.index')->with('update', 'Purchase Return updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseReturn $purchaseReturn)
    {
        $invoice_id = $purchaseReturn->invoice_id;
        PurchaseReturn::whereIn('invoice_id', [$invoice_id])->delete();
        return redirect()->route('purchaseReturn.index')->with('delete', 'Purchase Return deleted successfully');
    }
    /**
     * api call.
     */
    public function purchaseReturnData($invoice_number)
    {

        $purchase = Purchase::where('invoice_id', $invoice_number)->with('supplier')->with('product')->get();
        // dd($sell);
        return response()->json($purchase);
    }
}
