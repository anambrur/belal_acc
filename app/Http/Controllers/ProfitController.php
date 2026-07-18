<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Sell;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\SellsReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class ProfitController extends Controller
{
    public function profitCalculation(Request $request)
    {
        $today = date('Y-m-d');

        $get_sell_price = Sell::where('date', $today)->groupBy('invoice_id')->pluck('total_price');
        $get_discount_price = Sell::where('date', $today)->groupBy('invoice_id')->pluck('total_discount');
        $get_paid_amount = Sell::where('date', $today)->groupBy('invoice_id')->pluck('paid_amount');
        $get_sell_product = Sell::where('date', $today)->with('product')->get();
        $get_sell_return = SellsReturn::where('date', $today)->with('product')->get();
        $get_expenses = Expense::where('date', $today)->sum('amount');
        $get_due_collection = Payment::where('date', $today)->where('payment_for', 'cus_due_return')->sum('amount');
        $get_supplier_payment = Payment::where('date', $today)->where('payable_type', 'App\Models\Supplier')->sum('amount');

        $get_invoice = Sell::where('date', $today)->groupBy('invoice_id')->get();
        $get_invoice = count($get_invoice);


        $get_loan = Loan::with(['loanPayment' => function ($query) {
            $query->select('loan_id', DB::raw('SUM(paid_amount) as total_paid_amount'))
                ->groupBy('loan_id');
        }])
            ->whereDate('date', '=', date('Y-m-d')) // Replace 'date' with the correct column name for the loan date
            ->get();

        $loan_in_hand = 0;

        foreach ($get_loan as $d) {
            $paid_amount = $d->amount;
            $total_paid_amount = isset($d->loanPayment[0]) ? $d->loanPayment[0]->total_paid_amount : 0;
            $loan_in_hand = $paid_amount - $total_paid_amount;
        }



        //return price of product
        $sum_return_sell_price = 0;
        $sum_return_pur_price = 0;
        foreach ($get_sell_return as $s) {
            $qty = $s->quantity;
            $sell_price = $s->sell_price;
            $purchase_price = $s->product->price;

            $sum_return_sell_price += $qty * $sell_price;
            $sum_return_pur_price += $qty * $purchase_price;
        }



        //get_sell_pro_pur_price of product
        $get_sell_pro_pur_price = 0;
        foreach ($get_sell_product as $pro) {
            $qyt = $pro->quantity;
            $price = $pro->product->price ?? 0;
            $total = $qyt * $price;
            $get_sell_pro_pur_price += $total;
        }

        //sum_of_day_sell price
        $sum_of_day_sell = 0;
        foreach ($get_sell_price as $day_sell) {
            // dd($day_sell);
            $sum_of_day_sell += $day_sell;
        }


        //$get_discount_price 
        $sum_discount_of_sell = 0;
        foreach ($get_discount_price as $dis) {
            $sum_discount_of_sell += $dis;
        }


        //total_paid price
        $total_paid_amount = 0;
        foreach ($get_paid_amount as $due) {
            $total_paid_amount += $due;
        }

        return view('profit.profit', compact('get_sell_product', 'sum_of_day_sell', 'get_sell_pro_pur_price', 'get_invoice', 'get_sell_return', 'sum_return_sell_price', 'sum_return_pur_price', 'sum_discount_of_sell', 'total_paid_amount', 'get_expenses', 'get_due_collection', 'get_supplier_payment','loan_in_hand'));
    }

    public function dateFilter(Request $request)
    {
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $date = new DateTime($dateTo);
        $date->modify('-1 day');
        $previousDate = $date->format('Y-m-d');
        // dd($previousDate);


        $get_sell_price = Sell::whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('invoice_id')
            ->pluck('total_price');
        $get_discount_price = Sell::whereBetween('date', [$dateFrom, $dateTo])->groupBy('invoice_id')->pluck('total_discount');
        $get_paid_price = Sell::whereBetween('date', [$dateFrom, $dateTo])->groupBy('invoice_id')->pluck('paid_amount');

        $get_sell_product = Sell::whereBetween('date', [$dateFrom, $dateTo])->with('product')->get();
        $get_sell_return = SellsReturn::whereBetween('date', [$dateFrom, $dateTo])->with('product')->get();

        $get_expenses = Expense::whereBetween('date', [$dateFrom, $dateTo])->sum('amount');

        $get_due_collection = Payment::whereBetween('date', [$dateFrom, $dateTo])->where('payment_for', 'cus_due_return')->sum('amount');
        $get_supplier_payment = Payment::whereBetween('date', [$dateFrom, $dateTo])->where('payable_type', 'App\Models\Supplier')->sum('amount');

        $get_invoice = Sell::whereBetween('date', [$dateFrom, $dateTo])->groupBy('invoice_id')->get();
        $get_invoice = count($get_invoice);
        // dd($get_due_collection);

        //return price of product
        $sum_return_sell_price = 0;
        $sum_return_pur_price = 0;
        foreach ($get_sell_return as $s) {
            $qty = $s->quantity;
            $sell_price = $s->sell_price;
            $purchase_price = $s->product->price;

            $sum_return_sell_price += $qty * $sell_price;
            $sum_return_pur_price += $qty * $purchase_price;
        }


        //get_sell_pro_pur_price of product
        $get_sell_pro_pur_price = 0;
        foreach ($get_sell_product as $pro) {
            $qyt = $pro->quantity;
            $price = $pro->product->price;
            $total = $qyt * $price;
            $get_sell_pro_pur_price += $total;
        }

        //sum_of_day_sell
        $sum_of_day_sell = 0;
        foreach ($get_sell_price as $day_sell) {
            // dd($day_sell);
            $sum_of_day_sell += $day_sell;
        }

        //$get_discount_price 
        $sum_discount_of_sell = 0;
        foreach ($get_discount_price as $dis) {
            $sum_discount_of_sell += $dis;
        }

        //total_due price
        $total_paid_amount = 0;
        foreach ($get_paid_price as $due) {
            $total_paid_amount += $due;
        }
        //call helper function
        $opening_balance = getOpeningBalanceFilter($dateFrom, $previousDate);



        // dd($get_sell_product);
        return view('profit.profit', compact('get_sell_product', 'sum_of_day_sell', 'get_sell_pro_pur_price', 'get_invoice', 'get_sell_return', 'sum_return_sell_price', 'sum_return_pur_price', 'sum_discount_of_sell', 'total_paid_amount', 'get_expenses', 'get_due_collection', 'get_supplier_payment', 'opening_balance'));
    }



    public function sellsReportList()
    {
        $customer = Customer::all();
        $address = Location::all();
        return view('report.sellReport', compact('customer', 'address'));
    }
    public function purchaseReportList()
    {
        $supplier = Supplier::all();
        $address = Location::all();
        return view('report.purchaseReport', compact('supplier', 'address'));
    }


    public function sellsReport(Request $request)
    {
        $query = DB::table('sells')
            ->leftJoin('customers', 'customers.id', '=', 'sells.customer_id')
            ->leftJoin('locations', 'locations.id', '=', 'customers.location_id')
            ->select([
                'sells.id',
                'sells.invoice_id',
                'sells.customer_id',
                'sells.total_price',
                'sells.paid_amount',
                'sells.total_discount',
                'sells.date',
                'sells.location_id',
                'sells.created_at',
                'customers.customer_name',
                'locations.address as location_address',
                DB::raw('(sells.total_price - sells.paid_amount) as due_amount'),
            ]);

        if ($request->filled('filter_customer') && $request->filter_customer !== 'All Customer') {
            $query->where('sells.customer_id', $request->filter_customer);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('sells.location_id', $request->filter_address);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('sells.date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        return DataTables::of($query)
            ->orderColumn('customer_name', 'customers.customer_name $1')
            ->orderColumn('location_address', 'locations.address $1')
            ->orderColumn('invoice_id', 'sells.invoice_id $1')
            ->orderColumn('date', 'sells.date $1, sells.id $1')
            ->orderColumn('total_price', 'sells.total_price $1')
            ->orderColumn('paid_amount', 'sells.paid_amount $1')
            ->orderColumn('due_amount', 'due_amount $1')
            ->filterColumn('customer_name', function ($q, $keyword) {
                $q->where('customers.customer_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('location_address', function ($q, $keyword) {
                $q->where('locations.address', 'like', "%{$keyword}%");
            })
            ->filterColumn('invoice_id', function ($q, $keyword) {
                $q->where('sells.invoice_id', 'like', "%{$keyword}%");
            })
            ->filterColumn('date', function ($q, $keyword) {
                $q->where('sells.date', 'like', "%{$keyword}%");
            })
            ->filter(function ($q) use ($request) {
                $search = trim((string) $request->input('search.value', ''));
                if ($search === '') {
                    return;
                }

                $like = '%' . $search . '%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('customers.customer_name', 'like', $like)
                        ->orWhere('locations.address', 'like', $like)
                        ->orWhere('sells.invoice_id', 'like', $like)
                        ->orWhere('sells.date', 'like', $like);
                });
            }, false)
            ->addColumn('action', function ($row) {
                return
                    '<div class="action-btns">
                        <a href="' . route('sell.show', $row->id) . '" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="' . route('sell.edit', $row->id) . '" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
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
}
