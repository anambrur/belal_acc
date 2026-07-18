@extends('layout')

@section('title')
    Sell Create
@endsection
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <form action="{{ route('sell.store') }}" method="post" id="jqvalidation">
                <div class="row ">
                    @csrf()
                    <div class="col-lg-4 col-sm-12">
                        <div class="order-list">
                            <div class="orderid">
                                <h4>Sells Create</h4>
                                <h5>Invoice id : MT {{ $invoice_id }}</h5>
                            </div>
                        </div>
                        <div class="card card-order">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('customer.create') }}" class="btn btn-adds"
                                            data-bs-target="#create"><i class="fa fa-plus me-2"></i>Add
                                            Customer</a>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <select class="select" name="customer_id">
                                                <option value="">Choose Customer</option>
                                                @forelse ($customer as $d)
                                                    <option value="{{ $d->id }}">
                                                        {{ $d->customer_name }} ({{ $d->location->address }})</option>

                                                @empty
                                                @endforelse
                                            </select>
                                            
                                            <div class="text-danger">
                                                @error('customer_id')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="input-groupicon">
                                                <input type="text" value="" name="date" placeholder="DD-MM-YYYY"
                                                    id="datetimepicker" />
                                                <div class="addonset">
                                                    <img src="{{ asset('assets/img/icons/calendars.svg') }}"
                                                        alt="img" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="form-group">
                                            <select class="select" name="accounts_id" id="paymentMethod_data">
                                                @forelse ($accounts as $d)
                                                    <option value="{{ $d->id }}">
                                                        {{ $d->bank_name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                                <div class="addonset">
                                                    <img src="{{ asset('assets/img/icons/scanners.svg') }}"
                                                        alt="img" />
                                                </div>
                                            </select>
                                            <div class="text-danger">
                                                @error('paymentMethod_id')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="split-card"></div>

                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-12 tabs_wrapper">
                        <div class="tabs_container">
                            <div class="tab_content active" data-tab="fruits">
                                <div class="row">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6 mt-auto">
                                                    <ul>
                                                        <li class="total">

                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="total-order">
                                                        <ul>
                                                            <li class="total">
                                                                <div class="input-group">
                                                                    <h4>Sub Total</h4>
                                                                    <span class="input-group-text">৳</span>

                                                                    <div>
                                                                        <input type="text" value="0"
                                                                            name="sub_total" id="sub_total"
                                                                            class="form-control
                                                                            aria-label="Amount
                                                                            (to the nearest dollar)">
                                                                    </div>

                                                                </div>
                                                            </li>
                                                            <li class="total">
                                                                <div class="input-group">
                                                                    <h4>Discount</h4>
                                                                    <span class="input-group-text">৳</span>
                                                                    <div>
                                                                        <input type="text" value="0"
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
                                                                        <input type="text" value="0"
                                                                            name="total_price" id="total_price"
                                                                            class="form-control"
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
                                                                        <input type="text" value="0"
                                                                            name="paid_amount" id="paid_amount"
                                                                            class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)">
                                                                    </div>
                                                                    <div class="text-danger">
                                                                        @error('paid_amount')
                                                                            {{ $message }}
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li class="total">
                                                                <div class="input-group">
                                                                    <h4>Due</h4>
                                                                    <span class="input-group-text">৳</span>
                                                                    <div><input type="text" value="0"
                                                                            name="due" id="due"
                                                                            class="form-control"
                                                                            aria-label="Amount (to the nearest dollar)">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
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

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom_pos_sell.js') }}"></script>
@endpush
