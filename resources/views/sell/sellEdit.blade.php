@extends('layout')

@section('title')
    Sell Edit
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sell edit-sales</h4>
                    <h6>Add/Update Purchase</h6>
                </div>
            </div>
            <form action="{{ route('sell.update', $sell_details[0]->invoice_id) }}" method="post" id="jqvalidation">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name"
                                        value="{{ $sell_details[0]->customer->customer_name }} ({{ $sell_details[0]->customer->location->address }})"
                                        readonly>
                                    <input type="hidden" name="customer_id" value="{{ $sell_details[0]->customer_id }}">
                                    <div class="text-danger">
                                        @error('customer_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" value="{{ $sell_details[0]->date }}" name="date"
                                            placeholder="DD-MM-YYYY" id="datetimepicker" />
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
                        <div class="row">
                            <div class="table-responsive table-bordered">
                                <table class="table" id="myTable">
                                    <thead>

                                    </thead>
                                    <tbody class="vertical-align: middle" id="tBody">
                                        <?php $m = 1; ?>
                                        @forelse ($sell_details as $k=> $p)
                                            {{-- {{ $p }} --}}
                                            <?php $m += $k; ?>

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
                                                <span class="input-group-text">৳</span>

                                                <div>
                                                    <input type="text" value="{{ $p->total_price }}" name="sub_total"
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
                                                    <input type="text" value="{{ $p->total_discount }}"
                                                        name="discount_price" id="discount_price" class="form-control"
                                                        aria-label="Amount (to the nearest dollar)">
                                                </div>

                                            </div>
                                        </li>
                                        <li class="total">
                                            <div class="input-group">
                                                <h4>Total</h4>
                                                <span class="input-group-text">৳</span>
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
                                                <span class="input-group-text">৳</span>

                                                <div>
                                                    <input type="text" value="{{ $sell_details[0]->paid_amount }}"
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




                            <div class="col-6">
                                <div class="form-group">
                                    <select class="select" name="accounts_id" id="paymentMethod_data">
                                        @forelse ($accounts as $d)
                                            <option value="{{ $d->id }}">
                                                {{ $d->bank_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                        <div class="addonset">
                                            <img src="{{ asset('assets/img/icons/scanners.svg') }}" alt="img" />
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
    <script>
        $(document).ready(function() {
            $('#datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
                scrollInput: false
            });
        });
        let laram = {{ $m }}

        var g = $("#sub_total").val()
        var d = $("#discount_price").val()
        var total_price = g - d
        $("#total_price").val(total_price)

        var p = $("#paid_amount").val()
        var due = total_price - p
        $("#due").val(due)
    </script>
    <script src="{{ asset('assets/js/custom_pos_sell.js') }}"></script>
@endpush
{{-- <script>
    var laravelData = @json($product);
</script> --}}
