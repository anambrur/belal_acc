@extends('layout')

@section('title')
    Dashboard
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/report-pages.css') }}" />
@endpush

@section('content')
    <div class="page-wrapper report-page sell-report-page dashboard-page">
        <div class="content">
            <div class="report-hero">
                <div>
                    <h4>Dashboard</h4>
                    <p>Business overview — cash, dues, sales, and collections at a glance</p>
                </div>
                <span class="period-chip">
                    <i class="fa fa-calendar-alt"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>

            <div class="kpi-section">
                <div class="kpi-section-head">
                    <p class="kpi-section-label">Overview</p>
                </div>
                <div class="kpi-grid kpi-grid-4">
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Cash in hand</div>
                            <span class="kpi-icon"><i class="fa fa-wallet"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['cash_in_hand'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Customer due</div>
                            <span class="kpi-icon"><i class="fa fa-exclamation-circle"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['customer_due'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Supplier due</div>
                            <span class="kpi-icon"><i class="fa fa-truck"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['supplier_due'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-total">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Today's sales</div>
                            <span class="kpi-icon"><i class="fa fa-chart-line"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['today_sales'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="kpi-section">
                <div class="kpi-section-head">
                    <p class="kpi-section-label">Counts</p>
                </div>
                <div class="kpi-grid kpi-grid-4">
                    <div class="kpi-card kpi-invoices">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Customers</div>
                            <span class="kpi-icon"><i class="fa fa-users"></i></span>
                        </div>
                        <div class="kpi-value">{{ number_format($summary['customer_count']) }}</div>
                    </div>
                    <div class="kpi-card kpi-total">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Suppliers</div>
                            <span class="kpi-icon"><i class="fa fa-user-check"></i></span>
                        </div>
                        <div class="kpi-value">{{ number_format($summary['supplier_count']) }}</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Purchase invoices</div>
                            <span class="kpi-icon"><i class="fa fa-file-invoice"></i></span>
                        </div>
                        <div class="kpi-value">{{ number_format($summary['purchase_invoice_count']) }}</div>
                    </div>
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Sales invoices</div>
                            <span class="kpi-icon"><i class="fa fa-file"></i></span>
                        </div>
                        <div class="kpi-value">{{ number_format($summary['sales_invoice_count']) }}</div>
                    </div>
                </div>
            </div>

            <div class="kpi-section">
                <div class="kpi-section-head">
                    <p class="kpi-section-label">This month</p>
                </div>
                <div class="kpi-grid kpi-grid-3">
                    <div class="kpi-card kpi-total">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Sales</div>
                            <span class="kpi-icon"><i class="fa fa-chart-line"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['month_sales'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Collections</div>
                            <span class="kpi-icon"><i class="fa fa-check-circle"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['month_collections'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Expenses</div>
                            <span class="kpi-icon"><i class="fa fa-receipt"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['month_expenses'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="kpi-section">
                <div class="kpi-section-head">
                    <p class="kpi-section-label">Today &amp; liabilities</p>
                </div>
                <div class="kpi-grid kpi-grid-4">
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Today's collections</div>
                            <span class="kpi-icon"><i class="fa fa-hand-holding-usd"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['today_collections'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Today's supplier payments</div>
                            <span class="kpi-icon"><i class="fa fa-money-bill-wave"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['today_supplier_payments'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Loan outstanding</div>
                            <span class="kpi-icon"><i class="fa fa-landmark"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['loan_outstanding'], 2) }}</div>
                    </div>
                    <div class="kpi-card kpi-invoices">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Accounts balance</div>
                            <span class="kpi-icon"><i class="fa fa-university"></i></span>
                        </div>
                        <div class="kpi-value">৳{{ number_format($summary['accounts_total'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="row dashboard-panel-row">
                <div class="col-lg-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Purchase &amp; Sales</h5>
                            <div class="graph-sets">
                                <ul>
                                    <li><span>Sales</span></li>
                                    <li><span>Purchase</span></li>
                                </ul>
                                <div class="dropdown">
                                    <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                        id="salesYearButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="sales_year_label">{{ $summary['current_year'] }}</span>
                                        <img src="{{ asset('assets/img/icons/dropdown.svg') }}" alt="img" class="ms-2" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="salesYearButton">
                                        @foreach ($summary['chart_years'] as $year)
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item sales-year-option"
                                                    data-year="{{ $year }}">{{ $year }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="sales_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0">Sales Payment Status</h4>
                        </div>
                        <div class="card-body">
                            <div id="payment_status_chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row dashboard-panel-row">
                <div class="col-lg-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-0">Collections · Last 30 Days</h5>
                        </div>
                        <div class="card-body">
                            <div id="collections_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Top Customers by Due</h4>
                            <a href="{{ route('customerDueList') }}" class="btn btn-sm btn-outline-primary">View all</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Customer</th>
                                            <th class="text-end">Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($summary['top_due_customers'] as $customer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('customer.show', $customer->id) }}">
                                                        {{ $customer->customer_name }}
                                                    </a>
                                                    @if ($customer->phone)
                                                        <div class="text-muted small">{{ $customer->phone }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-end">৳{{ number_format((float) $customer->due_total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No outstanding dues</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row dashboard-panel-row">
                <div class="col-lg-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Top Suppliers by Due</h4>
                            <a href="{{ route('supplierDueList') }}" class="btn btn-sm btn-outline-primary">View all</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Supplier</th>
                                            <th class="text-end">Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($summary['top_due_suppliers'] as $supplier)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('supplier.show', $supplier->id) }}">
                                                        {{ $supplier->supplier_name }}
                                                    </a>
                                                    @if ($supplier->phone)
                                                        <div class="text-muted small">{{ $supplier->phone }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-end">৳{{ number_format((float) $supplier->due_total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No outstanding dues</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Account Balances</h4>
                            <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Account</th>
                                            <th>Number</th>
                                            <th class="text-end">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($summary['account_balances'] as $account)
                                            <tr>
                                                <td>
                                                    {{ $account->bank_name }}
                                                    @if ($account->branch_name)
                                                        <div class="text-muted small">{{ $account->branch_name }}</div>
                                                    @endif
                                                </td>
                                                <td>{{ $account->account_number ?: '—' }}</td>
                                                <td class="text-end">৳{{ number_format((float) $account->amount, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No accounts found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row dashboard-panel-row">
                <div class="col-lg-6 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-0">Expense Trend · Last 6 Months</h5>
                        </div>
                        <div class="card-body">
                            <div id="expense_trend_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-0">Sales by Road · This Month</h5>
                        </div>
                        <div class="card-body">
                            <div id="road_sales_chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var salesChart = null;
            var currentYear = {{ (int) $summary['current_year'] }};

            function formatMoney(val) {
                return '৳ ' + Number(val || 0).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function loadSalesChart(year) {
                $.ajax({
                    url: "{{ route('getSalesData') }}",
                    method: 'GET',
                    data: { year: year },
                    success: function(response) {
                        if (!salesChart) {
                            return;
                        }
                        salesChart.updateSeries([
                            { name: 'Sales', data: response.sales },
                            { name: 'Purchase', data: response.purchase }
                        ]);
                        salesChart.updateOptions({
                            xaxis: { categories: response.categories }
                        });
                        $('#sales_year_label').text(response.year || year);
                    },
                    error: function() {
                        console.error('Error fetching sales chart data');
                    }
                });
            }

            if ($('#sales_chart').length > 0) {
                salesChart = new ApexCharts(document.querySelector('#sales_chart'), {
                    colors: ['#28C76F', '#FF9F43'],
                    series: [
                        { name: 'Sales', type: 'column', data: [] },
                        { name: 'Purchase', type: 'column', data: [] }
                    ],
                    chart: {
                        type: 'bar',
                        fontFamily: 'Poppins, sans-serif',
                        height: 260,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '60%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    xaxis: { categories: [] },
                    yaxis: {
                        labels: {
                            formatter: function(val) {
                                return Number(val || 0).toLocaleString();
                            }
                        }
                    },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return formatMoney(val);
                            }
                        }
                    }
                });
                salesChart.render();
                loadSalesChart(currentYear);
            }

            $('.sales-year-option').on('click', function() {
                var year = $(this).data('year');
                currentYear = year;
                loadSalesChart(year);
            });

            if ($('#collections_chart').length > 0) {
                var collectionsChart = new ApexCharts(document.querySelector('#collections_chart'), {
                    colors: ['#2563eb'],
                    series: [{ name: 'Collections', data: [] }],
                    chart: {
                        type: 'area',
                        height: 220,
                        fontFamily: 'Poppins, sans-serif',
                        toolbar: { show: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.35,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: { categories: [] },
                    yaxis: {
                        labels: {
                            formatter: function(val) {
                                return Number(val || 0).toLocaleString();
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return formatMoney(val);
                            }
                        }
                    }
                });
                collectionsChart.render();

                $.ajax({
                    url: "{{ route('dashboard.collectionsChart') }}",
                    method: 'GET',
                    success: function(response) {
                        collectionsChart.updateSeries([{
                            name: 'Collections',
                            data: response.series
                        }]);
                        collectionsChart.updateOptions({
                            xaxis: { categories: response.categories }
                        });
                    },
                    error: function() {
                        console.error('Error fetching collections chart data');
                    }
                });
            }

            if ($('#payment_status_chart').length > 0) {
                var statusChart = new ApexCharts(document.querySelector('#payment_status_chart'), {
                    colors: ['#28C76F', '#FF9F43', '#EA5455'],
                    series: [],
                    labels: ['Paid', 'Partial', 'Unpaid'],
                    chart: {
                        type: 'donut',
                        height: 260,
                        fontFamily: 'Poppins, sans-serif'
                    },
                    legend: { position: 'bottom' },
                    dataLabels: { enabled: true },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return Number(val || 0).toLocaleString() + ' invoices';
                            }
                        }
                    }
                });
                statusChart.render();

                $.ajax({
                    url: "{{ route('dashboard.paymentStatusChart') }}",
                    method: 'GET',
                    success: function(response) {
                        statusChart.updateOptions({ labels: response.labels });
                        statusChart.updateSeries(response.series);
                    },
                    error: function() {
                        console.error('Error fetching payment status chart data');
                    }
                });
            }

            if ($('#expense_trend_chart').length > 0) {
                var expenseChart = new ApexCharts(document.querySelector('#expense_trend_chart'), {
                    colors: ['#EA5455'],
                    series: [{ name: 'Expenses', data: [] }],
                    chart: {
                        type: 'bar',
                        height: 220,
                        fontFamily: 'Poppins, sans-serif',
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '45%'
                        }
                    },
                    dataLabels: { enabled: false },
                    xaxis: { categories: [] },
                    yaxis: {
                        labels: {
                            formatter: function(val) {
                                return Number(val || 0).toLocaleString();
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return formatMoney(val);
                            }
                        }
                    }
                });
                expenseChart.render();

                $.ajax({
                    url: "{{ route('dashboard.expenseTrendChart') }}",
                    method: 'GET',
                    success: function(response) {
                        expenseChart.updateSeries([{
                            name: 'Expenses',
                            data: response.series
                        }]);
                        expenseChart.updateOptions({
                            xaxis: { categories: response.categories }
                        });
                    },
                    error: function() {
                        console.error('Error fetching expense trend chart data');
                    }
                });
            }

            if ($('#road_sales_chart').length > 0) {
                var roadSalesChart = new ApexCharts(document.querySelector('#road_sales_chart'), {
                    colors: ['#7c3aed'],
                    series: [{ name: 'Sales', data: [] }],
                    chart: {
                        type: 'bar',
                        height: 220,
                        fontFamily: 'Poppins, sans-serif',
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 4,
                            barHeight: '60%'
                        }
                    },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: [],
                        labels: {
                            formatter: function(val) {
                                return Number(val || 0).toLocaleString();
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return formatMoney(val);
                            }
                        }
                    }
                });
                roadSalesChart.render();

                $.ajax({
                    url: "{{ route('dashboard.roadSalesChart') }}",
                    method: 'GET',
                    success: function(response) {
                        roadSalesChart.updateSeries([{
                            name: 'Sales',
                            data: response.series
                        }]);
                        roadSalesChart.updateOptions({
                            xaxis: { categories: response.categories }
                        });
                    },
                    error: function() {
                        console.error('Error fetching road sales chart data');
                    }
                });
            }
        });
    </script>
@endpush
