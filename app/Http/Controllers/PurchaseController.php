<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Accounts;
use App\Models\Category;
use App\Models\Location;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::all();
        $address = Location::all();
        return view('purchase.purchase_list', compact('supplier', 'address'));
    }

    /**
     * yajra data table
     */
    public function getPurchaseDataList(Request $request)
    {
        $query = Purchase::groupBy('invoice_id')->with('supplier', 'supplier.location');

        // Apply Supplier Filter
        if ($request->has('filter_supplier') && $request->filter_supplier != 'All Supplier') {
            $query->where('supplier_id', $request->filter_supplier);
        }

        // Apply Address Filter
        if ($request->has('filter_address') && $request->filter_address != 'All Address') {
            $query->where('location_id', $request->filter_address);
        }

        // Apply Date Range Filter
        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }

        // Fetch Data
        $data = $query->orderBy('created_at', 'DESC')->get();
        // dd($data);

        $sl = 1;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actions =
                    '<div class="d-flex align-center ">
                    
                    <a href="' . route('purchase.edit', $row->id) . '">
                        <span><i class="fa-solid fa-pen "></i></span>
                    </a>
                    <form action="' . route('purchase.destroy', $row->id) . '" method="post">
                        <input type="hidden" name ="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name ="_method" value="delete"/>
                        <button onclick="return confirm(\'Are you sure you want to delete?\')" class="btn">
                            <i class="fas fa-trash" style="color: #FF0000;"></i>
                        </button>
                    </form>
                </div>';
                return $actions;
            })
            ->addColumn('due', function ($row) {
                return $row->total_price - $row->paid_amount;
            })
            ->addColumn('status', function ($row) {
                $statusText = ($row->total_price - $row->paid_amount > 0) ? 'Unpaid' : 'Paid';
                $textColor = ($row->total_price - $row->paid_amount > 0) ? 'text-danger' : 'text-success';

                return '<span class="px-2 py-1 border rounded ' . $textColor . '">' . $statusText . '</span>';
            })
            ->addColumn('sl', function () use (&$sl) {
                return $sl++;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::orderBy('created_at', 'DESC')->get();
        $unit = Unit::orderBy('created_at', 'DESC')->get();
        $supplier = Supplier::with('location')->get();
        // dd($supplier); 
        $product = Product::all();
        $paymentMethod = PaymentMethod::all();
        $accounts = Accounts::all();
        return view('purchase.purchase_add', compact('category', 'unit', 'supplier', 'product', 'paymentMethod', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'supplier_id' => 'required',
                'date' => 'required',
                'sub_total' => 'required',
                'paid_amount' => 'required',
                'total_price' => 'required',
                'accounts_id' => 'required',
                'discount_price' => 'required',

            ]);
            $pre_invoice_id = Purchase::max('invoice_id');
            if ($pre_invoice_id == '') {
                $pre_invoice_id = "P-1000";
            } else {
                $number_part = (int) substr($pre_invoice_id, 2);
                $pre_invoice_id = "P-" . ($number_part + 1);
            }
            $location = Supplier::where('id', $request->supplier_id)->get();
            $location_id = $location[0]->location_id;
            // dd($location_id);

            $supplier_id = $request->input('supplier_id');
            $date = $request->input('date');
            $total_price = $request->input('total_price');
            $discount_price = $request->input('discount_price');
            $paid_price = $request->input('paid_amount');

            $invoice_id = $pre_invoice_id;

            Purchase::create([
                'invoice_id' => $invoice_id,
                'supplier_id' => $supplier_id,
                'date' => $date,
                'total_price' => $total_price,
                'total_discount' => $discount_price,
                'paid_amount' => $paid_price,
                'location_id' => $location_id
            ]);

            $supplierData = Supplier::findOrFail($supplier_id);

            $supplierData->payment()->create(['accounts_id' => $request->accounts_id, 'amount' => $paid_price, 'date' => $date]);

            // Update cash in and accounts
            $accounts = Accounts::findOrFail($request->accounts_id);
            $accounts->update(['amount' => $accounts->amount - $request->paid_amount]);

            DB::commit();
            return redirect()->route('purchase.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        // dd($purchase);
        $invoice_id = $purchase->invoice_id;
        $supplier = Supplier::orderBy('created_at', 'DESC')->get();
        $Pur_details = Purchase::where('invoice_id', $invoice_id)->get();
        return view('purchase.purchase_show', compact('Pur_details', 'supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $invoice_id = $purchase->invoice_id;
        $product = Product::all();
        $Pur_details = Purchase::where('invoice_id', $invoice_id)->get();
        $supplier = Supplier::orderBy('created_at', 'DESC')->get();
        $accounts = Accounts::all();
        return view('purchase.purchase_edit', compact('Pur_details', 'product', 'supplier', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $purchase)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'supplier_id' => 'required',
                'date' => 'required',
                'sub_total' => 'required',
                'paid_amount' => 'required',
                'total_price' => 'required',
                'accounts_id' => 'required',
                'discount_price' => 'required',
            ]);

            // Get the invoice ID from the route parameter
            $invoice_id = $purchase;

            // Find the existing purchase records
            $existingPurchases = Purchase::where('invoice_id', $invoice_id)->get();

            if ($existingPurchases->isEmpty()) {
                throw new ModelNotFoundException('Purchase not found');
            }

            // Get the first purchase to get old values
            $firstPurchase = $existingPurchases->first();

            // Store old values for account adjustment
            $oldSupplierId = $firstPurchase->supplier_id;
            $oldPaidAmount = $firstPurchase->paid_amount;
            $oldDate = $firstPurchase->date;

            // Get location from supplier
            $location = Supplier::where('id', $request->supplier_id)->first();
            $location_id = $location->location_id;

            // Update common fields for all purchase records with this invoice_id
            Purchase::where('invoice_id', $invoice_id)->update([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_price' => $request->total_price,
                'total_discount' => $request->discount_price,
                'paid_amount' => $request->paid_amount,
                'location_id' => $location_id
            ]);

            // Handle payment updates - find the existing payment for this purchase
            $supplierData = Supplier::findOrFail($request->supplier_id);

            // Try to find the payment record by matching multiple criteria
            $payment = $supplierData->payment()
                ->where('amount', $oldPaidAmount)
                ->where('date', $oldDate)
                ->first();

            if ($payment) {
                // Revert the old payment from the account
                $oldAccount = Accounts::find($payment->accounts_id);
                if ($oldAccount) {
                    $oldAccount->update(['amount' => $oldAccount->amount + $oldPaidAmount]);
                }

                // Update the payment record
                $payment->update([
                    'accounts_id' => $request->accounts_id,
                    'amount' => $request->paid_amount,
                    'date' => $request->date
                ]);
            } else {
                // If no existing payment found, create a new one
                $supplierData->payment()->create([
                    'accounts_id' => $request->accounts_id,
                    'amount' => $request->paid_amount,
                    'date' => $request->date
                ]);
            }

            // Update the account with new payment
            $newAccount = Accounts::findOrFail($request->accounts_id);
            $newAccount->update(['amount' => $newAccount->amount - $request->paid_amount]);

            DB::commit();

            return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Purchase not found');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the purchase: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.index');
    }
    /**
     * Rget product data call api with barcode.
     */
    public function getProductDataBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();
        // dd($product);
        return response()->json($product);
    }
}
