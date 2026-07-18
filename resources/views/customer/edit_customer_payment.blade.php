@extends('layout')
@section('title')
    Edit Customer Payment
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Edit Payment</h4>
                    <h6>Update customer payment details</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('customer.payment.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" class="form-control" value="{{ $customer->customer_name }}" readonly>
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Payment Amount</label>
                                    <input type="number" step="0.01" name="paid_amount" class="form-control" 
                                           value="{{ old('paid_amount', $payment->amount) }}" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Payment Date</label>
                                    <input type="date" name="date" class="form-control" 
                                           value="{{ old('date', $payment->date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Payment Account</label>
                                    <select class="select" name="accounts_id" required>
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" 
                                                {{ old('accounts_id', $payment->accounts_id) == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }} - {{ number_format($account->amount) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            @if(isset($payment->payment_for))
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Payment For</label>
                                    <input type="text" name="payment_for" class="form-control" 
                                           value="{{ old('payment_for', $payment->payment_for) }}">
                                </div>
                            </div>
                            @endif
                            
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Payment</button>
                                    <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection