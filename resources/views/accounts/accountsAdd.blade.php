@extends('layout')

@section('title')
    Accounts Add
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Accounts Add</h4>
                    <h6>Add/Update Accounts</h6>
                </div>
            </div>
            <form action="{{ route('accounts.store') }}" method="post" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" value="" placeholder="Enter a Bank Name">
                                    <div class="text-danger">
                                        @error('bank_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>Branch Name</label>
                                    <input type="text" name="branch_name" value=""
                                        placeholder="Enter a branch Name">
                                    <div class="text-danger">
                                        @error('branch_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" name="account_number" value=""
                                        placeholder="Enter a Account Number">
                                    <div class="text-danger">
                                        @error('account_number')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="input-groupicon">
                                        <input type="text" value="" name="date" placeholder="DD-MM-YYYY"
                                            id="datetimepicker" />
                                        <div class="addonset">
                                            <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="text-danger">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" name="description" value="" placeholder="Enter your Note">
                                    <div class="text-danger">
                                        @error('description')
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
            $(".select").select2();
            $("#datetimepicker").datetimepicker({
                format: "Y-m-d",
                timepicker: false,
                lang: "ru",
                value: new Date(),
            });
        });
    </script>
@endpush
