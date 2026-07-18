<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Road;
use App\Models\Payment;
use App\Models\Accounts;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roads = Road::all();
        $locations = Location::all();
        return view('supplier.supplier_list', compact('roads', 'locations'));
    }

    /**
     * Yajra data table.
     */
    public function supplierDataList(Request $request)
    {
        $query = Supplier::with([
            'purchase' => function ($q) {
                $q->groupBy('invoice_id');
            },
            'purchaseReturn',
            'payment',
            'road',
            'location'
        ]);

        // Apply filters
        if ($request->has('filter_road') && $request->filter_road != 'All Road') {
            $query->where('road_id', $request->filter_road);
        }

        if ($request->has('filter_address') && $request->filter_address != 'All Address') {
            $query->where('location_id', $request->filter_address);
        }

        $suppliers = $query->orderBy('created_at', 'DESC')->get();

        $data = [];
        foreach ($suppliers as $c) {
            $d = [
                'supplier' => [
                    'id' => $c->id,
                    'supplier_name' => $c->supplier_name,
                    'phone' => $c->phone,
                    'road' => $c->road->name,
                    'location' => $c->location->address
                ]
            ];

            $d['purchase'] = $c->purchase->sum('total_price');
            $d['return'] = $c->purchaseReturn->sum('total_amount');
            $d['payment'] = $c->payment->sum('amount');

            $data[] = $d;
        }

        $sl = 0;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $supplierId = $row['supplier']['id'];
                $actions = '
                <div class="d-flex align-center">
                    <a href="' . route('supplier.show', $supplierId) . '">
                        <span><i class="fa-solid fa-eye me-3"></i></span>
                    </a>
                    <a href="' . route('supplier.edit', $supplierId) . '">
                        <span><i class="fa-solid fa-pen"></i></span>
                    </a>
                    <form action="' . route('supplier.destroy', $supplierId) . '" method="post">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="delete"/>
                        <button onclick="return confirm(\'Are you sure you want to delete?\')" class="btn">
                            <i class="fas fa-trash" style="color: #FF0000;"></i>
                        </button>
                    </form>
                </div>';
                return $actions;
            })
            ->addColumn('actionPayment', function ($row) {
                $supplierId = $row['supplier']['id'];
                return '<a href="' . route('supplierPayment', $supplierId) . '">
                <span class="badges bg-secondary">Payment</span>
            </a>';
            })
            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })
            ->addColumn('due', function ($row) {
                return $row['purchase'] - $row['payment'] - $row['return'];
            })
            ->addColumn('status', function ($row) {
                $due = $row['purchase'] - $row['payment'] - $row['return'];

                if ($due > 0) {
                    // Due status - red badge
                    return '<span class="badge bg-danger">Due</span>';
                } else if ($due < 0) {
                    // Overpaid status - blue badge
                    return '<span class="badge bg-info">Overpaid</span>';
                } else {
                    // Paid status - green badge
                    return '<span class="badge bg-success">Paid</span>';
                }
            })
            ->rawColumns(['actionPayment', 'action', 'sl', 'due', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $road = Road::all();
        $location = Location::all();
        return view('supplier.supplier_add', compact('road', 'location'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'supplier_name' => 'required|string|max:255',
                'phone' => 'required|numeric|unique:suppliers,phone',
                'road_id' => 'required|exists:roads,id',
                'location_id' => 'required|exists:locations,id'
            ]);

            if ($validator->fails()) {
                toastr()->error($validator->errors()->first(), 'Validation Error');
                return back()->withInput();
            }

            Supplier::create($validator->validated());

            toastr()->success('Supplier added successfully', 'Success');
            return redirect()->route('supplier.index');
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage(), 'Validation Error');
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;

            if ($errorCode == 1062) { // Duplicate entry
                toastr()->error('Phone number already exists. Please use a different phone number.', 'Duplicate Entry');
                return redirect()
                    ->back()
                    ->withInput();
            }

            toastr()->error('Database error occurred. Please try again.', 'Database Error');
            return redirect()
                ->back()
                ->withInput();
        } catch (Exception $e) {
            toastr()->error('An unexpected error occurred. Please try again.', 'Error');
            return redirect()
                ->back()
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $supplierId)
    {
        try {
            $supplier = Supplier::with('road', 'location')->where('id', $supplierId)->first();

            // Get date range from request or set defaults
            $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            // Query for purchases with date filter
            $supp_purchase = Purchase::where('supplier_id', $supplierId)
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('invoice_id');

            // Query for purchase returns with date filter
            $supp_purchase_return = PurchaseReturn::where('supplier_id', $supplierId)
                ->whereBetween('date', [$startDate, $endDate]);

            // Query for payments with date filter
            $supp_payments = Payment::where('payable_id', $supplierId)
                ->where('payable_type', Supplier::class)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('id', 'accounts_id', 'amount', 'date'); // Add this line

            // Get counts for pagination
            $purchaseCount = $supp_purchase->count();
            $returnCount = $supp_purchase_return->count();
            $paymentCount = $supp_payments->count();
            $totalRecords = $purchaseCount + $returnCount + $paymentCount;

            // Implement pagination
            $perPage = 50; // Adjust based on your needs
            $currentPage = $request->input('page', 1);

            // Get paginated data
            $purchaseData = $supp_purchase->get();
            $returnData = $supp_purchase_return->get();
            $paymentData = $supp_payments->get();

            // Combine all data
            $supplier_ledger = array_merge(
                $purchaseData->all(),
                $returnData->all(),
                $paymentData->all()
            );

            // Sort by date
            usort($supplier_ledger, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            // Manual pagination implementation
            $offset = ($currentPage - 1) * $perPage;
            $paginatedLedger = array_slice($supplier_ledger, $offset, $perPage);

            // Create LengthAwarePaginator instance
            $ledgerPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedLedger,
                $totalRecords,
                $perPage,
                $currentPage,
                [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                    'query' => $request->query()
                ]
            );

            // Calculate totals for the footer
            $debitTotal = $purchaseData->sum('total_price');
            $creditTotal = $returnData->sum('total_amount') + $paymentData->sum('amount');

            // dd($supplier);

            return view('supplier.sup_ledger', compact(
                'supplier',
                'ledgerPaginator',
                'debitTotal',
                'creditTotal',
                'startDate',
                'endDate'
            ));
        } catch (Exception $e) {
            return redirect()
                ->route('supplier.index')
                ->with('error', 'Supplier not found or an error occurred.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $roads = Road::all();
        $locations = Location::all();
        return view('supplier.supplier_edit', compact('supplier', 'roads', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validatedData = $request->validate([
                'supplier_name' => 'required',
                'phone' => 'required|numeric',
                'road_id' => 'required|exists:roads,id',
                'location_id' => 'required|exists:locations,id'
            ]);

            $supplier->update($validatedData);

            toastr()->success('Supplier updated successfully', 'Success');
            return redirect()->route('supplier.index');
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage(), 'Validation Error');
            return back()->withInput();
        } catch (Exception $e) {
            toastr()->error('Failed to update supplier. Please try again.', 'Error');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            if ($supplier->photo) {
                Storage::disk('public')->delete($supplier->photo);
            }

            $supplier->delete();

            toastr()->success('Supplier deleted successfully', 'Success');
            return redirect()->route('supplier.index');
        } catch (Exception $e) {
            toastr()->error('Failed to delete supplier. Please try again.', 'Error');
            return redirect()->route('supplier.index');
        }
    }

    /**
     * make payment the specified resource from storage.
     */
    public function supplierPayment($id)
    {
        try {
            $supplier_data = Supplier::with([
                'purchase' => function ($q) {
                    $q->groupBy('invoice_id');
                },
                'purchaseReturn',
                'payment'
            ])
                ->where('id', $id)
                ->orderBy('created_at', 'DESC')
                ->get();

            if ($supplier_data->isEmpty()) {
                throw new Exception('Supplier not found');
            }

            $sup_pay_data = [];
            foreach ($supplier_data as $c) {
                $d = [
                    'supplier' => [
                        'id' => $c->id,
                        'supplier_name' => $c->supplier_name,
                        'phone' => $c->phone,
                        'email' => $c->email,
                        'address' => $c->address,
                        'photo' => $c->photo
                    ]
                ];

                $d['purchase'] = $c->purchase->sum('total_price');
                $d['return'] = $c->purchaseReturn->sum('total_amount');
                $d['payment'] = $c->payment->sum('amount');

                $sup_pay_data[] = $d;
            }

            $dueAmount = $sup_pay_data[0]['purchase'] - ($sup_pay_data[0]['payment'] + $sup_pay_data[0]['return']);

            $paymentMethod = PaymentMethod::all();
            $accounts = Accounts::all();

            return view('makePayment.supplierPayment', compact('sup_pay_data', 'dueAmount', 'paymentMethod', 'accounts'));
        } catch (Exception $e) {
            return redirect()
                ->route('supplier.index')
                ->with('error', 'Failed to load payment page: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function makeSupplierPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'supplier_id'   => 'required',
                'supplier_name' => 'required',
                'accounts_id'   => 'required',
                'paid_amount'   => 'required|numeric|min:0',
                'date'          => 'required|date',
            ]);

            if ($validator->fails()) {
                toastr()->error($validator->errors()->first(), 'Validation Error');
                return back()->withErrors($validator)->withInput();
            }

            $validatedData = $validator->validated();

            $supplier_id = $validatedData['supplier_id'];
            $paid_amount = $validatedData['paid_amount'];
            $date = $validatedData['date'];

            $cash_in_hand = totalBalance();
            if ($paid_amount > $cash_in_hand) {
                return back()
                    ->with('error', "You can't pay more than cash in hand")
                    ->withInput();
            }

            $supplierData = Supplier::findOrFail($supplier_id);
            $accounts = Accounts::findOrFail($validatedData['accounts_id']);

            // Start transaction for atomic operation
            DB::beginTransaction();

            try {
                $supplierData->payment()->create([
                    'accounts_id' => $validatedData['accounts_id'],
                    'amount' => $paid_amount,
                    'date' => $date
                ]);

                $accounts->update(['amount' => $accounts->amount - $paid_amount]);

                DB::commit();

                return redirect()
                    ->route('supplier.index')
                    ->with('success', 'Payment added successfully');
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to process payment: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Show the form for editing a payment.
     */
    public function editPayment(Payment $payment)
    {
        try {
            // Verify this payment belongs to a supplier
            if ($payment->payable_type !== Supplier::class) {
                throw new Exception('Invalid payment type');
            }

            $supplier = $payment->payable;
            $paymentMethod = PaymentMethod::all();
            $accounts = Accounts::all();

            $payment->date = \Carbon\Carbon::parse($payment->date);
            
            return view('supplier.edit_supplier_payment', compact('payment', 'supplier', 'paymentMethod', 'accounts'));
        } catch (Exception $e) {
            toastr()->error('Failed to load payment edit page: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Update the specified payment in storage.
     */
    public function updatePayment(Request $request, Payment $payment)
    {
        try {
            $validatedData = $request->validate([
                'accounts_id' => 'required|exists:accounts,id',
                'paid_amount' => 'required|numeric|min:0',
                'date' => 'required|date',
            ]);

            // Verify this payment belongs to a supplier
            if ($payment->payable_type !== Supplier::class) {
                throw new Exception('Invalid payment type');
            }

            $oldAmount = $payment->amount;
            $newAmount = $validatedData['paid_amount'];
            $oldAccountId = $payment->accounts_id;
            $newAccountId = $validatedData['accounts_id'];

            DB::beginTransaction();

            try {
                // Revert old payment amount from old account
                $oldAccount = Accounts::find($oldAccountId);
                if ($oldAccount) {
                    $oldAccount->update(['amount' => $oldAccount->amount + $oldAmount]);
                }

                // Update payment
                $payment->update([
                    'accounts_id' => $newAccountId,
                    'amount' => $newAmount,
                    'date' => $validatedData['date']
                ]);

                // Deduct new amount from new account
                $newAccount = Accounts::find($newAccountId);
                if ($newAccount) {
                    $newAccount->update(['amount' => $newAccount->amount - $newAmount]);
                }

                DB::commit();

                toastr()->success('Payment updated successfully', 'Success');
                return redirect()->route('supplier.show', $payment->payable_id);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            toastr()->error('Failed to update payment: ' . $e->getMessage(), 'Error');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroyPayment(Payment $payment)
    {
        try {
            // Verify this payment belongs to a supplier
            if ($payment->payable_type !== Supplier::class) {
                throw new Exception('Invalid payment type');
            }

            DB::beginTransaction();

            try {
                // Return the payment amount to the account
                $account = Accounts::find($payment->accounts_id);
                if ($account) {
                    $account->update(['amount' => $account->amount + $payment->amount]);
                }

                $supplierId = $payment->payable_id;
                $payment->delete();

                DB::commit();

                toastr()->success('Payment deleted successfully', 'Success');
                return redirect()->route('supplier.show', $supplierId);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            toastr()->error('Failed to delete payment: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}
