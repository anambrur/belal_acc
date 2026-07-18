<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Accounts;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Purchase;
use App\Models\SellsReturn;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;
use Yajra\DataTables\Facades\DataTables;

class SellController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::all();
        $address = Location::all();
        return view('sell.sellList', compact('customer', 'address'));
    }

    /**
     * Yajra data table
     */
    public function getSellDataList(Request $request)
    {
        $query =  Sell::groupBy('invoice_id')->orderBy('id', 'DESC')
            ->select('id', 'invoice_id', 'customer_id', 'total_price', 'paid_amount', 'total_discount', 'date', 'location_id', 'created_at', 'updated_at')->with('customer', 'customer.location');


        if ($request->has('filter_customer') && $request->filter_customer != 'All Customer') {
            $query->where('customer_id', $request->filter_customer);
        }

        if ($request->has('filter_address') && $request->filter_address != 'All Address') {
            $query->where('location_id', $request->filter_address);
        }

        // // Apply filtering based on date range
        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }



        $data = $query->orderBy('created_at', 'DESC')->get();
        // dd($data);
        $sl = 0;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actions =
                    '<div class="d-flex align-center ">
                            <a href="' . route('sell.show', $row->id) . '"><span><i class="fa-solid fa-eye me-3"></i></span></a>
                        
                            <a href="' . route('sell.edit', $row->id) . '"> <span><i class="fa-solid fa-pen "></i></span></a>
                            
                            <button type="button" class="border-0 ms-2 text-danger bg-transparent delete-button" 
                            data-url="' . route('sell.destroy', $row->id) . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->addColumn('due', function ($row) {
                return $row->total_price - $row->paid_amount;
            })
            ->addColumn('status', function ($row) {
                $statusText = ($row->total_price - $row->paid_amount > 0) ? 'Unpaid' : 'Paid';
                $textColor = ($row->total_price - $row->paid_amount > 0) ? 'text-danger' : 'text-success';

                return '<span class="px-2 py-1 border rounded ' . $textColor . '">' . $statusText . '</span>';
            })
            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })
            ->rawColumns(['due', 'action', 'status', 'sl'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customer = Customer::with('location')->get();
        $pre_invoice_id = Sell::max('invoice_id');
        $invoice_id = $pre_invoice_id + 1;
        $paymentMethod = PaymentMethod::all();
        $accounts = Accounts::all();
        return view('sell.sellAdd', compact('customer', 'invoice_id', 'paymentMethod', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'customer_id' => 'required',
                'discount_price' => 'required',
                'date' => 'required|date',
                'total_price' => 'required|numeric',
                'paid_amount' => 'required|numeric',
                'accounts_id' => 'required|exists:accounts,id', // Ensure accounts_id exists in accounts table
            ]);

            // Retrieve location_id based on customer
            $location = Customer::where('id', $request->customer_id)->first();
            if (!$location) {
                return back()->withErrors(['customer_id' => 'Customer not found.']);
            }
            $location_id = $location->location_id;

            // Generate invoice ID
            $pre_invoice_id = Sell::max('invoice_id');
            $invoice_id = ($pre_invoice_id ?? 1000) + 1;

            // Create new sell record
            Sell::create([
                'customer_id' => $request->customer_id,
                'total_price' => $request->total_price,
                'paid_amount' => $request->paid_amount,
                'date' => $request->date,
                'invoice_id' => $invoice_id,
                'total_discount' => $request->discount_price,
                'location_id' => $location_id,
            ]);

            // Retrieve customer data
            $customerData = Customer::findOrFail($request->customer_id);

            // Create a payment record
            $customerData->payment()->create([
                'accounts_id' => $request->accounts_id,
                'amount' => $request->paid_amount,
                'date' => $request->date,
            ]);

            // Update cash in and accounts
            $accounts = Accounts::findOrFail($request->accounts_id);
            $accounts->update(['amount' => $accounts->amount + $request->paid_amount]);

            DB::commit();
            toastr()->success('Sell added successfully', 'Success');
            return redirect()->route('sell.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            toastr()->error('Failed to add sell. Please try again.', 'Error');
            return back()->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Sell $sell)
    {
        $invoice_id = $sell->invoice_id;
        $customer = Customer::with('location')->get();
        $sell_details = Sell::where('invoice_id', $invoice_id)->get();
        return view('sell.sellShow', compact('sell_details', 'customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sell $sell)
    {
        $invoice_id = $sell->invoice_id;
        $product = Product::all();
        $sell_details = Sell::where('invoice_id', $invoice_id)->get();
        $paymentMethod = PaymentMethod::all();
        $accounts = Accounts::all();
        return view('sell.sellEdit', compact('sell_details', 'product', 'paymentMethod', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sell)
    {
        DB::beginTransaction();
        try {
            $invoice_id = $sell;
            $sell_edit = Sell::where('invoice_id', $invoice_id)->get();
            if ($sell_edit->isNotEmpty()) {
                $sell_edit->each->delete();
            }
            $request->validate([
                'customer_id' => 'required',
                'date' => 'required',
                'total_price' => 'required',
                'paid_amount' => 'required',
                'sub_total' => 'required',
                'discount_price' => 'required',
                'due' => 'required',
                'accounts_id' => 'required',

            ]);
            $customer_id = $request->customer_id;
            $date = $request->date;
            $total_price = $request->total_price;
            $paid_amount = $request->paid_amount;
            $discount_price = $request->discount_price;
            Sell::create([
                'customer_id' => $customer_id,
                'total_price' => $total_price,
                'paid_amount' => $paid_amount,
                'date' => $date,
                'invoice_id' => $invoice_id,
                'total_discount' => $discount_price
            ]);

            //Retrieve customer data
            $customerData = Customer::findOrFail($customer_id);
            // Update or create payment entry
            $payment = $customerData->payment()->firstOrCreate([
                'accounts_id' => $request->accounts_id,
                'amount' => $paid_amount,
                'date' => $date,
            ]);


            if (!$payment->wasRecentlyCreated) {
                $payment->update([
                    'amount' => $paid_amount,
                    'date' => $date,
                ]);
            }

            // Update cash in and accounts
            $accounts = Accounts::findOrFail($request->accounts_id);
            $accounts->update(['amount' => $accounts->amount + $request->paid_amount]);
            DB::commit();
            toastr()->success('Sell updated successfully', 'Success');
            return redirect()->route('sell.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            toastr()->error('Something went wrong', 'Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sell $sell)
    {
        try {
            // Get all sells with the same invoice_id
            $sells = Sell::where('invoice_id', $sell->invoice_id)->get();

            // Get the total paid amount for this invoice
            $totalPaid = $sells->sum('paid_amount');

            // Find related payments and reverse them
            $customer = Customer::find($sell->customer_id);
            if ($customer) {
                $payments = $customer->payment()
                    ->where('amount', $totalPaid)
                    ->where('date', $sell->date)
                    ->get();

                // Reverse the account balances
                foreach ($payments as $payment) {
                    $account = Accounts::find($payment->accounts_id);
                    if ($account) {
                        $account->update(['amount' => $account->amount - $payment->amount]);
                    }
                    $payment->delete();
                }
            }

            // Delete all sells with this invoice_id
            Sell::where('invoice_id', $sell->invoice_id)->delete();

            return response()->json(['success' => true, 'message' => 'Sell record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting sell record: ' . $e->getMessage()]);
        }
    }

    /**
     * api call for sell data.
     */
    public function sellsData($id)
    {
        $purchase = Purchase::where('product_id', $id)->orderBy('id', 'desc')->first();
        $product = Product::find($id);
        // Calculate total purchases
        $totalPurchases = Purchase::where('product_id', $id)->sum('quantity');
        $totalSales = Sell::where('product_id', $id)->sum('quantity');
        $totalSalesReturn = SellsReturn::where('product_id', $id)->sum('quantity');
        $totalPurchaseReturn = PurchaseReturn::where('product_id', $id)->sum('quantity');
        $currentStock = ($totalPurchases + $totalSalesReturn) - ($totalSales + $totalPurchaseReturn);

        return response()->json(['purchase' => $purchase, 'product' => $product, 'currentStock' => $currentStock]);
    }
    public function sellsDataBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();
        $product_id = $product->id;
        $purchase = Purchase::where('product_id', $product_id)->orderBy('id', 'desc')->first();
        // Calculate total purchases
        $totalPurchases = Purchase::where('product_id', $product_id)->sum('quantity');
        $totalSales = Sell::where('product_id', $product_id)->sum('quantity');
        $totalSalesReturn = SellsReturn::where('product_id', $product_id)->sum('quantity');
        $totalPurchaseReturn = PurchaseReturn::where('product_id', $product_id)->sum('quantity');
        $currentStock = ($totalPurchases + $totalSalesReturn) - ($totalSales + $totalPurchaseReturn);
        return response()->json(['purchase' => $purchase, 'product' => $product, 'currentStock' => $currentStock]);
    }
}
