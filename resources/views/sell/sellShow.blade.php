@extends('layout')

@section('title')
    Sell Details
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sell Details</h4>
                </div>
            </div>
            <form action="" method="post">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name"
                                        value="{{ $sell_details[0]->customer->customer_name }} ({{ $sell_details[0]->customer->location->address }})">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="{{ $sell_details[0]->date }}" name="date"
                                            placeholder="DD-MM-YYYY" " />
                                                                    <div class="addonset">
                                                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                      
                                                           
                                                    </div>
                                                </div>

                                                 @forelse ($sell_details as $p)
                                    @empty
                                        @endforelse


                                        <div class="row">
                                            <div class="col-lg-12 float-md-right">
                                                <div class="total-order">
                                                    <ul>
                                                        <li class="total">
                                                            <div class="input-group">
                                                                <h4>Grand Total</h4>
                                                                <span class="input-group-text"><i
                                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                                <input type="text" value="{{ $p->total_price }}"
                                                                    id="grand_total" class="form-control text-start"
                                                                    aria-label="Amount (to the nearest dollar)">
                                                            </div>
                                                        </li>
                                                        <li class="total">
                                                            <div class="input-group">
                                                                <h4>Discount</h4>
                                                                <span class="input-group-text"><i
                                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                                <input type="text" value="{{ $p->total_discount }}"
                                                                    id="discount" class="form-control text-start"
                                                                    aria-label="Amount (to the nearest dollar)">
                                                            </div>
                                                        </li>
                                                        <li class="total">
                                                            <div class="input-group">
                                                                <h4>Total</h4>
                                                                <span class="input-group-text"><i
                                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                                <input type="text"
                                                                    value="{{ $p->total_price - $p->total_discount }}"
                                                                    id="discount" class="form-control text-start"
                                                                    aria-label="Amount (to the nearest dollar)">
                                                            </div>
                                                        </li>
                                                        <li class="total">
                                                            <div class="input-group">
                                                                <h4>Paid</h4>
                                                                <span class="input-group-text"><i
                                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                                <input type="text" value=" {{ $p->paid_amount }}"
                                                                    id="paid_amount" class="form-control text-start"
                                                                    aria-label="Amount (to the nearest dollar)">
                                                            </div>
                                                        </li>
                                                        <li class="total">
                                                            <div class="input-group">
                                                                <h4>Due</h4>
                                                                <span class="input-group-text"><i
                                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                                <input type="text"
                                                                    value="{{ $p->total_price - $p->paid }}" id="due"
                                                                    class="form-control text-start"
                                                                    aria-label="Amount (to the nearest dollar)">
                                                            </div>
                                                        </li>
                                                    </ul>
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
    {{-- <script src="{{ asset('assets/js/add_purchase.js') }}"></script> --}}
    <script>
        var g = $("#grand_total").val()
        var d = $("#discount").val()
        var p = $("#paid_amount").val()
        var due = (g - d) - p;
        $("#due").val(due)
    </script>
@endpush
