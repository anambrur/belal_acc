@extends('layout')
@section('title')
    Daily Report
@endsection
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Daily Report</h4>
                    <h6>Manage your profit or loss</h6>
                </div>
                <div class="page-btn ">
                    <form action="{{ route('dateFilter') }}" method="post">
                        @csrf
                        <div class="d-flex">
                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" value="{{ date('Y-m') }}-01" name="dateFrom"
                                        placeholder="DD-MM-YYYY" id="datetimepicker" />
                                    <div class="addonset">
                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" value="{{ date('Y-m-d') }}" name="dateTo"
                                        placeholder="DD-MM-YYYY" id="datetimepickerto" />
                                    <div class="addonset">
                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary ms-3">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-lg-12 col-sm-12 col-12">
                    <div class="p-3 border">
                        <h4 class="card-title">Daily Calculation</h4>
                        <table class="table table-striped">
                            <tbody>
                                @php
                                    $cash_in_hand = totalBalance();
                                    $opening_balance = getOpeningBalance();
                                    $total_sell = $sum_of_day_sell - $sum_return_sell_price;

                                    $purchase_price = $get_sell_pro_pur_price - $sum_return_pur_price;

                                    $paid_amount = $total_paid_amount - $sum_return_sell_price;

                                    $sell_in_cash = $paid_amount - $sum_return_sell_price;

                                    $due = $total_sell - $paid_amount;

                                    $total_cash =
                                        $paid_amount + $loan_in_hand +
                                        $get_due_collection -
                                        ($sum_return_sell_price + $get_supplier_payment + $get_expenses);

                                    $closing_balance = $opening_balance + $total_cash;
                                @endphp
                                <tr>
                                    <th>Cash In Hand:</th>
                                    <th class="text-right">৳ {{ number_format($cash_in_hand) }}</th>
                                </tr>
                                <tr>
                                    <th>OPENNING BALANCE:</th>
                                    <th class="text-right">৳ {{ number_format($opening_balance) }}</th>
                                </tr>
                                <tr>
                                    <th>TOTAL INVOICE:</th>
                                    <th>{{ number_format($get_invoice) }}</th>
                                </tr>
                                {{-- <tr>
                                    <th>SUB TOTAL</th>
                                    <th class="text-right">৳ {{ number_format($sum_of_day_sell) }}</th>
                                </tr> --}}
                                {{-- <tr>
                                    <th>RETURN</th>
                                    <th class="text-right">৳ {{ number_format($sum_return_sell_price) }}</th>
                                </tr> --}}
                                <tr>
                                    <th>TOTAL SELL</th>
                                    <th class="text-right">৳ {{ number_format($total_sell) }}
                                    </th>
                                </tr>
                                {{-- <tr>
                                    <th>PURCHSE PRICE</th>
                                    <th class="text-right">৳ {{ number_format($purchase_price) }}
                                    </th>
                                </tr> --}}
                                <tr>
                                    <th>DISCOUNT</th>
                                    <th class="text-right">৳ {{ number_format($sum_discount_of_sell) }}</th>
                                </tr>
                                <tr>
                                    <th>SELL IN CASH</th>
                                    <th class="text-right">৳ {{ number_format($sell_in_cash) }}</th>
                                </tr>
                                <tr>

                                    <th>DUE</th>
                                    <th class="text-right">৳ {{ number_format($due) }}</th>
                                </tr>
                                <tr>
                                    <th>DUE COLLECTION</th>
                                    <th class="text-right">৳ {{ number_format($get_due_collection) }}</th>
                                </tr>
                                <tr>
                                    <th>SUPPLIER PAYMENT</th>
                                    <th class="text-right">৳ {{ number_format($get_supplier_payment) }}</th>
                                </tr>
                                <tr>
                                    <th>LOAN</th>
                                    <th class="text-right">৳ {{ number_format($loan_in_hand) }}</th>
                                </tr>
                                <tr>
                                    <th>TOTAL CASH</th>
                                    <th class="text-right">৳ {{ number_format($total_cash) }}</th>
                                </tr>
                                <tr>
                                    <th>CLOSONING BALANCE</th>
                                    <th class="text-right">৳ {{ number_format($closing_balance) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- <div class="col-lg-6 col-sm-12 col-12">
                    <div class="p-3 border">
                        <h4 class="card-title">Profit/Loss</h4>
                        <table class="table table-striped">
                            <tbody>
                                @php

                                    $grand_total = $total_sell - ($purchase_price + $sum_discount_of_sell);

                                    $profit = $grand_total - $get_expenses;

                                @endphp
                                <tr>
                                    <th>GRAND TOTAL</th>
                                    <th class="text-right">৳ {{ number_format($grand_total) }}</th>
                                </tr>
                                <tr>
                                    <th>EXPENSES</th>
                                    <th class="text-right">৳ {{ number_format($get_expenses) }}</th>
                                </tr>
                                <?php 
                                    if ($profit > -1) {
                                        ?>
                                <tr style="background-color: rgb(14, 215, 11);">
                                    <th>PROFIT</th>
                                    <th class="text-right">৳ {{ number_format($profit) }}</th>
                                </tr>
                                <?php
                                    }else{
                                        ?>
                                <tr style="background-color: rgb(216, 6, 45);">
                                    <th class="text-white">LOSS</th>
                                    <th class="text-right text-white">৳ {{ number_format($profit) }}</th>
                                </tr>
                                <?php
                                    }
                                    
                                    ?>



                            </tbody>
                        </table>
                    </div>
                </div> --}}
            </div>


        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/custom_profit_loss.js') }}"></script>
@endpush
