@extends('layout')

@section('title')
    Purchase Return Edit
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Edit Purchase Return</h4>
                    <h6>Add/Update Purchase Return</h6>
                </div>
            </div>
            <form action="{{ route('purchaseReturn.update', $purchaseReturnData->id) }}" method="post" id="jqvalidation">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <input type="text" value="{{ $purchaseReturnData->supplier->name }}"
                                        name="supplier_name" id="supplier_name">
                                        <div class="text-danger">
                                            @error('supplier_name')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="{{ $purchaseReturnData->date }}" name="date"
                                            placeholder="DD-MM-YYYY" />
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
                                        <th>Purchase Price(<i class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i>)</th>
                                        <th>Total Price</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="vertical-align: middle" id="tBody">
                                    {{-- @forelse ($sellReturnData as $d) --}}
                                    {{-- {{$sellReturnData}} --}}
                                    <tr class="" id="tRow">
                                        <td class="productimgname">
                                            <a class="product-img">
                                                <img src="{{ asset($purchaseReturnData->product->photo) }}" alt="product" />
                                            </a>
                                            <div class="form-group mt-3">
                                                <input type="text" name="product_name[]"
                                                    value="{{ $purchaseReturnData->product->product_name }}"
                                                    placeholder="Product Name" id="product_name">  
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
                                                    class="text-end quantity" value="{{ $purchaseReturnData->quantity }}"
                                                    placeholder="0">
                                            </div>
                                            <div class="text-danger">
                                                @error('quantity')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group mt-3">
                                                <input type="text" name="purchase_price" id="purchase_price"
                                                    value="{{ $purchaseReturnData->purchase_price }}" class="text-end price"
                                                    placeholder="0">
                                            </div>
                                            <div class="text-danger">
                                                @error('purchase_price')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group mt-3">
                                                <input type="text" name="purchase_total_price" class="text-end total_price"
                                                    id='purchase_total_price'
                                                    value="{{ $purchaseReturnData->total_amount }}"
                                                    placeholder="0">
                                            </div>
                                            <div class="text-danger">
                                                @error('purchase_total_price')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @empty
                                        @endforelse --}}


                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-lg-12 float-md-right">
                            <div class="total-order">
                                <ul>
                                    <li class="total">
                                        <div class="input-group">
                                            <h4>Sub Total</h4>
                                            <span class="input-group-text"><i
                                                    class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>

                                            <div>
                                                <input type="text" value="0" name="sub_total" id="sub_total"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)">
                                            </div>

                                        </div>
                                    </li>
                                    <li class="total">
                                        <div class="input-group">
                                            <h4>Discount</h4>
                                            <span class="input-group-text"><i
                                                    class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
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
                                            <span class="input-group-text"><i
                                                    class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                            <div>
                                                <input type="text" value="0" name="total_price" id="total_price"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)">
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
                                            <span class="input-group-text"><i
                                                    class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>

                                            <div>
                                                <input type="text" value="0" name="paid_amount" id="paid_amount"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)">
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
                                            <span class="input-group-text"><i
                                                    class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                            <div><input type="text" value="0" name="due" id="due"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> --}}
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
    <script src="{{ asset('assets/js/custom_purchase_return_Edit.js') }}"></script>
@endpush
<script></script>
