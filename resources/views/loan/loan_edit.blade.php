@extends('layout')

@section('title')
    Loan Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Loan Edit </h4>

                </div>
            </div>

            {{-- Unit Submit Here --}}
            <form action="{{ route('loan.update', $loan->id) }}" method="post" id="jqvalidation">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
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
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" name="amount" value="{{ $loan->amount }}" />
                                    <div class="text-danger">
                                        @error('amount')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="input-groupicon">
                                        <input type="text" value="{{ $loan->date }}" name="date"
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


                            <div class="col-lg-12">
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
    <script src="{{ asset('assets/js/custom_loan.js') }}"></script>
@endpush
