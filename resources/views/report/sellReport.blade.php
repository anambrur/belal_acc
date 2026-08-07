@extends('layout')

@section('title')
    Sales Report
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/report-pages.css') }}" />
@endpush

@section('content')
    <div class="page-wrapper report-page sell-report-page">
        <div class="content">
            <div class="report-hero">
                <div>
                    <h4>Sales Report</h4>
                    <p>Filter sales invoices and review period totals at a glance</p>
                </div>
                <span class="period-chip" id="active_range_label">
                    <i class="fa fa-calendar-alt"></i>
                    Period: All time
                </span>
            </div>

            <div class="report-shell">
                <div class="filter-grid">
                    <div class="filter-field">
                        <label for="filter_customer">Customer</label>
                        <select class="select" id="filter_customer">
                            <option>All Customer</option>
                            @foreach ($customer as $d)
                                <option value="{{ $d->id }}">{{ $d->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="filter_address">Address</label>
                        <select class="select" id="filter_address">
                            <option>All Address</option>
                            @foreach ($address as $d)
                                <option value="{{ $d->id }}">{{ $d->address }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="filter_status">Payment status</label>
                        <select class="select" id="filter_status">
                            <option>All Status</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="partial">Partial</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="daterange">Date range</label>
                        <div class="date-wrap">
                            <i class="fa fa-calendar-alt date-icon"></i>
                            <input type="text" class="form-control" id="daterange" placeholder="Select date range"
                                autocomplete="off" readonly>
                        </div>
                    </div>
                </div>

                <div class="filter-toolbar">
                    <div class="date-presets">
                        <button type="button" class="date-preset" data-preset="today">Today</button>
                        <button type="button" class="date-preset" data-preset="this_month">This month</button>
                        <button type="button" class="date-preset" data-preset="last_month">Last month</button>
                        <button type="button" class="date-preset" data-preset="this_year">This year</button>
                    </div>
                    <button type="button" class="btn btn-reset-filters" id="reset_filters">
                        <i class="fa fa-undo"></i> Reset filters
                    </button>
                </div>
            </div>

            <div class="kpi-section">
                <div class="kpi-section-head">
                    <p class="kpi-section-label">Grand total · filtered period</p>
                </div>
                <div class="kpi-grid">
                    <div class="kpi-card kpi-invoices">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Invoices</div>
                            <span class="kpi-icon"><i class="fa fa-file-invoice"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_invoice_count">0</div>
                        <div class="kpi-hint" id="kpi_status_counts">Paid 0 · Unpaid 0 · Partial 0</div>
                    </div>
                    <div class="kpi-card kpi-total">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Total sales</div>
                            <span class="kpi-icon"><i class="fa fa-chart-line"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_sales">0.00</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Discount</div>
                            <span class="kpi-icon"><i class="fa fa-percent"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_discount">0.00</div>
                    </div>
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Paid</div>
                            <span class="kpi-icon"><i class="fa fa-check-circle"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_paid">0.00</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Due</div>
                            <span class="kpi-icon"><i class="fa fa-exclamation-circle"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_due">0.00</div>
                    </div>
                </div>
            </div>

            <div class="report-table-card">
                <div class="report-table-head">
                    <div>
                        <h6>Sales invoices</h6>
                        <p class="hint">Newest first · page totals in the footer</p>
                    </div>
                </div>
                <div class="report-table-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Due</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                    <th>Page total</th>
                                    <th id="total-price-footer" class="text-end"></th>
                                    <th id="discount-footer" class="text-end"></th>
                                    <th id="paid-amount-footer" class="text-end"></th>
                                    <th id="due-footer" class="text-end"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/daterangepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/js/daterangepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var filter_date = '';
            var activePreset = '';

            function formatMoney(value) {
                return Number(value || 0).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateSummary(summary) {
                summary = summary || {};
                $('#kpi_invoice_count').text(Number(summary.invoice_count || 0).toLocaleString());
                $('#kpi_total_sales').text(formatMoney(summary.total_sales));
                $('#kpi_total_discount').text(formatMoney(summary.total_discount));
                $('#kpi_total_paid').text(formatMoney(summary.total_paid));
                $('#kpi_total_due').text(formatMoney(summary.total_due));
                $('#kpi_status_counts').text(
                    'Paid ' + (summary.paid_count || 0) +
                    ' · Unpaid ' + (summary.unpaid_count || 0) +
                    ' · Partial ' + (summary.partial_count || 0)
                );
            }

            function setPeriodLabel(start, end) {
                $('#active_range_label').html(
                    '<i class="fa fa-calendar-alt"></i> Period: ' +
                    start.format('DD MMM YYYY') + ' — ' + end.format('DD MMM YYYY')
                );
            }

            function applyDateRange(start, end, preset) {
                filter_date = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                $('#daterange').val(filter_date);
                var picker = $('#daterange').data('daterangepicker');
                if (picker) {
                    picker.setStartDate(start);
                    picker.setEndDate(end);
                }
                activePreset = preset || '';
                $('.date-preset').removeClass('is-active');
                if (activePreset) {
                    $('.date-preset[data-preset="' + activePreset + '"]').addClass('is-active');
                }
                setPeriodLabel(start, end);
            }

            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 400,
                pageLength: 25,
                lengthMenu: DtListHelpers.lengthMenu,
                order: [[1, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search customer, invoice, address…',
                    processing: 'Loading sell report…',
                    lengthMenu: 'Show _MENU_ entries'
                },
                ajax: {
                    url: "{{ route('sellsReport') }}",
                    data: function(d) {
                        d.filter_customer = $('#filter_customer').val();
                        d.filter_address = $('#filter_address').val();
                        d.filter_status = $('#filter_status').val();
                        d.date_range = filter_date;
                    },
                    dataSrc: function(json) {
                        updateSummary(json.summary);
                        return json.data;
                    }
                },
                columns: [{
                        data: null,
                        name: 'sl',
                        orderable: false,
                        searchable: false,
                        className: 'text-muted',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'invoice_id', name: 'invoice_id' },
                    { data: 'date', name: 'date' },
                    { data: 'customer_name', name: 'customer_name', defaultContent: '—' },
                    { data: 'location_address', name: 'location_address', defaultContent: '—' },
                    { data: 'total_price', name: 'total_price', searchable: false, className: 'text-end' },
                    { data: 'total_discount', name: 'total_discount', searchable: false, className: 'text-end' },
                    { data: 'paid_amount', name: 'paid_amount', searchable: false, className: 'text-end' },
                    { data: 'due_amount', name: 'due_amount', searchable: false, className: 'text-end' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: DtListHelpers.reportDom,
                buttons: DtListHelpers.makeExportButtons({
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    docTitle: 'SELL REPORT',
                    getFilterSummary: function() {
                        var parts = [];
                        var customer = $('#filter_customer').val();
                        var address = $('#filter_address').val();
                        var status = $('#filter_status').val();
                        if (customer && customer !== 'All Customer' && customer !== '') {
                            parts.push($('#filter_customer option:selected').text().trim());
                        }
                        if (address && address !== 'All Address' && address !== '') {
                            parts.push($('#filter_address option:selected').text().trim());
                        }
                        if (status && status !== 'All Status' && status !== '') {
                            parts.push($('#filter_status option:selected').text().trim());
                        }
                        if (typeof filter_date !== 'undefined' && filter_date) {
                            parts.push(filter_date);
                        }
                        return parts.join(' · ');
                    }
                }),
                footerCallback: function() {
                    var api = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ? i : 0;
                    };

                    var total = api.column(6, { page: 'current' }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var discount = api.column(7, { page: 'current' }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var paid = api.column(8, { page: 'current' }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var due = api.column(9, { page: 'current' }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    $(api.column(6).footer()).html(total.toFixed(2));
                    $(api.column(7).footer()).html(discount.toFixed(2));
                    $(api.column(8).footer()).html(paid.toFixed(2));
                    $(api.column(9).footer()).html(due.toFixed(2));
                }
            });

            $('.select').select2({ width: '100%' });

            $('#filter_customer, #filter_address, #filter_status').on('change', function() {
                table.draw();
            });

            $('#daterange').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: { format: 'YYYY-MM-DD', cancelLabel: 'Clear' }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                applyDateRange(picker.startDate, picker.endDate, '');
                table.draw();
            });

            $('#daterange').on('cancel.daterangepicker', function() {
                filter_date = '';
                activePreset = '';
                $(this).val('');
                $('.date-preset').removeClass('is-active');
                $('#active_range_label').html('<i class="fa fa-calendar-alt"></i> Period: All time');
                table.draw();
            });

            $('.date-preset').on('click', function() {
                var preset = $(this).data('preset');
                var start;
                var end = moment();

                if (preset === 'today') {
                    start = moment();
                    end = moment();
                } else if (preset === 'this_month') {
                    start = moment().startOf('month');
                    end = moment().endOf('month');
                } else if (preset === 'last_month') {
                    start = moment().subtract(1, 'month').startOf('month');
                    end = moment().subtract(1, 'month').endOf('month');
                } else if (preset === 'this_year') {
                    start = moment().startOf('year');
                    end = moment().endOf('year');
                } else {
                    return;
                }

                applyDateRange(start, end, preset);
                table.draw();
            });

            $('#reset_filters').on('click', function() {
                filter_date = '';
                activePreset = '';
                $('#daterange').val('');
                $('#filter_customer').val('All Customer').trigger('change.select2');
                $('#filter_address').val('All Address').trigger('change.select2');
                $('#filter_status').val('All Status').trigger('change.select2');
                $('.date-preset').removeClass('is-active');
                $('#active_range_label').html('<i class="fa fa-calendar-alt"></i> Period: All time');
                table.search('').draw();
            });
        });
    </script>
@endpush
