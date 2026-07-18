<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                    <label>Supplier Name</label>
                    <select class="select" name="supplier_id">
                        <option value="">Choose Supplier</option>
                        @forelse ($supplier as $d)
                            <option value="{{ $d->id }}">{{ $d->supplier_name }}</option>
                        @empty
                        @endforelse
                    </select>
                    <div class="text-danger">
                        @error('supplier_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-12">
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

            <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                    <label>Product Name</label>
                    <div class="input-groupicon">
                        <input type="text" name="barcode" 
                            id="barcode" placeholder="Scan/Search Product by code and select..."
                            autofocus />
                        <div class="addonset">
                            <img src="{{ asset('assets/img/icons/scanners.svg') }}" alt="img" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                    <label>Product Name</label>
                    <select class="select" name="product_id" id="product_data">
                        <option value="">Choose Product </option>
                        @forelse ($product as $d)
                            <option value="{{ $d->id }}">{{ $d->product_name }}</option>
                        @empty
                        @endforelse
                        <div class="addonset">
                            <img src="{{ asset('assets/img/icons/scanners.svg') }}" alt="img" />
                        </div>
                    </select>
                    <div class="text-danger">
                        @error('product_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="table-responsive table-bordered">
                <table class="table" id="myTable">
                    <thead>
                        <tr class="table-success">
                            <th style="width:30%">Product Name</th>
                            <th style="width:10%">Quantity</th>
                            <th style="width:10%">Purchase Price(৳)</th>
                            <th style="width:10%">Total Price(৳)</th>
                            <th style="width:10%">Profit (%)</th>
                            <th style="width:10%">Selling Price (৳)</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody class="vertical-align: middle" id="tBody">
                        
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mt-auto">
                <ul>
                    <li class="total">
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Payment Method</label>
                                <select class="select" name="paymentMethod_id" id="paymentMethod_data">
                                    @forelse ($paymentMethod as $d)
                                        <option value="{{ $d->id }}">
                                            {{ $d->payment_method }}
                                        </option>
                                    @empty
                                    @endforelse
                                    <div class="addonset">
                                        <img src="{{ asset('assets/img/icons/scanners.svg') }}"
                                            alt="img" />
                                    </div>
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
            <div class="col-lg-6">
                <div class="total-order">
                    <ul>
                        <li class="total">
                            <div class="input-group">
                                <h4>Sub Total</h4>
                                <span class="input-group-text">৳</span>

                                <div>
                                    <input type="text" value="0" name="sub_total" id="sub_total"
                                        class="form-control" aria-label="Amount (to the nearest dollar)">
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
            <div class="col-lg-12 float-md-right">

            </div>
        </div>
        <div class="row">

            <div class="col-lg-12">
                <hr>
                <button class="btn btn-submit me-2">Submit</button>
                <a href="" class="btn btn-cancel">Cancel</a>
            </div>
        </div>
    </div>
</div>