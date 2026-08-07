@extends('layout')

@section('title')
    Expense Report
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
                    <h4>Expense Report</h4>
                    <p>Track spending by category and date range</p>
                </div>
                <span class="period-chip" id="active_range_label">
                    <i class="fa fa-calendar-alt"></i>
                    Period: All time
                </span>
            </div>

            <div class="report-shell">
                <div class="filter-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                    <div class="filter-field">
                        <label for="filter_category">Category</label>
                        <select class="select" id="filter_category">
                            <option>All Category</option>
                            @foreach ($categories as $d)
                                <option value="{{ $d->id }}">{{ $d->expense_category }}</option>
                            @endforeach
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
                <div class="kpi-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
                    <div class="kpi-card kpi-invoices">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Entries</div>
                            <span class="kpi-icon"><i class="fa fa-list"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_expense_count">0</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Categories</div>
                            <span class="kpi-icon"><i class="fa fa-tags"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_category_count">0</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Total expenses</div>
                            <span class="kpi-icon"><i class="fa fa-wallet"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_total_amount">0.00</div>
                    </div>
                </div>
            </div>

            <div class="report-table-card">
                <div class="report-table-head">
                    <div>
                        <h6>Expense entries</h6>
                        <p class="hint">Newest first · page totals in the footer</p>
                    </div>
                </div>
                <div class="report-table-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3"></th>
                                    <th>Page total</th>
                                    <th class="text-end" id="amount-footer"></th>
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
                $('#kpi_expense_count').text(Number(summary.expense_count || 0).toLocaleString());
                $('#kpi_category_count').text(Number(summary.category_count || 0).toLocaleString());
                $('#kpi_total_amount').text(formatMoney(summary.total_amount));
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
                    searchPlaceholder: 'Search category, description…',
                    processing: 'Loading expense report…',
                    lengthMenu: 'Show _MENU_ entries'
                },
                ajax: {
                    url: "{{ route('expenseReport') }}",
                    data: function(d) {
                        d.filter_category = $('#filter_category').val();
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
                    { data: 'date', name: 'date' },
                    { data: 'category_name', name: 'category_name', defaultContent: '—' },
                    { data: 'description', name: 'description', defaultContent: '—' },
                    { data: 'amount', name: 'amount', searchable: false, className: 'text-end' },
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
                    columns: [0, 1, 2, 3, 4],
                    docTitle: 'EXPENSE REPORT',
                    getFilterSummary: function() {
                        var parts = [];
                        var category = $('#filter_category').val();
                        if (category && category !== 'All Category' && category !== '') {
                            parts.push($('#filter_category option:selected').text().trim());
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

                    var total = api.column(4, { page: 'current' }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(4).footer()).html(total.toFixed(2));
                }
            });

            $('.select').select2({ width: '100%' });

            $('#filter_category').on('change', function() {
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
                $('#filter_category').val('All Category').trigger('change.select2');
                $('.date-preset').removeClass('is-active');
                $('#active_range_label').html('<i class="fa fa-calendar-alt"></i> Period: All time');
                table.search('').draw();
            });
        });
    </script>
@endpush
