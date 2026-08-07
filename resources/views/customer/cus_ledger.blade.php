@extends('layout')

@section('title')
    Customer Ledger
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/report-pages.css') }}" />
@endpush

@section('content')
    @php
        $initials = collect(explode(' ', (string) $customer->customer_name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
        $rangeLabel = $isFiltered
            ? (($startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '…') .
                ' — ' .
                ($endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '…'))
            : 'All time';
    @endphp

    <div class="page-wrapper report-page ledger-page">
        <div class="content">
            <div class="report-hero">
                <div>
                    <h4>Customer Ledger</h4>
                    <p>Account statement and running balance</p>
                </div>
                <div class="hero-actions">
                    <span class="period-chip">
                        <i class="fa fa-calendar-alt"></i>
                        Period: {{ $rangeLabel }}
                    </span>
                    <a href="{{ route('customer.index') }}" class="btn btn-ledger-ghost">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <a href="{{ route('customerPayment', $customer->id) }}" class="btn btn-ledger-ghost">
                        <i class="fa fa-money-bill-wave"></i> Payment
                    </a>
                    <a href="{{ route('customer.ledger.pdf', array_filter([
                            'customer' => $customer->id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                        ])) }}"
                        class="btn btn-ledger-primary">
                        <i class="fa fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <div class="customer-identity">
                <div class="d-flex align-items-center gap-3">
                    <div class="customer-avatar" aria-hidden="true">{{ $initials ?: 'C' }}</div>
                    <div class="customer-meta">
                        <h5>{{ $customer->customer_name }}</h5>
                        <div class="meta-row">
                            <span><i class="fa fa-phone"></i> {{ $customer->phone ?: '—' }}</span>
                            <span>
                                <i class="fa fa-map-marker-alt"></i>
                                {{ $customer->road->name ?? '—' }}, {{ $customer->location->address ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="identity-chips">
                    <span class="period-chip">
                        <i class="fa fa-list"></i>
                        Entries: {{ number_format($ledgerPaginator->total()) }}
                    </span>
                </div>
            </div>

            <div class="report-shell">
                <form action="{{ route('customer.show', $customer->id) }}" method="GET" id="ledger_filter_form">
                    <input type="hidden" name="start_date" id="start_date" value="{{ $startDate ?? '' }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ $endDate ?? '' }}">

                    <div class="filter-grid is-ledger">
                        <div class="filter-field">
                            <label for="daterange">Date range</label>
                            <div class="date-wrap">
                                <i class="fa fa-calendar-alt date-icon"></i>
                                <input type="text" class="form-control" id="daterange"
                                    placeholder="Select date range" autocomplete="off" readonly
                                    value="{{ $isFiltered && $startDate && $endDate ? $startDate . ' - ' . $endDate : '' }}">
                            </div>
                        </div>
                        <div class="filter-field">
                            <label for="per_page">Rows / page</label>
                            <select name="per_page" id="per_page" class="form-control">
                                @foreach ([25, 50, 100, 200] as $option)
                                    <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="filter-toolbar">
                        <div class="date-presets">
                            <button type="button" class="date-preset" data-preset="today">Today</button>
                            <button type="button" class="date-preset" data-preset="this_month">This month</button>
                            <button type="button" class="date-preset" data-preset="last_month">Last month</button>
                            <button type="button" class="date-preset" data-preset="this_year">This year</button>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-filter-apply">
                                <i class="fa fa-filter"></i> Apply
                            </button>
                            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-reset-filters">
                                <i class="fa fa-undo"></i> Reset filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="kpi-section">
                <div class="kpi-section-head">
                    <p class="kpi-section-label">
                        {{ $isFiltered ? 'Period summary · filtered range' : 'Account summary · all time' }}
                    </p>
                </div>
                <div class="kpi-grid is-ledger {{ $isFiltered ? 'is-filtered' : '' }}">
                    @if ($isFiltered)
                        <div class="kpi-card kpi-opening">
                            <div class="kpi-card-top">
                                <div class="kpi-label">Opening balance</div>
                                <span class="kpi-icon"><i class="fa fa-history"></i></span>
                            </div>
                            <div class="kpi-value {{ $openingBalance > 0 ? 'amount-balance-pos' : ($openingBalance < 0 ? 'amount-balance-neg' : '') }}">
                                ৳ {{ number_format($openingBalance, 2) }}
                            </div>
                            <div class="kpi-hint">Before selected period</div>
                        </div>
                    @endif

                    <div class="kpi-card kpi-debit">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Total debit</div>
                            <span class="kpi-icon"><i class="fa fa-arrow-up"></i></span>
                        </div>
                        <div class="kpi-value">৳ {{ number_format($debitTotal, 2) }}</div>
                        <div class="kpi-hint">Sales in period</div>
                    </div>

                    <div class="kpi-card kpi-credit">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Total credit</div>
                            <span class="kpi-icon"><i class="fa fa-arrow-down"></i></span>
                        </div>
                        <div class="kpi-value">৳ {{ number_format($creditTotal, 2) }}</div>
                        <div class="kpi-hint">Payments + returns</div>
                    </div>

                    <div class="kpi-card kpi-due">
                        <div class="kpi-card-top">
                            <div class="kpi-label">Closing due</div>
                            <span class="kpi-icon"><i class="fa fa-exclamation-circle"></i></span>
                        </div>
                        <div class="kpi-value {{ $dueTotal > 0 ? 'amount-balance-pos' : ($dueTotal < 0 ? 'amount-balance-neg' : '') }}">
                            ৳ {{ number_format($dueTotal, 2) }}
                        </div>
                        <div class="kpi-hint">
                            @if ($dueTotal > 0)
                                Customer owes
                            @elseif ($dueTotal < 0)
                                Advance / credit
                            @else
                                Settled
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-table-card">
                <div class="report-table-head">
                    <div>
                        <h6>Ledger statement</h6>
                        <p class="hint">Running balance · newest activity included</p>
                    </div>
                    <span class="period-chip">
                        Page {{ $ledgerPaginator->currentPage() }} of {{ max(1, $ledgerPaginator->lastPage()) }}
                    </span>
                </div>

                <div class="report-table-body">
                    <div class="table-responsive">
                        {{-- Do NOT add class "datanew" — triggers global DataTables --}}
                        <table class="table ledger-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:56px">#</th>
                                    <th>Date</th>
                                    <th>Operation</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                    <th class="text-end">Balance</th>
                                    <th class="text-center" style="width:100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($ledgerPaginator->currentPage() - 1) * $ledgerPaginator->perPage() + 1;
                                @endphp

                                @if ($isFiltered && $ledgerPaginator->onFirstPage() && abs($openingBalance) > 0.00001)
                                    <tr class="row-opening">
                                        <td class="text-muted">—</td>
                                        <td>{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '—' }}</td>
                                        <td>
                                            <span class="type-pill opening">Opening</span>
                                            <span class="op-ref">Brought forward</span>
                                        </td>
                                        <td class="text-end text-muted">—</td>
                                        <td class="text-end text-muted">—</td>
                                        <td class="text-end {{ $openingBalance > 0 ? 'amount-balance-pos' : 'amount-balance-neg' }}">
                                            {{ number_format($openingBalance, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif

                                @forelse ($ledgerPaginator as $d)
                                    <tr>
                                        <td class="text-muted">{{ $counter++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->date)->format('d M Y') }}</td>
                                        <td>
                                            @if ($d->type === 'sale')
                                                <span class="type-pill sale">Sale</span>
                                                <span class="op-ref">{{ $d->reference }}</span>
                                            @elseif ($d->type === 'return')
                                                <span class="type-pill return">Return</span>
                                                <span class="op-ref">{{ $d->reference }}</span>
                                            @else
                                                <span class="type-pill payment">Payment</span>
                                                @if (!empty($d->payment_for))
                                                    <span class="op-ref">Collection</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($d->debit > 0)
                                                <span class="amount-debit">{{ number_format($d->debit, 2) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($d->credit > 0)
                                                <span class="amount-credit">{{ number_format($d->credit, 2) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end {{ $d->running_balance > 0 ? 'amount-balance-pos' : 'amount-balance-neg' }}">
                                            {{ number_format($d->running_balance, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if ($d->type === 'payment')
                                                <div class="action-btns">
                                                    <a href="{{ route('customer.payment.edit', $d->source_id) }}"
                                                        class="btn btn-sm" title="Edit payment">
                                                        <i class="fa fa-pen"></i>
                                                    </a>
                                                    <form action="{{ route('customer.payment.destroy', $d->source_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm" title="Delete payment"
                                                            onclick="return confirm('Delete this payment?')">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <div><i class="fa fa-folder-open"></i></div>
                                                <div class="fw-semibold text-dark mb-1">No ledger entries</div>
                                                <div>
                                                    @if ($isFiltered)
                                                        Nothing found for this date range. Try widening the filter.
                                                    @else
                                                        This customer has no sales, returns, or payments yet.
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if ($ledgerPaginator->total() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Period totals</td>
                                        <td class="text-end amount-debit">{{ number_format($debitTotal, 2) }}</td>
                                        <td class="text-end amount-credit">{{ number_format($creditTotal, 2) }}</td>
                                        <td class="text-end {{ $dueTotal > 0 ? 'amount-balance-pos' : 'amount-balance-neg' }}">
                                            {{ number_format($dueTotal, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                @if ($ledgerPaginator->hasPages())
                    <div class="pagination-bar">
                        <div class="text-muted">
                            Showing
                            <strong>{{ $ledgerPaginator->firstItem() }}</strong>
                            –
                            <strong>{{ $ledgerPaginator->lastItem() }}</strong>
                            of
                            <strong>{{ $ledgerPaginator->total() }}</strong>
                        </div>
                        <div>
                            {{ $ledgerPaginator->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @elseif ($ledgerPaginator->total() > 0)
                    <div class="pagination-bar">
                        <div class="text-muted">Showing all {{ $ledgerPaginator->total() }} entries</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/daterangepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/js/daterangepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var form = $('#ledger_filter_form');
            var startInput = $('#start_date');
            var endInput = $('#end_date');
            var initialStart = startInput.val();
            var initialEnd = endInput.val();

            function setHiddenRange(start, end) {
                startInput.val(start.format('YYYY-MM-DD'));
                endInput.val(end.format('YYYY-MM-DD'));
            }

            function applyDateRange(start, end, preset) {
                setHiddenRange(start, end);
                $('#daterange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

                var picker = $('#daterange').data('daterangepicker');
                if (picker) {
                    picker.setStartDate(start);
                    picker.setEndDate(end);
                }

                $('.date-preset').removeClass('is-active');
                if (preset) {
                    $('.date-preset[data-preset="' + preset + '"]').addClass('is-active');
                }
            }

            var pickerOptions = {
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            };

            if (initialStart && initialEnd) {
                pickerOptions.startDate = moment(initialStart, 'YYYY-MM-DD');
                pickerOptions.endDate = moment(initialEnd, 'YYYY-MM-DD');
                pickerOptions.autoUpdateInput = true;
            }

            $('#daterange').daterangepicker(pickerOptions);

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                applyDateRange(picker.startDate, picker.endDate, '');
                form.trigger('submit');
            });

            $('#daterange').on('cancel.daterangepicker', function() {
                startInput.val('');
                endInput.val('');
                $(this).val('');
                $('.date-preset').removeClass('is-active');
                form.trigger('submit');
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
                form.trigger('submit');
            });
        });
    </script>
@endpush
