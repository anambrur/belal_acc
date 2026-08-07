@extends('layout')

@section('title')
    Supplier Due Report
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
                    <h4>Supplier Due Report</h4>
                    <p>Payables at a glance — purchases minus returns and payments</p>
                </div>
                <span class="period-chip" id="active_range_label">
                    <i class="fa fa-calendar-alt"></i>
                    Period: All time
                </span>
            </div>

            <div class="report-shell">
                <div class="filter-grid">
                    <div class="filter-field">
                        <label for="filter_road">Road</label>
                        <select class="select" id="filter_road">
                            <option>All Road</option>
                            @foreach ($roads as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
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
                        <label for="filter_status">Due status</label>
                        <select class="select" id="filter_status">
                            <option>All Status</option>
                            <option value="due">Due</option>
                            <option value="cleared">Cleared</option>
                            <option value="advance">Advance</option>
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
                            <div class="kpi-label">Suppliers</div>
                            <span class="kpi-icon"><i class="fa fa-truck"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_supplier_count">0</div>
                        <div class="kpi-hint" id="kpi_status_counts">Due 0 · Cleared 0 · Advance 0</div>
                    </div>
                    <div class="kpi-card kpi-total">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Total purchases</div>
                            <span class="kpi-icon"><i class="fa fa-shopping-cart"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_purchases">0.00</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Returns</div>
                            <span class="kpi-icon"><i class="fa fa-undo"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_returns">0.00</div>
                    </div>
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Payments</div>
                            <span class="kpi-icon"><i class="fa fa-check-circle"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_payments">0.00</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Total due</div>
                            <span class="kpi-icon"><i class="fa fa-exclamation-circle"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_due">0.00</div>
                    </div>
                </div>
            </div>

            <div class="report-table-card">
                <div class="report-table-head">
                    <div>
                        <h6>Supplier payables</h6>
                        <p class="hint">Highest due first · page totals in the footer</p>
                    </div>
                </div>
                <div class="report-table-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Supplier</th>
                                    <th>Phone</th>
                                    <th>Road</th>
                                    <th>Address</th>
                                    <th class="text-end">Purchases</th>
                                    <th class="text-end">Returns</th>
                                    <th class="text-end">Payments</th>
                                    <th class="text-end">Due</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                    <th class="text-end" id="purchases-footer"></th>
                                    <th class="text-end" id="returns-footer"></th>
                                    <th class="text-end" id="payments-footer"></th>
                                    <th class="text-end" id="due-footer"></th>
                                    <th colspan="2"></th>
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
                $('#kpi_supplier_count').text(Number(summary.supplier_count || 0).toLocaleString());
                $('#kpi_total_purchases').text(formatMoney(summary.total_purchases));
                $('#kpi_total_returns').text(formatMoney(summary.total_returns));
                $('#kpi_total_payments').text(formatMoney(summary.total_payments));
                $('#kpi_total_due').text(formatMoney(summary.total_due));
                $('#kpi_status_counts').text(
                    'Due ' + (summary.due_count || 0) +
                    ' · Cleared ' + (summary.cleared_count || 0) +
                    ' · Advance ' + (summary.advance_count || 0)
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
                order: [[8, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search supplier, phone, road…',
                    processing: 'Loading supplier due report…'
                },
                ajax: {
                    url: "{{ route('supplierDueReport') }}",
                    data: function(d) {
                        d.filter_road = $('#filter_road').val();
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
                    { data: 'supplier_name', name: 'supplier_name', defaultContent: '—' },
                    { data: 'phone', name: 'phone', defaultContent: '—' },
                    { data: 'road_name', name: 'road_name', defaultContent: '—' },
                    { data: 'location_address', name: 'location_address', defaultContent: '—' },
                    { data: 'purchase_total', name: 'purchase_total', searchable: false, className: 'text-end' },
                    { data: 'return_total', name: 'return_total', searchable: false, className: 'text-end' },
                    { data: 'payment_total', name: 'payment_total', searchable: false, className: 'text-end' },
                    { data: 'due_total', name: 'due_total', searchable: false, className: 'text-end' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'row align-items-center mx-1 mt-2 mb-3'<'col-sm-12 col-md-5'B><'col-sm-12 col-md-7'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF' },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                    }
                ],
                footerCallback: function() {
                    var api = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ? i : 0;
                    };

                    [5, 6, 7, 8].forEach(function(col) {
                        var total = api.column(col, { page: 'current' }).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        $(api.column(col).footer()).html(total.toFixed(2));
                    });
                }
            });

            $('.select').select2({ width: '100%' });

            $('#filter_road, #filter_address, #filter_status').on('change', function() {
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
                $('#filter_road').val('All Road').trigger('change.select2');
                $('#filter_address').val('All Address').trigger('change.select2');
                $('#filter_status').val('All Status').trigger('change.select2');
                $('.date-preset').removeClass('is-active');
                $('#active_range_label').html('<i class="fa fa-calendar-alt"></i> Period: All time');
                table.search('').draw();
            });
        });
    </script>
@endpush
