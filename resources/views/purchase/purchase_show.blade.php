@extends('layout')

@section('title')
    Purchase Details
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Purchase Details</h4>
                </div>
            </div>
            <form action="" method="post">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="text" name="suspplier_name"
                                        value="{{ $Pur_details[0]->supplier->supplier_name }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="{{ $Pur_details[0]->date }}" name="date"
                                            placeholder="DD-MM-YYYY" " />
                                                    <div class="addonset">
                                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>

                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Purchase Price($)</th>
                                                        <th>Total Price($)</th>
                                                        <th>Sell Price($)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                       @forelse ($Pur_details as $p)
                                        {{-- {{ $p }} --}}
                                        <tr>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="{{ asset('storage/'.$p->product->photo) }}" alt="product" />
                                                </a>
                                                <a href="javascript:void(0);">{{ $p->product->product_name }}</a>
                                            </td>
                                            <td>{{ $p->quantity }}</td>
                                            <td>{{ $p->purchase_price }}</td>
                                            <td>{{ $p->quantity * $p->purchase_price }}</td>
                                            <td>{{ $p->sell_price }}</td>
                                        </tr>
                                    @empty
                                        @endforelse


                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 float-md-right">
                                        <div class="total-order">
                                            <ul>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Sub Total</h4>
                                                        <span class="input-group-text"><i
                                                                class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                        <input type="text" value="{{ $p->total_price + $p->total_discount}}" id="sub_total"
                                                            class="form-control text-end"
                                                            aria-label="Amount (to the nearest dollar)">
                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Discount</h4>
                                                        <span class="input-group-text"><i
                                                                class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                        <input type="text" value="{{ (int)$p->total_discount }}"
                                                            id="discount" class="form-control text-end"
                                                            aria-label="Amount (to the nearest dollar)">
                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Total</h4>
                                                        <span class="input-group-text"><i
                                                                class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                        <input type="text"
                                                            value="0"
                                                            id="total_amount" class="form-control text-end"
                                                            aria-label="Amount (to the nearest dollar)">
                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Paid</h4>
                                                        <span class="input-group-text"><i
                                                                class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                        <input type="text" value="{{ (int)$p->paid }}"
                                                            class="form-control text-end"
                                                            aria-label="Amount (to the nearest dollar)">
                                                    </div>
                                                </li>
                                                <li class="total">
                                                    <div class="input-group">
                                                        <h4>Due</h4>
                                                        <span class="input-group-text"><i
                                                                class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                        <input type="text" value="{{ $p->total_price - $p->paid }}"
                                                            class="form-control text-end"
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
    <script src="{{ asset('assets/js/add_purchase.js') }}"></script>
    <script>
        var s = $("#sub_total").val()
        var d = $("#discount").val()
        var total_amount = s-d;
        $("#total_amount").val(total_amount)
        var p = $("#paid_amount").val()
        var due = (g - d) - p;
        $("#due").val(due)
    </script>
@endpush
<script></script>
