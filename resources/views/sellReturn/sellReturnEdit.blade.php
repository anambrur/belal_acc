@extends('layout')

@section('title')
    Sell Return Edit
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Add Sell Return</h4>
                    <h6>Add/Update Sell Return</h6>
                </div>
            </div>
            <form action="{{ route('sellsReturn.update',$sellReturnData->id) }}" method="post" id="jqvalidation">
                @csrf()
                @method("PUT")


                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <input type="text" value="{{$sellReturnData->customer->customer_name}}" name="customer_name" id="customer_name">
                                    <div class="text-danger">
                                        @error('customer_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="{{$sellReturnData->date}}" name="date" placeholder="DD-MM-YYYY"
                                          id="date" />
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive table-bordered">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr class="table-success">
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Sell Price(<i class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i>)</th>
                                            <th>Total Price</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="vertical-align: middle" id="tBody">
                                        
                                        {{-- {{$sellReturnData}} --}}
                                            <tr class="" id="tRow">
                                                <td class="productimgname">
                                                    <a class="product-img">
                                                        <img src="{{asset('storage/'.$sellReturnData->product->photo)}}" alt="product" />
                                                    </a>
                                                    <div class="form-group mt-3">
                                                        <input type="text" name="product_name" value="{{$sellReturnData->product->product_name}}"
                                                            placeholder="Product Name">
                                                    </div>
                                                    <div class="text-danger">
                                                        @error('product_name')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mt-3">
                                                        <input type="text" name="quantity" id='quantity'
                                                            class="text-end quantity" value="{{$sellReturnData->quantity}}" placeholder="0">
                                                    </div>
                                                    <div class="text-danger">
                                                        @error('quantity')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mt-3">
                                                        <input type="text" name="sell_price" id="sell_price"
                                                            value="{{$sellReturnData->sell_price}}" class="text-end price" placeholder="0">
                                                    </div>
                                                    <div class="text-danger">
                                                        @error('sell_price')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mt-3">
                                                        <input type="text" name="sell_total_price"
                                                            class="text-end total_price" id='sell_total_price' value="{{$sellReturnData->total_amount}}"
                                                            placeholder="0">
                                                    </div>
                                                    <div class="text-danger">
                                                        @error('sell_total_price')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="purchaselist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom_sell_return_Edit.js') }}"></script>
@endpush
<script></script>
