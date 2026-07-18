@extends('layout')

@section('title')
    Purchase Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Purchase Edit</h4>
                    <h6>Update Purchase Details</h6>
                </div>
            </div>
            <form action="{{ route('purchase.update', $Pur_details[0]->invoice_id) }}" method="post" id="jqvalidation">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <select class="select" name="supplier_id">
                                                <option value="">Choose Supplier</option>
                                                @forelse ($supplier as $d)
                                                    <option value="{{ $d->id }}"
                                                        {{ $Pur_details[0]->supplier_id == $d->id ? 'selected' : '' }}>
                                                        {{ $d->supplier_name }} ({{ $d->location->address }})
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <div class="text-danger">
                                                @error('supplier_id')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Purchase Date </label>
                                            <div class="input-groupicon">
                                                <input type="text" value="{{ $Pur_details[0]->date }}" name="date"
                                                    placeholder="DD-MM-YYYY" id="datetimepicker" />
                                                <div class="addonset">
                                                    <img src="{{ asset('assets/img/icons/calendars.svg') }}"
                                                        alt="img" />
                                                </div>
                                                <div class="text-danger">
                                                    @error('date')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Accounts</label>
                                            <select class="select" name="accounts_id" id="paymentMethod_data">
                                                @forelse ($accounts as $d)
                                                    <option value="{{ $d->id }}" data-balance="{{ $d->amount }}"
                                                        {{ $Pur_details[0]->account_id == $d->id ? 'selected' : '' }}>
                                                        {{ $d->bank_name }} (Balance: {{ $d->amount }})
                                                    </option>
                                                @empty
                                                    <option>No accounts available</option>
                                                @endforelse
                                            </select>
                                            <div class="text-danger">
                                                @error('accounts_id')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 offset-1">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="total-order">
                                            <ul>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Sub Total</h4>
                                                        <span class="input-group-text">৳</span>
                                                        <div>
                                                            <input type="text"
                                                                value="{{ (int) $Pur_details[0]->total_price + $Pur_details[0]->total_discount }}"
                                                                name="sub_total" id="sub_total" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Discount</h4>
                                                        <span class="input-group-text">৳</span>
                                                        <div>
                                                            <input type="text"
                                                                value="{{ (int) $Pur_details[0]->total_discount }}"
                                                                name="discount_price" id="discount_price"
                                                                class="form-control"
                                                                aria-label="Amount (to the nearest dollar)">
                                                        </div>
                                                        <div class="text-danger">
                                                            @error('discount_price')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Total</h4>
                                                        <span class="input-group-text">৳</span>
                                                        <div>
                                                            <input type="text"
                                                                value="{{ (int) $Pur_details[0]->total_price }}"
                                                                name="total_price" id="total_price" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)" readonly>
                                                        </div>
                                                        <div class="text-danger">
                                                            @error('total_price')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Paid</h4>
                                                        <span class="input-group-text">৳</span>
                                                        <div>
                                                            <input type="text"
                                                                value="{{ (int) $Pur_details[0]->paid_amount }}"
                                                                name="paid_amount" id="paid_amount" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)">
                                                        </div>
                                                        <div class="text-danger">
                                                            @error('paid_amount')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </li>
                                                <span id="error_due"></span>

                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Due</h4>
                                                        <span class="input-group-text">৳</span>
                                                        <div>
                                                            <input type="text"
                                                                value="{{ (int) $Pur_details[0]->total_price - (int) $Pur_details[0]->paid_amount }}"
                                                                name="due" id="due" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)" readonly>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-end">
                                <hr>
                                <button class="btn btn-submit me-2">Update</button>
                                <a href="{{ route('purchase.index') }}" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize datetimepicker
            $('#datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
                scrollInput: false
            });
            // Payment method validation
            $('#paymentMethod_data').on('change', function() {
                const selectedAccountId = $(this).val();
                const selectedAccountOption = $(this).find(':selected');
                const accountBalance = parseFloat(selectedAccountOption.data('balance')) || 0;

                const paidAmountInput = $('#paid_amount');
                const errorMsg = $('<span class="text-danger text-end"></span>');
                errorMsg.appendTo($('#error_due'));

                paidAmountInput.on('input', function() {
                    const enteredAmount = parseFloat(paidAmountInput.val()) || 0;

                    // Clear any previous error messages
                    errorMsg.text('');

                    // Check if the entered amount exceeds the balance
                    if (enteredAmount > accountBalance) {
                        errorMsg.text('Paid amount cannot exceed account balance of ৳' +
                            accountBalance);
                        paidAmountInput.val(accountBalance);
                    }

                    // Recalculate due amount
                    calculateDue();
                });
            });

            // Calculate total and due amounts
            function calculateTotals() {
                const subTotal = parseFloat($('#sub_total').val()) || 0;
                const discount = parseFloat($('#discount_price').val()) || 0;
                const totalPrice = subTotal - discount;

                $('#total_price').val(totalPrice.toFixed(2));
                calculateDue();
            }

            // Calculate due amount
            function calculateDue() {
                const totalPrice = parseFloat($('#total_price').val()) || 0;
                const paidAmount = parseFloat($('#paid_amount').val()) || 0;
                const dueAmount = totalPrice - paidAmount;

                $('#due').val(dueAmount.toFixed(2));
            }

            // Recalculate when sub_total changes
            $('#sub_total').on('input', function() {
                calculateTotals();
            });

            // Recalculate when discount changes
            $('#discount_price').on('input', function() {
                calculateTotals();
            });

            // Recalculate when paid amount changes
            $('#paid_amount').on('input', function() {
                calculateDue();
            });

            // Initialize calculations on page load
            calculateTotals();
        });
    </script>
@endpush
