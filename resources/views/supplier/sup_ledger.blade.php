@extends('layout')
@section('title')
    Supplier Ledger
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('supplier.show', $supplier->id) }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ $startDate }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="{{ $endDate }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label style="visibility: hidden;">Filter</label>
                                            <button type="submit" class="btn btn-primary form-control">Filter</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label style="visibility: hidden;">Reset</label>
                                            <a href="{{ route('supplier.show', $supplier->id) }}"
                                                class="btn btn-secondary form-control">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Supplier Info -->
                    <div class="table-top mb-0">
                        <div class="row" style="width: 100%">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <h6>Supplier Name: {{ $supplier->supplier_name }} </h6>
                                <p class="m-0">Mobile: {{ $supplier->phone }} </p>
                                <p class="m-0">Address: {{ $supplier->road->name }},
                                    <span>{{ $supplier->location->address }}</span>
                                </p>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <p class="m-0">Date Range: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ledger Table -->
                    <div class="table-responsive">
                        <table class="table datanew data-table" id="dataTable">
                            <thead>
                                <tr class="table-primary">
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Operation</th>
                                    <th>Debit Amount</th>
                                    <th>Credit Amount</th>
                                    <th>Amount Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $amountBalance = 0;
                                    $counter = ($ledgerPaginator->currentPage() - 1) * $ledgerPaginator->perPage() + 1;
                                @endphp

                                @foreach ($ledgerPaginator as $d)
                                    @php
                                        // Calculate running balance
                                        if (isset($d->total_price) && $d->total_price > 0) {
                                            $amountBalance += $d->total_price;
                                        }
                                        if (isset($d->total_amount) && $d->total_amount > 0) {
                                            $amountBalance -= $d->total_amount;
                                        }
                                        if (isset($d->amount) && $d->amount > 0) {
                                            $amountBalance -= $d->amount;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->date)->format('M d, Y') }}</td>
                                        
                                        <td>
                                            @if (isset($d->total_price) && $d->total_price > 0)
                                                Invoice No: {{ $d->invoice_id }}
                                            @elseif(isset($d->total_amount) && $d->total_amount > 0)
                                                Product Return
                                            @else
                                                Payment
                                                {{-- Add edit/delete buttons for payments --}}
                                                @if (isset($d->id))
                                                    <div class="btn-group">
                                                        <a href="{{ route('supplier.payment.edit', $d->id) }}"
                                                            class="btn btn-sm btn-primary me-1">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('supplier.payment.destroy', $d->id) }}"
                                                            method="POST" style="display:inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this payment?')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($d->total_price) && $d->total_price > 0)
                                                {{ number_format($d->total_price) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($d->amount) && $d->amount > 0)
                                                {{ number_format($d->amount) }}
                                            @elseif(isset($d->total_amount) && $d->total_amount > 0)
                                                {{ number_format($d->total_amount) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ number_format($amountBalance) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Totals:</td>
                                    <td class="fw-bold">{{ number_format($debitTotal) }}</td>
                                    <td class="fw-bold">{{ number_format($creditTotal) }}</td>
                                    <td class="fw-bold">Due: {{ number_format($debitTotal - $creditTotal) }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Showing {{ $ledgerPaginator->firstItem() }} to {{ $ledgerPaginator->lastItem() }}
                                of {{ $ledgerPaginator->total() }} entries
                            </div>
                            <div>
                                {{ $ledgerPaginator->appends([
                                        'start_date' => $startDate,
                                        'end_date' => $endDate,
                                    ])->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add some JavaScript for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Date validation
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

            // Select all checkbox functionality
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        });
    </script>
@endsection
