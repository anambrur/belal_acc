@extends('layout')

@section('title')
    Profit & Loss
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/report-pages.css') }}" />
@endpush

@section('content')
    <div class="page-wrapper report-page sell-report-page pnl-report-page">
        <div class="content">
            <div class="report-hero">
                <div>
                    <h4>Profit &amp; Loss</h4>
                    <p>Period summary of net sales, purchases, expenses, and profit</p>
                </div>
                <span class="period-chip" id="active_range_label">
                    <i class="fa fa-calendar-alt"></i>
                    Period: All time
                </span>
            </div>

            <div class="report-shell">
                <div class="filter-grid" style="grid-template-columns: minmax(0, 1fr);">
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
                        <button type="button" class="date-preset is-active" data-preset="this_month">This month</button>
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
                    <p class="kpi-section-label">Key results · filtered period</p>
                </div>
                <div class="kpi-grid">
                    <div class="kpi-card kpi-total">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Net sales</div>
                            <span class="kpi-icon"><i class="fa fa-chart-line"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_net_sales">0.00</div>
                    </div>
                    <div class="kpi-card kpi-discount">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Net purchases</div>
                            <span class="kpi-icon"><i class="fa fa-shopping-cart"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_net_purchases">0.00</div>
                    </div>
                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Expenses</div>
                            <span class="kpi-icon"><i class="fa fa-wallet"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_expenses">0.00</div>
                    </div>
                    <div class="kpi-card kpi-paid">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Net profit</div>
                            <span class="kpi-icon"><i class="fa fa-coins"></i></span>
                        </div>
                        <div class="kpi-value" id="kpi_net_profit">0.00</div>
                    </div>
                </div>
            </div>

            <div class="report-table-card pnl-statement-card">
                <div class="report-table-head">
                    <div>
                        <h6>Profit &amp; loss statement</h6>
                        <p class="hint">Net sales − purchases − expenses</p>
                    </div>
                </div>
                <div class="report-table-body">
                    <table class="table pnl-statement">
                        <tbody>
                            <tr>
                                <td>Sales (gross)</td>
                                <td class="text-end" id="row_sales_total">0.00</td>
                            </tr>
                            <tr>
                                <td>Sales discount</td>
                                <td class="text-end" id="row_sales_discount">0.00</td>
                            </tr>
                            <tr>
                                <td>Sales returns</td>
                                <td class="text-end" id="row_sales_returns">0.00</td>
                            </tr>
                            <tr class="pnl-subtotal">
                                <td>Net sales</td>
                                <td class="text-end" id="row_net_sales">0.00</td>
                            </tr>
                            <tr>
                                <td>Purchases (gross)</td>
                                <td class="text-end" id="row_purchases_total">0.00</td>
                            </tr>
                            <tr>
                                <td>Purchase returns</td>
                                <td class="text-end" id="row_purchase_returns">0.00</td>
                            </tr>
                            <tr class="pnl-subtotal">
                                <td>Net purchases</td>
                                <td class="text-end" id="row_net_purchases">0.00</td>
                            </tr>
                            <tr class="pnl-subtotal">
                                <td>Gross profit</td>
                                <td class="text-end" id="row_gross_profit">0.00</td>
                            </tr>
                            <tr>
                                <td>Operating expenses</td>
                                <td class="text-end" id="row_expenses_total">0.00</td>
                            </tr>
                            <tr class="pnl-result">
                                <td>Net profit / (loss)</td>
                                <td class="text-end" id="row_net_profit">0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="report-table-card">
                <div class="report-table-head">
                    <div>
                        <h6>Expenses by category</h6>
                        <p class="hint">Breakdown of operating expenses in the selected period</p>
                    </div>
                </div>
                <div class="report-table-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="expenseCategoryTable">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Entries</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="expense_category_body">
                                <tr>
                                    <td colspan="3" class="text-muted text-center">No expenses in this period</td>
                                </tr>
                            </tbody>
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
            var activePreset = 'this_month';

            function formatMoney(value) {
                return Number(value || 0).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
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

            function renderExpenseCategories(rows) {
                var $body = $('#expense_category_body');
                $body.empty();

                if (!rows || !rows.length) {
                    $body.append('<tr><td colspan="3" class="text-muted text-center">No expenses in this period</td></tr>');
                    return;
                }

                rows.forEach(function(row) {
                    $body.append(
                        '<tr>' +
                        '<td>' + (row.category_name || 'Uncategorized') + '</td>' +
                        '<td class="text-end">' + Number(row.expense_count || 0).toLocaleString() + '</td>' +
                        '<td class="text-end">' + formatMoney(row.total_amount) + '</td>' +
                        '</tr>'
                    );
                });
            }

            function loadSummary() {
                $.ajax({
                    url: "{{ route('profitLossReport') }}",
                    data: { date_range: filter_date },
                    success: function(data) {
                        $('#kpi_net_sales').text(formatMoney(data.net_sales));
                        $('#kpi_net_purchases').text(formatMoney(data.net_purchases));
                        $('#kpi_expenses').text(formatMoney(data.expenses_total));
                        $('#kpi_net_profit').text(formatMoney(data.net_profit));

                        $('#row_sales_total').text(formatMoney(data.sales_total));
                        $('#row_sales_discount').text(formatMoney(data.sales_discount));
                        $('#row_sales_returns').text(formatMoney(data.sales_returns));
                        $('#row_net_sales').text(formatMoney(data.net_sales));
                        $('#row_purchases_total').text(formatMoney(data.purchases_total));
                        $('#row_purchase_returns').text(formatMoney(data.purchase_returns));
                        $('#row_net_purchases').text(formatMoney(data.net_purchases));
                        $('#row_gross_profit').text(formatMoney(data.gross_profit));
                        $('#row_expenses_total').text(formatMoney(data.expenses_total));
                        $('#row_net_profit').text(formatMoney(data.net_profit));

                        var $result = $('#row_net_profit').closest('tr');
                        $result.toggleClass('is-loss', Number(data.net_profit) < 0);
                        $result.toggleClass('is-profit', Number(data.net_profit) >= 0);

                        renderExpenseCategories(data.expense_by_category);
                    }
                });
            }

            $('#daterange').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: { format: 'YYYY-MM-DD', cancelLabel: 'Clear' }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                applyDateRange(picker.startDate, picker.endDate, '');
                loadSummary();
            });

            $('#daterange').on('cancel.daterangepicker', function() {
                filter_date = '';
                activePreset = '';
                $(this).val('');
                $('.date-preset').removeClass('is-active');
                $('#active_range_label').html('<i class="fa fa-calendar-alt"></i> Period: All time');
                loadSummary();
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
                loadSummary();
            });

            $('#reset_filters').on('click', function() {
                filter_date = '';
                activePreset = '';
                $('#daterange').val('');
                $('.date-preset').removeClass('is-active');
                $('#active_range_label').html('<i class="fa fa-calendar-alt"></i> Period: All time');
                loadSummary();
            });

            applyDateRange(moment().startOf('month'), moment().endOf('month'), 'this_month');
            loadSummary();
        });
    </script>
@endpush
