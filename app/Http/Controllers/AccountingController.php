<?php

namespace App\Http\Controllers;

use App\Models\Road;
use App\Models\Sell;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AccountingController extends Controller
{
    public function blanceSheetView()
    {
        return view('accounting.balanceSheet');
    }
    public function blanceSheetTable()
    {
        $data = Sell::orderBy('created_at', 'DESC')->with('customer')->get();

        return Datatables::of($data)
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
    public function collectionListData(Request $request)
    {
        // dd($request->date_range);

        // Start with the base query
        $query = Payment::where('payment_for', 'cus_due_return')
            ->orderBy('created_at', 'DESC')
            ->with(['payable' => function ($query) {
                $query->with('road', 'location');
            }]);

        // Apply road filter if provided
        if ($request->has('filter_road') && $request->filter_road != 'All Road') {
            $query->whereHas('payable', function ($q) use ($request) {
                $q->where('road_id', $request->filter_road);
            });
        }

        // Apply address filter if provided
        if ($request->has('filter_address') && $request->filter_address != 'All Address') {
            $query->whereHas('payable', function ($q) use ($request) {
                $q->where('location_id', $request->filter_address);
            });
        }
        // // Apply filtering based on date range
        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $start_date = $dates[0];
            $end_date = $dates[1];
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }


        // Get the filtered results
        $data = $query->get()->filter(function ($payment) {
            return $payment->payable instanceof Customer;
        });


        return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })

            ->make(true);
    }
}
