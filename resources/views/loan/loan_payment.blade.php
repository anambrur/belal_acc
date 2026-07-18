@extends('layout')

@section('title')
    Loan Payment
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Loan Payment</h4>
                    <h6>Make Loan Payment</h6>
                </div>
            </div>

            {{-- Unit Submit Here --}}

            <form action=" {{ route('makeLoanPayment') }}" method="post" id="jqvalidation">
                @csrf()
                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="loan_holder" value="{{ $loan->loan_holder }}" />
                                    <div class="text-danger">
                                        @error('loan_holder')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" name="amount"
                                        value="{{ $loan->amount - $loan->loanPayment->sum('paid_amount') }}" />
                                    <div class="text-danger">
                                        @error('amount')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Accounts</label>
                                    <select class="select" name="accounts_id" id="paymentMethod_data">
                                        @forelse ($accounts as $d)
                                            <option value="{{ $d->id }}">
                                                {{ $d->bank_name }} ({{ $d->amount }})
                                            </option>
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

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Date</label>
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

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <input type="text" name="paid_amount" value=""
                                        placeholder="Enter paid amount" />
                                </div>
                                <div class="text-danger">
                                    @error('paid_amount')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="categorylist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('assets/js/custom_loan.js') }}"></script>
@endpush
