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
            <form action="{{ route('balanceAdjustment') }}" method="post" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Account</label>
                                    <select name="accounts_id" class="form-control" id="accountSelect">
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}" data-balance="{{ $account->amount }}">
                                                {{ $account->bank_name }} [{{ $account->account_number }}]
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="1">Add Balance</option>
                                        <option value="2">Remove Balance</option>
                                    </select>
                                    <div class="text-danger">
                                        @error('status')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Balance Input -->
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Available Balance</label>
                                    <input type="text" id="balanceInput" name="balance" value=""
                                        placeholder="Balance" readonly>
                                    <div class="text-danger">
                                        @error('balance')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" name="adjustAmount" value=""
                                        placeholder="Adjust Amount">
                                    <div class="text-danger">
                                        @error('adjustAmount')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
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

                            <div class="col-lg-6 col-sm-12">
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

                            <div class="col-lg-12 col-sm-12">
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

            // Update balance input when account is selected
            $('#accountSelect').on('change', function() {
                var selectedOption = $(this).find(':selected');
                var balance = selectedOption.data('balance');
            
                $('#balanceInput').val(balance ? balance : '');
            });
        });
    </script>
@endpush
