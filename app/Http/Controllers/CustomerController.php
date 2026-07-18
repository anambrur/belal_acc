<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Road;
use App\Models\Sell;
use App\Models\Payment;
use App\Models\Accounts;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Purchase;
use App\Models\SellsReturn;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
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
     * Yajra data table.
     */
    public function customerDataList(Request $request)
    {
        $query = Customer::with(['sell' => function ($q) {
            $q->groupBy('invoice_id');
        }])->with('sellReturn', 'payment', 'road', 'location');

        // Apply filters
        if ($request->has('filter_road') && $request->filter_road != 'All Road') {
            $query->where('road_id', $request->filter_road);
        }

        if ($request->has('filter_address') && $request->filter_address != 'All Address') {
            $query->where('location_id', $request->filter_address);
        }

        $customers = $query->orderBy('created_at', 'DESC')->get();




        $data = [];
        foreach ($customers as $c) {
            // dd($c);
            $d = ['customer' => ['id' => $c->id, 'customer_name' => $c->customer_name, 'phone' => $c->phone, 'road' => $c->road->name, 'location' => $c->location->address]];
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
            $data[] = $d;
        }


        // <a href="' . route('customer.show', $customerId) . '">
        $data = $data;
        $sl = 0;
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $customerId = $row['customer']['id'];
                $actions =
                    '<div class="d-flex align-center ">
                    
                    <a href="' . route('customer.show', $customerId) . '">
                        <span><i class="fa-solid fa-eye me-3"></i></span>
                    </a> <a href="' . route('customer.edit', $customerId) . '">
                        <span><i class="fa-solid fa-pen "></i></span>
                    </a>
                    
                    <form action="' . route('customer.destroy', $customerId) . '" method="post">
                        <input type="hidden" name ="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name ="_method" value="delete"/>
                        <button onclick="return confirm(\'Are you sure you want to delete?\')" class="btn"><i class="fas fa-trash"
                        style="color: #FF0000;"></i></button>
                    </form>
                </div>';
                return $actions;
            })



            ->addColumn('due', function ($row) {
                return $row['sell'] - $row['payment'];
            })

            ->addColumn('actionPayment', function ($row) {
                $customerId = $row['customer']['id'];
                return '<a href="' . route('customerPayment', $customerId) . '"><span
                class="badges bg-secondary">Payment</span></a>';
            })

            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })
            ->addColumn('status', function ($row) {
                $status = $row['sell'] - $row['payment'] > 0 ? 'btn-danger btn-sm' : 'btn-success btn-sm';
                return '<button class="btn ' . $status . '">Status</button>';
            })
            ->rawColumns(['actionPayment', 'due', 'action', 'sl', 'status'])

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
     * Display the specified resource.
     */

     public function show(Request $request, $customerId)
     {
        try {
            $customer = Customer::with('location', 'road')->where('id', $customerId)->first();

            if (!$customer) {
                throw new Exception('Customer not found');
            }

            $startDate = $request->input('start_date', now()->subYear()->format('Y-m-d'));
            $endDate   = $request->input('end_date', now()->format('Y-m-d'));
            $perPage   = (int) $request->input('per_page', 50);
            $currentPage = (int) $request->input('page', 1);

            // Fetch ALL records within date range
            $salesData = Sell::where('customer_id', $customerId)
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('invoice_id')
                ->get();


            $returnData = SellsReturn::where('customer_id', $customerId)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $paymentData = Payment::where('payable_id', $customerId)
                ->where('payable_type', Customer::class)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('id', 'accounts_id', 'amount', 'date', 'payment_for')
                ->get();

            // Merge and sort ALL records by date
            $customer_ledger = array_merge(
                $salesData->all(),
                $returnData->all(),
                $paymentData->all()
            );

            usort($customer_ledger, fn($a, $b) => strtotime($a['date']) - strtotime($b['date']));

            // Calculate running balance on ALL records BEFORE pagination
            $runningBalance = 0;
            foreach ($customer_ledger as &$entry) {
                if (isset($entry->total_price) && $entry->total_price > 0) {
                    $runningBalance += $entry->total_price;
                } elseif (isset($entry->total_amount) && $entry->total_amount > 0) {
                    $runningBalance -= $entry->total_amount;
                } elseif (isset($entry->amount) && $entry->amount > 0) {
                    $runningBalance -= $entry->amount;
                }
                $entry->running_balance = $runningBalance;
            }
            unset($entry);

            $totalRecords    = count($customer_ledger);
            $offset          = ($currentPage - 1) * $perPage;
            $paginatedLedger = array_slice($customer_ledger, $offset, $perPage);

            $ledgerPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedLedger,
                $totalRecords,
                $perPage,
                $currentPage,
                [
                    'path'  => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                    'query' => $request->query()
                ]
            );

            // Totals from FILTERED data only
            $debitTotal  = $salesData->sum('total_price');
            $creditTotal = $returnData->sum('total_amount') + $paymentData->sum('amount');
            $dueTotal    = $debitTotal - $creditTotal;

            return view('customer.cus_ledger', compact(
                'customer',
                'ledgerPaginator',
                'debitTotal',
                'creditTotal',
                'dueTotal',
                'startDate',
                'endDate',
                'perPage'
            ));
        } catch (Exception $e) {
            return redirect()->route('customer.index')
                ->with('error', 'Customer not found or an error occurred.');
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
     * make payment the specified resource from storage.
     */
    public function customerPayment($id)
    {
        $customers = Customer::with(['sell' => function ($q) {
            $q->groupBy('invoice_id');
        }])->with('sellReturn', 'payment', 'location')
            ->orderBy('created_at', 'DESC')
            ->where('id', $id)
            ->get();

        // dd($customers);


        $cus_pay_data = [];
        foreach ($customers as $c) {
            // dd($c);
            $d = ['customer' => ['id' => $c->id, 'customer_name' => $c->customer_name, 'phone' => $c->phone, 'email' => $c->email, 'address' => $c->location->address, 'photo' => $c->photo]];
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
            $cus_pay_data[] = $d;
        }
        // dd($cus_pay_data);
        $dueAmount = ($cus_pay_data[0]['sell']) - ($cus_pay_data[0]['payment'] + $cus_pay_data[0]['return']);
        // dd($dueAmount);
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
