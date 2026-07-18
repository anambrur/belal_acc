<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Road;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountingController extends Controller
{
    public function blanceSheetView()
    {
        return view('accounting.balanceSheet');
    }

    public function blanceSheetTable()
    {
        $sl = 0;
        $data = Sell::orderBy('created_at', 'DESC')->with('customer')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })
            ->make(true);
    }

    public function collectionList()
    {
        $roads = Road::all();
        $locations = Location::all();

        return view('accounting.collectionList', compact('roads', 'locations'));
    }

    /**
     * Collection list DataTables — server-side joins (fast).
     */
    public function collectionListData(Request $request)
    {
        $query = DB::table('payments')
            ->join('customers', function ($join) {
                $join->on('customers.id', '=', 'payments.payable_id')
                    ->where('payments.payable_type', '=', Customer::class);
            })
            ->leftJoin('roads', 'roads.id', '=', 'customers.road_id')
            ->leftJoin('locations', 'locations.id', '=', 'customers.location_id')
            ->where('payments.payment_for', 'cus_due_return')
            ->select([
                'payments.id',
                'payments.amount',
                'payments.date',
                'payments.created_at',
                'customers.id as customer_id',
                'customers.customer_name',
                'roads.name as road_name',
                'locations.address as location_address',
            ]);

        if ($request->filled('filter_road') && $request->filter_road !== 'All Road') {
            $query->where('customers.road_id', $request->filter_road);
        }

        if ($request->filled('filter_address') && $request->filter_address !== 'All Address') {
            $query->where('customers.location_id', $request->filter_address);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('payments.date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        return DataTables::of($query)
            ->orderColumn('customer_name', 'customers.customer_name $1')
            ->orderColumn('road_name', 'roads.name $1')
            ->orderColumn('location_address', 'locations.address $1')
            ->orderColumn('amount', 'payments.amount $1')
            ->orderColumn('date', 'payments.date $1, payments.id $1')
            ->filterColumn('customer_name', function ($q, $keyword) {
                $q->where('customers.customer_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('road_name', function ($q, $keyword) {
                $q->where('roads.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('location_address', function ($q, $keyword) {
                $q->where('locations.address', 'like', "%{$keyword}%");
            })
            ->filter(function ($q) use ($request) {
                $search = trim((string) $request->input('search.value', ''));
                if ($search === '') {
                    return;
                }

                $like = '%' . $search . '%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('customers.customer_name', 'like', $like)
                        ->orWhere('roads.name', 'like', $like)
                        ->orWhere('locations.address', 'like', $like)
                        ->orWhere('payments.amount', 'like', $like)
                        ->orWhere('payments.date', 'like', $like);
                });
            }, false)
            ->editColumn('amount', fn ($row) => number_format((float) $row->amount, 2))
            ->editColumn('date', function ($row) {
                return $row->date
                    ? \Carbon\Carbon::parse($row->date)->format('Y-m-d')
                    : '';
            })
            ->make(true);
    }
}
