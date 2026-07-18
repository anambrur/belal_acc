@extends('layout')
@section('title')
    Customer Ledger
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">

                    {{-- ========== Filter Section ========== --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('customer.show', $customer->id) }}" method="GET">
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            value="{{ $startDate ?? '' }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            value="{{ $endDate ?? '' }}">
                                    </div>
                                    

                                    {{-- Per Page Selector --}}
                                    <div class="col-md-2">
                                        <label for="per_page" class="form-label">Show Entries</label>
                                        <select name="per_page" id="per_page" class="form-control">
                                            @foreach ([25, 50, 100, 200] as $option)
                                                <option value="{{ $option }}"
                                                    {{ $perPage == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-filter me-1"></i> Filter
                                        </button>
                                    </div>

                                    <div class="col-md-2">
                                        <a href="{{ route('customer.show', $customer->id) }}"
                                            class="btn btn-secondary w-100">
                                            <i class="fa fa-refresh me-1"></i> Reset
                                        </a>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ========== Customer Info ========== --}}
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <h6 class="mb-1">Customer Name: <strong>{{ $customer->customer_name }}</strong></h6>
                                <p class="mb-0">Mobile: {{ $customer->phone }}</p>
                                <p class="mb-0">Address: {{ $customer->road->name }}, {{ $customer->location->address }}
                                </p>
                            </div>
                            <div class="col-lg-6 text-lg-end">
                                <p class="mb-0">
                                    <strong>Date Range:</strong>
                                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} —
                                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Total Records:</strong> {{ $ledgerPaginator->total() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ========== Ledger Table ========== --}}
                    <div class="table-responsive">
                        {{-- 
                            IMPORTANT: Do NOT add 'datanew' or any DataTable class here.
                            That class triggers Yajra DataTables JS which causes double pagination.
                        --}}
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Date</th>
                                    <th>Operation</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                    <th class="text-end">Balance</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($ledgerPaginator->currentPage() - 1) * $ledgerPaginator->perPage() + 1;
                                @endphp

                                @forelse ($ledgerPaginator as $d)
                                    <tr>
                                        <td>{{ $counter++ }}</td>

                                        <td>{{ \Carbon\Carbon::parse($d->date)->format('M d, Y') }}</td>

                                        <td>
                                            @if (isset($d->total_price) && $d->total_price > 0)
                                                <span class="badge bg-info text-dark">Sale</span>
                                                Invoice: <strong>{{ $d->invoice_id }}</strong>
                                            @elseif (isset($d->total_amount) && $d->total_amount > 0)
                                                <span class="badge bg-warning text-dark">Return</span>
                                                Product Return
                                            @else
                                                <span class="badge bg-success">Payment</span>
                                                @if (!empty($d->payment_for))
                                                    — {{ $d->payment_for }}
                                                @endif
                                            @endif
                                        </td>

                                        {{-- Debit --}}
                                        <td class="text-end">
                                            @if (isset($d->total_price) && $d->total_price > 0)
                                                {{ number_format($d->total_price) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Credit --}}
                                        <td class="text-end">
                                            @if (isset($d->amount) && $d->amount > 0)
                                                {{ number_format($d->amount) }}
                                            @elseif (isset($d->total_amount) && $d->total_amount > 0)
                                                {{ number_format($d->total_amount) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Pre-calculated running balance --}}
                                        <td
                                            class="text-end fw-bold
                                            {{ $d->running_balance > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($d->running_balance) }}
                                        </td>

                                        {{-- Action: only for payments --}}
                                        <td>
                                            @if (isset($d->amount) && $d->amount > 0 && isset($d->id))
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('customer.payment.edit', $d->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit Payment">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('customer.payment.destroy', $d->id) }}"
                                                        method="POST" style="display:inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Delete Payment"
                                                            onclick="return confirm('Delete this payment?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No records found for the selected date range.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Totals:</td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ number_format($debitTotal) }}
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ number_format($creditTotal) }}
                                    </td>
                                    <td
                                        class="text-end fw-bold
                                        {{ $dueTotal > 0 ? 'text-danger' : 'text-success' }}">
                                        Due: {{ number_format($dueTotal) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- ========== Pagination ========== --}}
                        @if ($ledgerPaginator->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Showing
                                    <strong>{{ $ledgerPaginator->firstItem() }}</strong>
                                    to
                                    <strong>{{ $ledgerPaginator->lastItem() }}</strong>
                                    of
                                    <strong>{{ $ledgerPaginator->total() }}</strong>
                                    entries
                                </div>
                                <div>
                                    {{ $ledgerPaginator->appends([
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'per_page' => $perPage,
                                        ])->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @else
                            <div class="text-muted small mt-2">
                                Showing all {{ $ledgerPaginator->total() }} entries
                            </div>
                        @endif

                    </div>{{-- end table-responsive --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection
