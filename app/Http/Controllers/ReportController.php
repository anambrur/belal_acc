<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\Location;
use App\Models\Road;
use App\Services\CustomerDueReportService;
use App\Services\ExpenseReportService;
use App\Services\ProfitLossReportService;
use App\Services\SupplierDueReportService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function customerDueList()
    {
        $roads = Road::all();
        $address = Location::all();

        return view('report.customerDueReport', compact('roads', 'address'));
    }

    public function customerDueReport(Request $request, CustomerDueReportService $service)
    {
        [$startDate, $endDate] = $service->parseDateRange($request);
        $query = $service->baseQuery($startDate, $endDate);
        $service->applyFilters($query, $request);
        $summary = $service->totals($query);

        return DataTables::of($query)
            ->orderColumn('customer_name', 'customers.customer_name $1')
            ->orderColumn('phone', 'customers.phone $1')
            ->orderColumn('road_name', 'roads.name $1')
            ->orderColumn('location_address', 'locations.address $1')
            ->orderColumn('sell_total', 'sell_total $1')
            ->orderColumn('return_total', 'return_total $1')
            ->orderColumn('payment_total', 'payment_total $1')
            ->orderColumn('due_total', 'due_total $1')
            ->filter(function () {
                // Filters (including search) already applied via CustomerDueReportService.
            }, false)
            ->addColumn('status', function ($row) {
                return $this->dueStatusBadge((float) $row->due_total);
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="action-btns">
                        <a href="' . route('customer.show', $row->id) . '" title="Ledger">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="' . route('customerPayment', $row->id) . '" title="Payment">
                            <i class="fa fa-money-bill-wave"></i>
                        </a>
                    </div>';
            })
            ->editColumn('sell_total', fn ($row) => number_format((float) $row->sell_total, 2))
            ->editColumn('return_total', fn ($row) => number_format((float) $row->return_total, 2))
            ->editColumn('payment_total', fn ($row) => number_format((float) $row->payment_total, 2))
            ->editColumn('due_total', fn ($row) => number_format((float) $row->due_total, 2))
            ->rawColumns(['action', 'status'])
            ->with(['summary' => $summary])
            ->make(true);
    }

    public function supplierDueList()
    {
        $roads = Road::all();
        $address = Location::all();

        return view('report.supplierDueReport', compact('roads', 'address'));
    }

    public function supplierDueReport(Request $request, SupplierDueReportService $service)
    {
        [$startDate, $endDate] = $service->parseDateRange($request);
        $query = $service->baseQuery($startDate, $endDate);
        $service->applyFilters($query, $request);
        $summary = $service->totals($query);

        return DataTables::of($query)
            ->orderColumn('supplier_name', 'suppliers.supplier_name $1')
            ->orderColumn('phone', 'suppliers.phone $1')
            ->orderColumn('road_name', 'roads.name $1')
            ->orderColumn('location_address', 'locations.address $1')
            ->orderColumn('purchase_total', 'purchase_total $1')
            ->orderColumn('return_total', 'return_total $1')
            ->orderColumn('payment_total', 'payment_total $1')
            ->orderColumn('due_total', 'due_total $1')
            ->filter(function () {
                // Filters (including search) already applied via SupplierDueReportService.
            }, false)
            ->addColumn('status', function ($row) {
                return $this->dueStatusBadge((float) $row->due_total);
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="action-btns">
                        <a href="' . route('supplier.show', $row->id) . '" title="Ledger">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="' . route('supplierPayment', $row->id) . '" title="Payment">
                            <i class="fa fa-money-bill-wave"></i>
                        </a>
                    </div>';
            })
            ->editColumn('purchase_total', fn ($row) => number_format((float) $row->purchase_total, 2))
            ->editColumn('return_total', fn ($row) => number_format((float) $row->return_total, 2))
            ->editColumn('payment_total', fn ($row) => number_format((float) $row->payment_total, 2))
            ->editColumn('due_total', fn ($row) => number_format((float) $row->due_total, 2))
            ->rawColumns(['action', 'status'])
            ->with(['summary' => $summary])
            ->make(true);
    }

    public function expenseReportList()
    {
        $categories = ExpenseCategory::all();

        return view('report.expenseReport', compact('categories'));
    }

    public function expenseReport(Request $request, ExpenseReportService $service)
    {
        $query = $service->baseQuery();
        $service->applyFilters($query, $request);
        $summary = $service->totals($query);

        return DataTables::of($query)
            ->orderColumn('date', 'expenses.date $1, expenses.id $1')
            ->orderColumn('category_name', 'expense_categories.expense_category $1')
            ->orderColumn('amount', 'expenses.amount $1')
            ->orderColumn('description', 'expenses.description $1')
            ->filter(function () {
                // Filters (including search) already applied via ExpenseReportService.
            }, false)
            ->addColumn('action', function ($row) {
                return
                    '<div class="action-btns">
                        <a href="' . route('expense.edit', $row->id) . '" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>';
            })
            ->editColumn('amount', fn ($row) => number_format((float) $row->amount, 2))
            ->editColumn('description', fn ($row) => $row->description ?: '—')
            ->editColumn('category_name', fn ($row) => $row->category_name ?: '—')
            ->rawColumns(['action'])
            ->with(['summary' => $summary])
            ->make(true);
    }

    public function profitLossList()
    {
        return view('report.profitLossReport');
    }

    public function profitLossReport(Request $request, ProfitLossReportService $service)
    {
        return response()->json($service->summary($request));
    }

    private function dueStatusBadge(float $due): string
    {
        if ($due > 0) {
            return '<span class="status-pill is-unpaid">Due</span>';
        }

        if ($due < 0) {
            return '<span class="status-pill is-partial">Advance</span>';
        }

        return '<span class="status-pill is-paid">Cleared</span>';
    }
}
