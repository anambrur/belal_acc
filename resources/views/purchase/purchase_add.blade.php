@extends('layout')

@section('title')
    Purchase Add
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Purchase Add</h4>
                    <h6>Add/Update Purchase</h6>
                </div>
            </div>
            <form action="{{ route('purchase.store') }}" method="post" id="jqvalidation">
                @csrf()
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
                                                    <option value="{{ $d->id }}">{{ $d->supplier_name }}
                                                        ({{ $d->location->address }})
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
                                                <input type="text" value="" name="date" placeholder="DD-MM-YYYY"
                                                    id="datetimepicker" />
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
                                                    <option value="{{ $d->id }}" data-balance="{{ $d->amount }}">
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
                                                            <input type="text" value="0" name="sub_total"
                                                                id="sub_total" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)">
                                                        </div>

                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Discount</h4>
                                                        <span class="input-group-text">৳</span>
                                                        <div>
                                                            <input type="text" value="0" name="discount_price"
                                                                id="discount_price" class="form-control"
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
                                                            <input type="text" value="0" name="total_price"
                                                                id="total_price" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)">
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
                                                            <input type="text" value="0" name="paid_amount"
                                                                id="paid_amount" class="form-control"
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
                                                        <div><input type="text" value="0" name="due"
                                                                id="due" class="form-control"
                                                                aria-label="Amount (to the nearest dollar)">
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
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="" class="btn btn-cancel">Cancel</a>
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
        let laram = 1;
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
    <script src="{{ asset('assets/js/custome_add_purchase.js') }}"></script>
@endpush
<script>
    var laravelData = @json($product);
</script>
