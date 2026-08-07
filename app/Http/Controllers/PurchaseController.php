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
     * Yajra data table — server-side query with joins (fast).
     */
    public function getPurchaseDataList(Request $request)
    {
        $query = DB::table('purchases')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->leftJoin('locations', 'locations.id', '=', 'suppliers.location_id')
            ->select([
                'purchases.id',
                'purchases.invoice_id',
                'purchases.supplier_id',
                'purchases.total_price',
                'purchases.paid_amount',
                'purchases.total_discount',
                'purchases.date',
                'purchases.location_id',
                'purchases.created_at',
                'suppliers.supplier_name',
                'locations.address as location_address',
                DB::raw('(purchases.total_price - purchases.paid_amount) as due_amount'),
            ]);

        if ($request->filled('filter_supplier') && $request->filter_supplier !== 'All Supplier') {
            $query->where('purchases.supplier_id', $request->filter_supplier);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('purchases.location_id', $request->filter_address);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('purchases.date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        $statsQuery = clone $query;
        $statsQuery->columns = null;
        $stats = $statsQuery
            ->selectRaw('
                COUNT(*) as invoices,
                COALESCE(SUM(purchases.total_price), 0) as total,
                COALESCE(SUM(purchases.paid_amount), 0) as paid,
                COALESCE(SUM(purchases.total_price - purchases.paid_amount), 0) as due
            ')
            ->first();

        return DataTables::of($query)
            ->with([
                'stats' => [
                    'invoices' => (int) ($stats->invoices ?? 0),
                    'total' => (float) ($stats->total ?? 0),
                    'paid' => (float) ($stats->paid ?? 0),
                    'due' => (float) ($stats->due ?? 0),
                ],
            ])
            ->orderColumn('supplier_name', 'suppliers.supplier_name $1')
            ->orderColumn('location_address', 'locations.address $1')
            ->orderColumn('invoice_id', 'purchases.invoice_id $1')
            ->orderColumn('date', 'purchases.date $1')
            ->orderColumn('total_price', 'purchases.total_price $1')
            ->orderColumn('paid_amount', 'purchases.paid_amount $1')
            ->orderColumn('due_amount', 'due_amount $1')
            ->orderColumn('created_at', 'purchases.created_at $1')
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->where('suppliers.supplier_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('location_address', function ($q, $keyword) {
                $q->where('locations.address', 'like', "%{$keyword}%");
            })
            ->filterColumn('invoice_id', function ($q, $keyword) {
                $q->where('purchases.invoice_id', 'like', "%{$keyword}%");
            })
            ->filterColumn('date', function ($q, $keyword) {
                $q->where('purchases.date', 'like', "%{$keyword}%");
            })
            ->filter(function ($q) use ($request) {
                $search = trim((string) $request->input('search.value', ''));
                if ($search === '') {
                    return;
                }

                $like = '%' . $search . '%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('suppliers.supplier_name', 'like', $like)
                        ->orWhere('locations.address', 'like', $like)
                        ->orWhere('purchases.invoice_id', 'like', $like)
                        ->orWhere('purchases.date', 'like', $like);
                });
            }, false)
            ->addColumn('action', function ($row) {
                return
                    '<div class="action-btns">
                        <a href="' . route('purchase.show', $row->id) . '" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="' . route('purchase.edit', $row->id) . '" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button" class="border-0 text-danger bg-transparent delete-button"
                            data-url="' . route('purchase.destroy', $row->id) . '" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
            })
            ->addColumn('status', function ($row) {
                $due = (float) $row->due_amount;
                if ($due > 0) {
                    return '<span class="px-2 py-1 border rounded text-danger">Unpaid</span>';
                }
                return '<span class="px-2 py-1 border rounded text-success">Paid</span>';
            })
            ->editColumn('total_price', fn ($row) => number_format((float) $row->total_price, 2))
            ->editColumn('paid_amount', fn ($row) => number_format((float) $row->paid_amount, 2))
            ->editColumn('due_amount', fn ($row) => number_format((float) $row->due_amount, 2))
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
        try {
            Purchase::where('invoice_id', $purchase->invoice_id)->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Purchase record deleted successfully.']);
            }

            return redirect()->route('purchase.index');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error deleting purchase record: ' . $e->getMessage()]);
            }

            return redirect()->route('purchase.index')->with('error', 'Error deleting purchase record.');
        }
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
