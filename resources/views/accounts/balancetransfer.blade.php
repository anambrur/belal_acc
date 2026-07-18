@extends('layout')

@section('title')
    Balance Transfer
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Balance Transfer</h4>
                    <h6>Update Balance</h6>
                </div>
            </div>
            <form action="{{ route('balanceTransfer') }}" method="post" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Transfer Reason <span class="text-danger">*</span></label>
                                    <input type="text" name="reason" value="{{ old('reason') }}"
                                        placeholder="Enter your Note">
                                    <div class="text-danger">
                                        @error('reason')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>From Account <span class="text-danger">*</span></label>
                                    <select name="from_accounts_id" class="form-control" id="fromAccountSelect" required>
                                        <option value="" disabled selected>Select Account</option>

                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->bank_name }} [{{ $account->account_number }}]
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        @error('from_accounts_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>To Account <span id="accountError" class="text-danger">*</span></label>
                                    <select name="to_accounts_id" class="form-control" id="toAccountSelect" required>
                                        <option value="" disabled selected>Select Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->bank_name }} [{{ $account->account_number }}]
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        @error('to_accounts_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <!-- Balance Input -->
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Available Balance</label>
                                    <input type="text" id="balanceInput" name="balance" value="{{ old('balance') }}"
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
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="adjustAmount" value="{{ old('adjustAmount') }}"
                                        placeholder="Transfer Amount">
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
                                    <input type="text" name="description" value="{{ old('description') }}"
                                        placeholder="Enter your Note">
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
                                <button type="submit" class="btn btn-submit me-2" id="submitButton">Submit</button>
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

            $('#fromAccountSelect, #toAccountSelect').on('change', function() {
                var fromAccount = $('#fromAccountSelect').val();
                var toAccount = $('#toAccountSelect').val();
                var submitButton = $('button[type="submit"]');

                // Check if from and to accounts are the same
                if (fromAccount === toAccount && fromAccount !== null && toAccount !== null) {
                    $('#accountError').text('You cannot transfer to the same account.');
                    submitButton.prop('disabled', true);
                } else {
                    $('#accountError').text('');
                    submitButton.prop('disabled', false);
                }
            });

            // Prevent form submit on manual submission if validation fails
            $('#jqvalidation').on('submit', function(e) {
                var fromAccount = $('#fromAccountSelect').val();
                var toAccount = $('#toAccountSelect').val();

                if (fromAccount === toAccount && fromAccount !== null && toAccount !== null) {
                    e.preventDefault();
                    $('#accountError').text('You cannot transfer to the same account.');
                }
            });
        });
    </script>
@endpush
