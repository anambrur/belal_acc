<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Road;
use App\Models\Payment;
use App\Models\Accounts;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Services\CustomerLedgerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function __construct(protected CustomerLedgerService $ledgerService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roads = Road::all();
        $locations = Location::all();
        return view('customer.customerList', compact('roads', 'locations'));
    }
    /**
     * Yajra data table — server-side query with pre-aggregated joins (fast).
     */
    public function customerDataList(Request $request)
    {
        $sellAgg = DB::table('sells')
            ->select('customer_id', DB::raw('SUM(total_price) as sell_total'))
            ->groupBy('customer_id');

        $returnAgg = DB::table('sells_returns')
            ->select('customer_id', DB::raw('SUM(total_amount) as return_total'))
            ->groupBy('customer_id');

        $paymentAgg = DB::table('payments')
            ->select('payable_id', DB::raw('SUM(amount) as payment_total'))
            ->where('payable_type', Customer::class)
            ->groupBy('payable_id');

        $query = DB::table('customers')
            ->leftJoin('roads', 'roads.id', '=', 'customers.road_id')
            ->leftJoin('locations', 'locations.id', '=', 'customers.location_id')
            ->leftJoinSub($sellAgg, 'sell_agg', 'sell_agg.customer_id', '=', 'customers.id')
            ->leftJoinSub($returnAgg, 'return_agg', 'return_agg.customer_id', '=', 'customers.id')
            ->leftJoinSub($paymentAgg, 'payment_agg', 'payment_agg.payable_id', '=', 'customers.id')
            ->select([
                'customers.id',
                'customers.customer_name',
                'customers.phone',
                'customers.created_at',
                'roads.name as road_name',
                'locations.address as location_address',
                DB::raw('COALESCE(sell_agg.sell_total, 0) as sell_total'),
                DB::raw('COALESCE(return_agg.return_total, 0) as return_total'),
                DB::raw('COALESCE(payment_agg.payment_total, 0) as payment_total'),
                DB::raw('(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) as due_total'),
            ]);

        if ($request->filled('filter_road') && $request->filter_road !== 'All Road') {
            $query->where('customers.road_id', $request->filter_road);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('customers.location_id', $request->filter_address);
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'All Status') {
            $dueExpr = '(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0))';
            if ($request->filter_status === 'Due') {
                $query->whereRaw("{$dueExpr} > 0");
            } elseif ($request->filter_status === 'Advance') {
                $query->whereRaw("{$dueExpr} < 0");
            } elseif ($request->filter_status === 'Cleared') {
                $query->whereRaw("{$dueExpr} = 0");
            }
        }

        $statsQuery = clone $query;
        $statsQuery->columns = null;
        $stats = $statsQuery
            ->selectRaw('
                COUNT(*) as total_count,
                COALESCE(SUM(COALESCE(sell_agg.sell_total, 0)), 0) as sell_total,
                COALESCE(SUM(COALESCE(return_agg.return_total, 0)), 0) as return_total,
                COALESCE(SUM(COALESCE(payment_agg.payment_total, 0)), 0) as payment_total,
                COALESCE(SUM(COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)), 0) as due_total,
                SUM(CASE WHEN (COALESCE(sell_agg.sell_total, 0) - COALESCE(return_agg.return_total, 0) - COALESCE(payment_agg.payment_total, 0)) > 0 THEN 1 ELSE 0 END) as due_count
            ')
            ->first();

        return DataTables::of($query)
            ->with([
                'stats' => [
                    'total_count' => (int) ($stats->total_count ?? 0),
                    'due_count' => (int) ($stats->due_count ?? 0),
                    'sell_total' => (float) ($stats->sell_total ?? 0),
                    'return_total' => (float) ($stats->return_total ?? 0),
                    'payment_total' => (float) ($stats->payment_total ?? 0),
                    'due_total' => (float) ($stats->due_total ?? 0),
                ],
            ])
            ->orderColumn('customer_name', 'customers.customer_name $1')
            ->orderColumn('phone', 'customers.phone $1')
            ->orderColumn('road_name', 'roads.name $1')
            ->orderColumn('location_address', 'locations.address $1')
            ->orderColumn('sell_total', 'sell_total $1')
            ->orderColumn('return_total', 'return_total $1')
            ->orderColumn('payment_total', 'payment_total $1')
            ->orderColumn('due_total', 'due_total $1')
            ->orderColumn('created_at', 'customers.created_at $1')
            ->filterColumn('customer_name', function ($q, $keyword) {
                $q->where('customers.customer_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('phone', function ($q, $keyword) {
                $q->where('customers.phone', 'like', "%{$keyword}%");
            })
            ->filterColumn('road_name', function ($q, $keyword) {
                $q->where('roads.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('location_address', function ($q, $keyword) {
                $q->where('locations.address', 'like', "%{$keyword}%");
            })
            // Second arg must be false: turns OFF Yajra auto-search (which wrongly uses customers.road_name)
            ->filter(function ($q) use ($request) {
                $search = trim((string) $request->input('search.value', ''));
                if ($search === '') {
                    return;
                }

                $like = '%' . $search . '%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('customers.customer_name', 'like', $like)
                        ->orWhere('customers.phone', 'like', $like)
                        ->orWhere('roads.name', 'like', $like)
                        ->orWhere('locations.address', 'like', $like);
                });
            }, false)
            ->addColumn('action', function ($row) {
                $customerId = $row->id;
                return
                    '<div class="action-btns">
                        <a href="' . route('customer.show', $customerId) . '" title="Ledger">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="' . route('customer.edit', $customerId) . '" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="' . route('customer.destroy', $customerId) . '" method="post" class="d-inline">
                            <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                            <input type="hidden" name="_method" value="delete"/>
                            <button onclick="return confirm(\'Are you sure you want to delete?\')"
                                class="border-0 text-danger bg-transparent p-0" type="submit" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            ->addColumn('actionPayment', function ($row) {
                return '<a class="payment-link" href="' . route('customerPayment', $row->id) . '">
                    <i class="fa fa-money-bill-wave"></i> Payment
                </a>';
            })
            ->addColumn('status', function ($row) {
                $due = (float) $row->due_total;
                if ($due > 0) {
                    return '<span class="badge bg-danger">Due</span>';
                }
                if ($due < 0) {
                    return '<span class="badge bg-info text-dark">Advance</span>';
                }
                return '<span class="badge bg-success">Cleared</span>';
            })
            ->editColumn('sell_total', fn ($row) => number_format((float) $row->sell_total, 2))
            ->editColumn('return_total', fn ($row) => number_format((float) $row->return_total, 2))
            ->editColumn('payment_total', fn ($row) => number_format((float) $row->payment_total, 2))
            ->editColumn('due_total', fn ($row) => number_format((float) $row->due_total, 2))
            ->rawColumns(['actionPayment', 'action', 'status'])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $road = Road::all();
        $location = Location::all();
        return view('customer.customerAdd', compact('road', 'location'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'road_id' => 'required',
            'location_id' => 'required'
        ]);

        $customer_name = $request->customer_name;
        $phone = $request->phone;
        $road_id = $request->road_id;
        $location_id = $request->location_id;
        Customer::create(['customer_name' => $customer_name,  'phone' => $phone, 'road_id' => $road_id, 'location_id' => $location_id]);
        return redirect()->route('customer.create')->with('success', 'Customer added successfully');
    }

    /**
     * Display the customer ledger.
     */
    public function show(Request $request, $customerId)
    {
        try {
            $customer = Customer::with('location', 'road')->find($customerId);

            if (!$customer) {
                throw new Exception('Customer not found');
            }

            $startDate = $request->filled('start_date') ? $request->input('start_date') : null;
            $endDate = $request->filled('end_date') ? $request->input('end_date') : null;
            $perPage = max(1, (int) $request->input('per_page', 50));
            if (!in_array($perPage, [25, 50, 100, 200], true)) {
                $perPage = 50;
            }

            $ledger = $this->ledgerService->build((int) $customerId, $startDate, $endDate);
            $entries = $ledger['entries'];
            $totalRecords = $entries->count();
            $lastPage = max(1, (int) ceil($totalRecords / $perPage));

            // Default to last page so newest ASC entries are visible
            $currentPage = $request->has('page')
                ? max(1, (int) $request->input('page'))
                : $lastPage;
            $currentPage = min($currentPage, $lastPage);

            $offset = ($currentPage - 1) * $perPage;
            $paginatedEntries = $entries->slice($offset, $perPage)->values();

            $queryParams = array_filter([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'per_page' => $perPage,
            ], fn ($v) => $v !== null && $v !== '');

            $ledgerPaginator = new LengthAwarePaginator(
                $paginatedEntries,
                $totalRecords,
                $perPage,
                $currentPage,
                [
                    'path' => Paginator::resolveCurrentPath(),
                    'query' => $queryParams,
                ]
            );

            return view('customer.cus_ledger', [
                'customer' => $customer,
                'ledgerPaginator' => $ledgerPaginator,
                'openingBalance' => $ledger['openingBalance'],
                'debitTotal' => $ledger['debitTotal'],
                'creditTotal' => $ledger['creditTotal'],
                'dueTotal' => $ledger['closingBalance'],
                'isFiltered' => $ledger['isFiltered'],
                'startDate' => $startDate,
                'endDate' => $endDate,
                'perPage' => $perPage,
            ]);
        } catch (Exception $e) {
            return redirect()->route('customer.index')
                ->with('error', 'Customer not found or an error occurred.');
        }
    }

    /**
     * Download customer ledger as PDF.
     */
    public function downloadLedgerPdf(Request $request, $customer)
    {
        try {
            $customerId = (int) $customer;
            $customerModel = Customer::with('location', 'road')->find($customerId);

            if (!$customerModel) {
                throw new Exception('Customer not found');
            }

            $startDate = $request->filled('start_date') ? $request->input('start_date') : null;
            $endDate = $request->filled('end_date') ? $request->input('end_date') : null;

            $ledger = $this->ledgerService->build($customerId, $startDate, $endDate);

            $pdf = Pdf::loadView('customer.cus_ledger_pdf', [
                'customer' => $customerModel,
                'entries' => $ledger['entries'],
                'openingBalance' => $ledger['openingBalance'],
                'debitTotal' => $ledger['debitTotal'],
                'creditTotal' => $ledger['creditTotal'],
                'dueTotal' => $ledger['closingBalance'],
                'isFiltered' => $ledger['isFiltered'],
                'startDate' => $startDate,
                'endDate' => $endDate,
                'generatedAt' => now(),
                'store' => Store::current(),
            ])->setPaper('a4', 'portrait');

            $filename = 'customer-' . $customerId . '-ledger-' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);
        } catch (Exception $e) {
            return redirect()->route('customer.index')
                ->with('error', 'Failed to generate ledger PDF.');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $road = Road::all();
        $location = Location::all();
        // dd($customer);
        return view('customer.customerEdit', compact('customer', 'road', 'location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'customer_name' => 'required',
            'road_id' => 'required',
            'phone' => 'required|numeric',
            'location_id' => 'required'
        ]);

        $customer_name = $request->customer_name;
        $phone = $request->phone;
        $road_id = $request->road_id;
        $location_id = $request->location_id;
        $customer->update(['customer_name' => $customer_name, 'phone' => $phone, 'road_id' => $road_id, 'location_id' => $location_id]);
        return redirect()->route('customer.index')->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $photo = $customer->photo;
        // dd($photo);
        // Storage::disk('public')->delete($photo);
        $customer->delete();
        return redirect()->route('customer.index');
    }
    /**
     * Show the make-payment page for a customer.
     */
    public function customerPayment($id)
    {
        $customer = Customer::with(['location'])
            ->withSum('sell as sell_total', 'total_price')
            ->withSum('sellReturn as return_total', 'total_amount')
            ->withSum('payment as payment_total', 'amount')
            ->findOrFail($id);

        $sell = (float) ($customer->sell_total ?? 0);
        $return = (float) ($customer->return_total ?? 0);
        $payment = (float) ($customer->payment_total ?? 0);
        $dueAmount = $sell - $return - $payment;

        $cus_pay_data = [[
            'customer' => [
                'id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'address' => $customer->location?->address ?? '',
                'photo' => $customer->photo,
            ],
            'sell' => $sell,
            'return' => $return,
            'payment' => $payment,
        ]];

        $paymentMethod = PaymentMethod::all();
        $accounts = Accounts::all();

        return view('makePayment.customerPayment', compact('cus_pay_data', 'dueAmount', 'paymentMethod', 'accounts'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function makeCustomerPayment(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'customer_name' => 'required',
            'accounts_id' => 'required',
            'paid_amount' => 'required',
            'date' => 'required',
        ]);

        $customer_id = $request->customer_id;
        $paid_amount = $request->paid_amount;
        $date = $request->date;
        $payment_for = $request->payment_for;

        $customerData = Customer::findOrFail($customer_id);

        $customerData->payment()->create(['accounts_id' => $request->accounts_id, 'amount' => $paid_amount, 'date' => $date, 'payment_for' => $payment_for]);

        // Update cash in and accounts
        $accounts = Accounts::findOrFail($request->accounts_id);
        $accounts->update(['amount' => $accounts->amount + $request->paid_amount]);

        return redirect()->route('customer.index')->with('success', 'Payment added successfully');
    }


    /**
     * Show the form for editing a customer payment.
     */
    public function editPayment(Payment $payment)
    {
        try {
            // Verify this payment belongs to a customer
            if ($payment->payable_type !== Customer::class) {
                throw new Exception('Invalid payment type');
            }

            $customer = $payment->payable;
            $paymentMethod = PaymentMethod::all();
            $accounts = Accounts::all();
            $payment->date = \Carbon\Carbon::parse($payment->date);

            return view('customer.edit_customer_payment', compact('payment', 'customer', 'paymentMethod', 'accounts'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load payment edit page: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified customer payment in storage.
     */
    public function updatePayment(Request $request, Payment $payment)
    {
        try {
            $validatedData = $request->validate([
                'accounts_id' => 'required|exists:accounts,id',
                'paid_amount' => 'required|numeric|min:0',
                'date' => 'required|date',
                'payment_for' => 'nullable|string'
            ]);

            // Verify this payment belongs to a customer
            if ($payment->payable_type !== Customer::class) {
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
                    $oldAccount->update(['amount' => $oldAccount->amount - $oldAmount]);
                }

                // Update payment
                $paymentData = [
                    'accounts_id' => $newAccountId,
                    'amount' => $newAmount,
                    'date' => $validatedData['date']
                ];

                if (isset($validatedData['payment_for'])) {
                    $paymentData['payment_for'] = $validatedData['payment_for'];
                }

                $payment->update($paymentData);

                // Add new amount to new account
                $newAccount = Accounts::find($newAccountId);
                if ($newAccount) {
                    $newAccount->update(['amount' => $newAccount->amount + $newAmount]);
                }

                DB::commit();

                return redirect()->route('customer.show', $payment->payable_id)
                    ->with('success', 'Payment updated successfully');
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
                ->with('error', 'Failed to update payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified customer payment from storage.
     */
    public function destroyPayment(Payment $payment)
    {
        try {
            // Verify this payment belongs to a customer
            if ($payment->payable_type !== Customer::class) {
                throw new Exception('Invalid payment type');
            }

            DB::beginTransaction();

            try {
                // Remove the payment amount from the account
                $account = Accounts::find($payment->accounts_id);
                if ($account) {
                    $account->update(['amount' => $account->amount - $payment->amount]);
                }

                $customerId = $payment->payable_id;
                $payment->delete();

                DB::commit();

                return redirect()->route('customer.show', $customerId)
                    ->with('success', 'Payment deleted successfully');
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }
}
