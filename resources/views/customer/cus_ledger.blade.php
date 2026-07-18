@extends('layout')

@section('title')
    Customer Ledger
@endsection

@push('css')
    <style>
        .ledger-page {
            --ledger-accent: #ff9f43;
            --ledger-ink: #1f2937;
            --ledger-muted: #6b7280;
            --ledger-line: #e8ecf1;
            --ledger-soft: #f7f8fa;
            --ledger-debit: #e11d48;
            --ledger-credit: #059669;
            --ledger-info: #0284c7;
        }

        .ledger-page .page-header {
            margin-bottom: 1.25rem;
        }

        .ledger-page .page-header .page-title h4 {
            margin-bottom: 0.15rem;
        }

        .ledger-page .page-header .page-title h6 {
            color: var(--ledger-muted);
            font-weight: 400;
        }

        .ledger-page .ledger-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .ledger-page .btn-ledger-back {
            background: #fff;
            border: 1px solid var(--ledger-line);
            color: var(--ledger-ink);
        }

        .ledger-page .btn-ledger-back:hover {
            background: var(--ledger-soft);
            color: var(--ledger-ink);
        }

        .ledger-page .btn-ledger-pdf {
            background: var(--ledger-accent);
            border-color: var(--ledger-accent);
            color: #fff;
            font-weight: 600;
        }

        .ledger-page .btn-ledger-pdf:hover {
            background: #e68a39;
            border-color: #e68a39;
            color: #fff;
        }

        .ledger-page .customer-hero {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            padding: 1.15rem 1.25rem;
            background: linear-gradient(135deg, #fff 0%, #fff8f1 100%);
            border: 1px solid #f3e4d4;
            border-radius: 12px;
            margin-bottom: 1.25rem;
        }

        .ledger-page .customer-avatar {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #f3e4d4;
            color: var(--ledger-accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .ledger-page .customer-meta h5 {
            margin: 0 0 0.35rem;
            color: var(--ledger-ink);
            font-weight: 700;
        }

        .ledger-page .customer-meta .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem 1.25rem;
            color: var(--ledger-muted);
            font-size: 0.875rem;
            margin: 0;
        }

        .ledger-page .customer-meta .meta-row i {
            color: var(--ledger-accent);
            width: 14px;
        }

        .ledger-page .range-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--ledger-line);
            color: var(--ledger-muted);
            font-size: 0.8125rem;
            white-space: nowrap;
        }

        .ledger-page .range-chip strong {
            color: var(--ledger-ink);
            font-weight: 600;
        }

        .ledger-page .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .ledger-page .kpi-grid.is-filtered {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .ledger-page .kpi-grid:not(.is-filtered) {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .ledger-page .kpi-card {
            background: #fff;
            border: 1px solid var(--ledger-line);
            border-radius: 12px;
            padding: 1rem 1.1rem;
            position: relative;
            overflow: hidden;
            min-height: 96px;
        }

        .ledger-page .kpi-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .ledger-page .kpi-card.kpi-opening::before { background: #64748b; }
        .ledger-page .kpi-card.kpi-debit::before { background: var(--ledger-debit); }
        .ledger-page .kpi-card.kpi-credit::before { background: var(--ledger-credit); }
        .ledger-page .kpi-card.kpi-due::before { background: var(--ledger-accent); }

        .ledger-page .kpi-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--ledger-muted);
            margin-bottom: 0.35rem;
            font-weight: 600;
        }

        .ledger-page .kpi-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--ledger-ink);
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .ledger-page .kpi-value.is-debit { color: var(--ledger-debit); }
        .ledger-page .kpi-value.is-credit { color: var(--ledger-credit); }
        .ledger-page .kpi-value.is-due-positive { color: var(--ledger-debit); }
        .ledger-page .kpi-value.is-due-negative { color: var(--ledger-credit); }

        .ledger-page .kpi-hint {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: var(--ledger-muted);
        }

        .ledger-page .filter-panel {
            background: #fff;
            border: 1px solid var(--ledger-line);
            border-radius: 12px;
            padding: 1rem 1.15rem;
            margin-bottom: 1.25rem;
        }

        .ledger-page .filter-panel .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--ledger-muted);
            margin-bottom: 0.35rem;
        }

        .ledger-page .filter-panel .form-control {
            border-radius: 8px;
            border-color: #dbe1ea;
            min-height: 42px;
        }

        .ledger-page .filter-panel .form-control:focus {
            border-color: var(--ledger-accent);
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.2);
        }

        .ledger-page .btn-filter-apply {
            background: var(--ledger-ink);
            border-color: var(--ledger-ink);
            color: #fff;
            min-height: 42px;
            font-weight: 600;
        }

        .ledger-page .btn-filter-apply:hover {
            background: #111827;
            border-color: #111827;
            color: #fff;
        }

        .ledger-page .statement-card {
            background: #fff;
            border: 1px solid var(--ledger-line);
            border-radius: 12px;
            overflow: hidden;
        }

        .ledger-page .statement-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.15rem;
            border-bottom: 1px solid var(--ledger-line);
            background: var(--ledger-soft);
        }

        .ledger-page .statement-toolbar h6 {
            margin: 0;
            font-weight: 700;
            color: var(--ledger-ink);
        }

        .ledger-page .statement-toolbar .count-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--ledger-line);
            color: var(--ledger-muted);
            font-size: 0.8125rem;
        }

        .ledger-page .ledger-table {
            margin: 0;
            border-color: var(--ledger-line);
        }

        .ledger-page .ledger-table thead th {
            background: #f3f5f8;
            color: #374151;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            border-bottom-width: 1px;
            border-color: var(--ledger-line);
            white-space: nowrap;
            padding: 0.85rem 1rem;
            vertical-align: middle;
        }

        .ledger-page .ledger-table tbody td {
            padding: 0.85rem 1rem;
            border-color: var(--ledger-line);
            vertical-align: middle;
            color: var(--ledger-ink);
            font-variant-numeric: tabular-nums;
        }

        .ledger-page .ledger-table tbody tr:hover {
            background: #fffaf5;
        }

        .ledger-page .ledger-table tfoot td {
            background: #f8fafc;
            border-color: var(--ledger-line);
            padding: 0.9rem 1rem;
            font-weight: 700;
        }

        .ledger-page .row-opening {
            background: #f8fafc !important;
        }

        .ledger-page .type-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.22rem 0.55rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-right: 0.4rem;
        }

        .ledger-page .type-pill.sale {
            background: #e0f2fe;
            color: #0369a1;
        }

        .ledger-page .type-pill.return {
            background: #fef3c7;
            color: #b45309;
        }

        .ledger-page .type-pill.payment {
            background: #d1fae5;
            color: #047857;
        }

        .ledger-page .type-pill.opening {
            background: #e2e8f0;
            color: #475569;
        }

        .ledger-page .op-ref {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .ledger-page .amount-debit { color: var(--ledger-debit); font-weight: 600; }
        .ledger-page .amount-credit { color: var(--ledger-credit); font-weight: 600; }
        .ledger-page .amount-balance-pos { color: var(--ledger-debit); font-weight: 700; }
        .ledger-page .amount-balance-neg { color: var(--ledger-credit); font-weight: 700; }

        .ledger-page .action-btns {
            display: inline-flex;
            gap: 0.35rem;
        }

        .ledger-page .action-btns .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .ledger-page .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--ledger-muted);
        }

        .ledger-page .empty-state i {
            font-size: 2rem;
            color: #cbd5e1;
            margin-bottom: 0.75rem;
        }

        .ledger-page .pagination-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.15rem;
            border-top: 1px solid var(--ledger-line);
            background: #fff;
        }

        .ledger-page .pagination-bar .text-muted {
            font-size: 0.8125rem;
        }

        @media (max-width: 991.98px) {
            .ledger-page .kpi-grid,
            .ledger-page .kpi-grid.is-filtered,
            .ledger-page .kpi-grid:not(.is-filtered) {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .ledger-page .kpi-grid,
            .ledger-page .kpi-grid.is-filtered,
            .ledger-page .kpi-grid:not(.is-filtered) {
                grid-template-columns: 1fr;
            }

            .ledger-page .customer-hero {
                align-items: flex-start;
            }
        }
    </style>
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

    <div class="page-wrapper ledger-page">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Customer Ledger</h4>
                    <h6>Account statement & running balance</h6>
                </div>
                <div class="page-btn ledger-actions">
                    <a href="{{ route('customer.index') }}" class="btn btn-ledger-back">
                        <i class="fa fa-arrow-left me-1"></i> Back to list
                    </a>
                    <a href="{{ route('customerPayment', $customer->id) }}" class="btn btn-outline-secondary">
                        <i class="fa fa-money-bill-wave me-1"></i> Make payment
                    </a>
                    <a href="{{ route('customer.ledger.pdf', array_filter([
                            'customer' => $customer->id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                        ])) }}"
                        class="btn btn-ledger-pdf">
                        <i class="fa fa-file-pdf me-1"></i> Download PDF
                    </a>
                </div>
            </div>

            {{-- Customer identity --}}
            <div class="customer-hero">
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
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="range-chip">
                        <i class="fa fa-calendar-alt"></i>
                        <span>Period: <strong>{{ $rangeLabel }}</strong></span>
                    </span>
                    <span class="range-chip">
                        <i class="fa fa-list"></i>
                        <span>Entries: <strong>{{ $ledgerPaginator->total() }}</strong></span>
                    </span>
                </div>
            </div>

            {{-- KPI summary --}}
            <div class="kpi-grid {{ $isFiltered ? 'is-filtered' : '' }}">
                @if ($isFiltered)
                    <div class="kpi-card kpi-opening">
                        <div class="kpi-label">Opening balance</div>
                        <div
                            class="kpi-value {{ $openingBalance > 0 ? 'is-due-positive' : ($openingBalance < 0 ? 'is-due-negative' : '') }}">
                            ৳ {{ number_format($openingBalance, 2) }}
                        </div>
                        <div class="kpi-hint">Before selected period</div>
                    </div>
                @endif

                <div class="kpi-card kpi-debit">
                    <div class="kpi-label">Total debit</div>
                    <div class="kpi-value is-debit">৳ {{ number_format($debitTotal, 2) }}</div>
                    <div class="kpi-hint">Sales in period</div>
                </div>

                <div class="kpi-card kpi-credit">
                    <div class="kpi-label">Total credit</div>
                    <div class="kpi-value is-credit">৳ {{ number_format($creditTotal, 2) }}</div>
                    <div class="kpi-hint">Payments + returns</div>
                </div>

                <div class="kpi-card kpi-due">
                    <div class="kpi-label">Closing due</div>
                    <div
                        class="kpi-value {{ $dueTotal > 0 ? 'is-due-positive' : ($dueTotal < 0 ? 'is-due-negative' : '') }}">
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

            {{-- Filters --}}
            <div class="filter-panel">
                <form action="{{ route('customer.show', $customer->id) }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3 col-sm-6">
                            <label for="start_date" class="form-label">Start date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ $startDate ?? '' }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="end_date" class="form-label">End date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ $endDate ?? '' }}">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label for="per_page" class="form-label">Rows / page</label>
                            <select name="per_page" id="per_page" class="form-control">
                                @foreach ([25, 50, 100, 200] as $option)
                                    <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <button type="submit" class="btn btn-filter-apply w-100">
                                <i class="fa fa-filter me-1"></i> Apply
                            </button>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-light border w-100"
                                style="min-height:42px;">
                                <i class="fa fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Statement --}}
            <div class="statement-card">
                <div class="statement-toolbar">
                    <h6>Ledger statement</h6>
                    <span class="count-pill">
                        Page {{ $ledgerPaginator->currentPage() }} of {{ max(1, $ledgerPaginator->lastPage()) }}
                    </span>
                </div>

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
                                    <td
                                        class="text-end {{ $openingBalance > 0 ? 'amount-balance-pos' : 'amount-balance-neg' }}">
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
                                    <td
                                        class="text-end {{ $d->running_balance > 0 ? 'amount-balance-pos' : 'amount-balance-neg' }}">
                                        {{ number_format($d->running_balance, 2) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($d->type === 'payment')
                                            <div class="action-btns">
                                                <a href="{{ route('customer.payment.edit', $d->source_id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit payment">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <form action="{{ route('customer.payment.destroy', $d->source_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Delete payment"
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
                                    <td
                                        class="text-end {{ $dueTotal > 0 ? 'amount-balance-pos' : 'amount-balance-neg' }}">
                                        {{ number_format($dueTotal, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            if (startDate && endDate) {
                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                });
                endDate.addEventListener('change', function() {
                    startDate.max = this.value;
                });
            }
        });
    </script>
@endpush
