@extends('layout')

@section('title')
    Add Purchase Return
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Add Purchase Return</h4>
                    <h6>Add/Update Purchase Return</h6>
                </div>
            </div>
            <form action="{{ route('purchaseReturn.store') }}" method="post" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <div class="input-groupicon">
                                        <input type="text" name="invoice_number" id="invoice_number"  placeholder="Invoice Number" autocomplete="off" />
                                        <div class="addonset">
                                            <img src="{{ asset('assets/img/icons/search-white.svg') }}" alt="img" />
                                        </div>
                                        <div class="text-danger">
                                            @error('invoice_number')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="" name="date" placeholder="DD-MM-YYYY"
                                        id="date"/>
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



                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <div class="input-groupicon">
                                        <input type="text" placeholder="Scan/Search Product by code and select..." />
                                        <div class="addonset">
                                            <img src="{{ asset('assets/img/icons/scanners.svg') }}" alt="img" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <input type="hidden" id="supplier_id" name="supplier_id">
                                    <input type="text" id="supplier_name" name="supplier_name">
                                    
                                </div>
                                <div class="text-danger">
                                    @error('supplier_id')
                                        {{ $message }}
                                    @enderror
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
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="vertical-align: middle" id="tBody">
                                        

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
                                                <span class="input-group-text"><i
                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
                                                <div>
                                                    <input type="text"
                                                    value="0"
                                                        name="discount_price"
                                                        id="discount_price"
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
                                                <span class="input-group-text"><i
                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
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
                                                <span class="input-group-text"><i
                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>

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
                                        <li class="total">
                                            <div class="input-group">
                                                <h4>Due</h4>
                                                <span class="input-group-text"><i
                                                        class="fa-sharp fa-solid fa-bangladeshi-taka-sign"></i></span>
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
                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <button class="btn btn-submit me-2" >Submit</button>
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
    <script src="{{ asset('assets/js/custom_purchase_return.js') }}"></script>
@endpush
<script>
    var laravelData = @json($product);
</script>
