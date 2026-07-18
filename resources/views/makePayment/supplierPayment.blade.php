@extends('layout')
@section('title')
    Supplier Payment
@endsection
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Supplier Payment</h4>
                    <h6>Make Spplier Payment</h6>
                </div>
            </div>
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        $('.alert-success').fadeOut('slow');
                    }, 3000);
                </script>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('makeSupplierPayment') }}" method="post" id="jqvalidation">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="hidden" name="supplier_id"
                                        value="{{ $sup_pay_data[0]['supplier']['id'] }}" />
                                    <input type="text" name="supplier_name"
                                        value="{{ $sup_pay_data[0]['supplier']['supplier_name'] }}" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="" name="date" placeholder="DD-MM-YYYY"
                                            id="datetimepicker" />
                                        <div class="addonset">
                                            <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                        </div>
                                        <div class="text-danger">
                                            @error('date')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <ul>
                                    <li class="total">
                                        <div class="col-lg-12 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Accounts</label>
                                                <select class="select" name="accounts_id" id="paymentMethod_data">
                                                    @forelse ($accounts as $d)
                                                        <option value="{{ $d->id }}"
                                                            data-balance="{{ $d->amount }}">
                                                            {{ $d->bank_name }} (Balance: {{ $d->amount }})
                                                        </option>
                                                    @empty
                                                        <option>No accounts available</option>
                                                    @endforelse
                                                </select>
                                                <div class="text-danger">
                                                    @error('product_id')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12 float-md-right">
                                <div class="total-order">
                                    <ul>
                                        <li class="total">
                                            <div class="input-group">
                                                <h4>Due Amount</h4>
                                                <span class="input-group-text">৳</span>

                                                <div>
                                                    <input type="text" value="{{ $dueAmount }}" name="due_amount"
                                                        id="due_amount" class="form-control"
                                                        aria-label="Amount (to the nearest dollar)">
                                                </div>

                                            </div>
                                        </li>

                                        <li class="total">
                                            <div class="input-group">
                                                <h4>Paid Amount</h4>
                                                <span class="input-group-text">৳</span>
                                                <div>
                                                    <input type="text" value="0" name="paid_amount" id="paid_amount"
                                                        autocomplete="off" class="form-control"
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
                                                <div><input type="text" value="0" name="due" id="due"
                                                        class="form-control" aria-label="Amount (to the nearest dollar)">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="purchaselist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#paymentMethod_data').on('change', function() {
                const selectedAccountId = $(this).val();
                const selectedAccountOption = $(this).find(':selected');
                const accountBalance = parseFloat(selectedAccountOption.data('balance')) || 0;

                // Log for debugging
                console.log('Selected Account ID:', selectedAccountId);
                console.log('Account Balance:', accountBalance);

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
                        paidAmountInput.val(
                            accountBalance);
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('assets/js/custom_supplier_payment.js') }}"></script>
@endpush
