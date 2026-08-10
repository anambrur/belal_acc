@extends('layout')

@section('title')
    Customer Payment
@endsection

@php
    $customer = $cus_pay_data[0]['customer'];
    $customerLabel = ($customer['customer_name'] ?? '—') .
        ' (' . (($customer['address'] ?? '') !== '' ? $customer['address'] : '—') . ')';
    $openingDue = (float) $dueAmount;
    $sellTotal = (float) ($cus_pay_data[0]['sell'] ?? 0);
    $returnTotal = (float) ($cus_pay_data[0]['return'] ?? 0);
    $paymentTotal = (float) ($cus_pay_data[0]['payment'] ?? 0);
@endphp

@push('css')
    <style>
        .customer-payment-page {
            --s-accent: #ff9f43;
            --s-ink: #1f2937;
            --s-muted: #6b7280;
            --s-line: #e8ecf1;
            --s-soft: #f7f8fa;
        }

        .customer-payment-page .page-header {
            margin-bottom: 1.25rem;
        }

        .customer-payment-page .page-header .page-title h4 {
            margin-bottom: 0.15rem;
        }

        .customer-payment-page .page-header .page-title h6 {
            color: var(--s-muted);
            font-weight: 400;
        }

        .customer-payment-page .balance-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: #fff8f1;
            border: 1px solid #f3e4d4;
            color: #c2410c;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .customer-payment-page .balance-chip.is-cleared {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #047857;
        }

        .customer-payment-page .balance-chip.is-advance {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .customer-payment-page .panel {
            background: #fff;
            border: 1px solid var(--s-line);
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        .customer-payment-page .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.9rem 1.15rem;
            background: var(--s-soft);
            border-bottom: 1px solid var(--s-line);
        }

        .customer-payment-page .panel-head h6 {
            margin: 0;
            font-weight: 700;
            color: var(--s-ink);
        }

        .customer-payment-page .panel-head .hint {
            margin: 0;
            font-size: 0.75rem;
            color: var(--s-muted);
        }

        .customer-payment-page .panel-body {
            padding: 1.15rem;
        }

        .customer-payment-page .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--s-muted);
            margin-bottom: 0.4rem;
        }

        .customer-payment-page .form-control,
        .customer-payment-page .select2-container .select2-selection--single {
            min-height: 44px;
            border-radius: 8px;
            border-color: #dbe1ea;
        }

        .customer-payment-page .form-control:focus {
            border-color: var(--s-accent);
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.2);
        }

        .customer-payment-page .form-control[readonly] {
            background: var(--s-soft);
            color: var(--s-ink);
        }

        .customer-payment-page .select2-container .select2-selection--single {
            display: flex;
            align-items: center;
        }

        .customer-payment-page .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
            padding-left: 12px;
            color: var(--s-ink);
        }

        .customer-payment-page .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .customer-payment-page .date-field {
            position: relative;
        }

        .customer-payment-page .date-field .form-control {
            padding-right: 2.5rem;
        }

        .customer-payment-page .date-field .date-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            opacity: 0.55;
            pointer-events: none;
        }

        .customer-payment-page .stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.65rem;
            margin-bottom: 1rem;
        }

        .customer-payment-page .stat-box {
            padding: 0.7rem 0.8rem;
            border: 1px solid var(--s-line);
            border-radius: 10px;
            background: var(--s-soft);
        }

        .customer-payment-page .stat-box span {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--s-muted);
            margin-bottom: 0.25rem;
        }

        .customer-payment-page .stat-box strong {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--s-ink);
            font-variant-numeric: tabular-nums;
        }

        .customer-payment-page .amount-grid {
            display: grid;
            gap: 0.85rem;
        }

        .customer-payment-page .amount-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 0.75rem;
            align-items: center;
            padding: 0.85rem 1rem;
            border: 1px solid var(--s-line);
            border-radius: 10px;
            background: #fff;
        }

        .customer-payment-page .amount-row.is-highlight {
            background: linear-gradient(135deg, #fff 0%, #fff8f1 100%);
            border-color: #f3e4d4;
        }

        .customer-payment-page .amount-row.is-due {
            background: #fff5f5;
            border-color: #fecdd3;
        }

        .customer-payment-page .amount-row .amount-label {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--s-ink);
        }

        .customer-payment-page .amount-row .amount-sub {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--s-muted);
            margin-top: 0.15rem;
        }

        .customer-payment-page .input-with-prefix {
            display: flex;
            align-items: stretch;
            border: 1px solid #dbe1ea;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .customer-payment-page .input-with-prefix .prefix {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            background: var(--s-soft);
            border-right: 1px solid #dbe1ea;
            color: var(--s-muted);
            font-weight: 700;
        }

        .customer-payment-page .input-with-prefix .form-control {
            border: 0;
            border-radius: 0;
            min-height: 44px;
            box-shadow: none;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .customer-payment-page .input-with-prefix .form-control:focus {
            box-shadow: none;
        }

        .customer-payment-page .summary-note {
            margin-top: 1rem;
            padding: 0.75rem 0.9rem;
            border-radius: 8px;
            background: var(--s-soft);
            color: var(--s-muted);
            font-size: 0.8125rem;
        }

        .customer-payment-page .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            justify-content: flex-end;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--s-line);
        }

        .customer-payment-page .btn-submit-sale {
            background: var(--s-accent);
            border-color: var(--s-accent);
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            min-width: 140px;
            padding: 0.65rem 1.1rem;
        }

        .customer-payment-page .btn-submit-sale:hover {
            background: #e68a39;
            border-color: #e68a39;
            color: #fff;
        }

        .customer-payment-page .btn-cancel-sale {
            background: #fff;
            border: 1px solid var(--s-line);
            color: var(--s-ink);
            font-weight: 600;
            border-radius: 8px;
            padding: 0.65rem 1.1rem;
        }

        .customer-payment-page .btn-cancel-sale:hover {
            background: var(--s-soft);
            color: var(--s-ink);
        }

        .customer-payment-page .field-error {
            font-size: 0.75rem;
            margin-top: 0.35rem;
        }

        @media (max-width: 767.98px) {
            .customer-payment-page .stat-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .customer-payment-page .amount-row {
                grid-template-columns: 1fr;
                gap: 0.45rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper customer-payment-page">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Customer payment</h4>
                    <h6>Collect due against ledger balance</h6>
                </div>
                <div class="page-btn">
                    @if ($openingDue > 0)
                        <span class="balance-chip">
                            <i class="fa fa-wallet"></i>
                            Due ৳ {{ number_format($openingDue, 2) }}
                        </span>
                    @elseif ($openingDue < 0)
                        <span class="balance-chip is-advance">
                            <i class="fa fa-wallet"></i>
                            Advance ৳ {{ number_format(abs($openingDue), 2) }}
                        </span>
                    @else
                        <span class="balance-chip is-cleared">
                            <i class="fa fa-check"></i>
                            Cleared
                        </span>
                    @endif
                </div>
            </div>

            <form action="{{ route('makeCustomerPayment') }}" method="post" id="jqvalidation">
                @csrf

                <input type="hidden" name="payment_for" value="cus_due_return">

                <div class="row g-3">
                    <div class="col-lg-5">
                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h6>Payment details</h6>
                                    <p class="hint">Customer, date &amp; receiving account</p>
                                </div>
                                <a href="{{ route('customer.show', $customer['id']) }}" class="btn btn-sm btn-light border">
                                    <i class="fa fa-book me-1"></i> Ledger
                                </a>
                            </div>
                            <div class="panel-body">
                                <div class="stat-row">
                                    <div class="stat-box">
                                        <span>Sell</span>
                                        <strong>৳ {{ number_format($sellTotal, 2) }}</strong>
                                    </div>
                                    <div class="stat-box">
                                        <span>Return</span>
                                        <strong>৳ {{ number_format($returnTotal, 2) }}</strong>
                                    </div>
                                    <div class="stat-box">
                                        <span>Paid</span>
                                        <strong>৳ {{ number_format($paymentTotal, 2) }}</strong>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="customer_name">Customer</label>
                                    <input type="hidden" name="customer_id" value="{{ $customer['id'] }}">
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        value="{{ $customerLabel }}" readonly>
                                    @error('customer_id')
                                        <div class="text-danger field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="datetimepicker">Payment date</label>
                                    <div class="date-field">
                                        <input type="text" class="form-control" name="date" id="datetimepicker"
                                            value="{{ old('date') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                                        <img class="date-icon" src="{{ asset('assets/img/icons/calendars.svg') }}"
                                            alt="">
                                    </div>
                                    @error('date')
                                        <div class="text-danger field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label class="form-label" for="paymentMethod_data">Receive in account</label>
                                    <select class="select" name="accounts_id" id="paymentMethod_data">
                                        @forelse ($accounts as $d)
                                            <option value="{{ $d->id }}"
                                                {{ (string) old('accounts_id') === (string) $d->id ? 'selected' : '' }}>
                                                {{ $d->bank_name }} (৳ {{ number_format((float) $d->amount, 2) }})
                                            </option>
                                        @empty
                                            <option value="" disabled>No accounts available</option>
                                        @endforelse
                                    </select>
                                    @error('accounts_id')
                                        <div class="text-danger field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h6>Amount summary</h6>
                                    <p class="hint">Remaining due updates as you type</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="amount-grid">
                                    <div class="amount-row">
                                        <div>
                                            <p class="amount-label">Current due</p>
                                            <span class="amount-sub">Ledger balance</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text"
                                                    value="{{ old('due_amount', number_format($openingDue, 2, '.', '')) }}"
                                                    name="due_amount" id="due_amount" class="form-control"
                                                    inputmode="decimal" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="amount-row is-highlight">
                                        <div>
                                            <p class="amount-label">Collect now</p>
                                            <span class="amount-sub">Amount received today</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text"
                                                    value="{{ old('paid_amount', '0.00') }}"
                                                    name="paid_amount" id="paid_amount" class="form-control"
                                                    inputmode="decimal" autocomplete="off">
                                            </div>
                                            @error('paid_amount')
                                                <div class="text-danger field-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="amount-row is-due" id="remaining_row">
                                        <div>
                                            <p class="amount-label" id="remaining_label">Remaining due</p>
                                            <span class="amount-sub" id="remaining_sub">After this payment</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text"
                                                    value="{{ old('due', number_format(abs($openingDue), 2, '.', '')) }}"
                                                    name="due" id="due" class="form-control"
                                                    inputmode="decimal" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-note">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Collected amount is posted to the customer ledger and added to the selected account.
                                    Amount above current due is kept as customer advance.
                                </div>

                                <div class="form-actions">
                                    <a href="{{ route('customer.index') }}" class="btn btn-cancel-sale">Cancel</a>
                                    <button type="submit" class="btn btn-submit-sale">
                                        <i class="fa fa-check me-1"></i> Save payment
                                    </button>
                                </div>
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
        (function($) {
            "use strict";

            function toNumber(value) {
                var n = parseFloat(String(value).replace(/,/g, ""));
                return Number.isFinite(n) ? n : 0;
            }

            function money(value) {
                return toNumber(value).toFixed(2);
            }

            function recalculateRemaining() {
                var dueAmount = toNumber($("#due_amount").val());
                var paid = toNumber($("#paid_amount").val());
                var remaining = dueAmount - paid;

                if (remaining < 0) {
                    $("#remaining_label").text("Advance");
                    $("#remaining_sub").text("Credit after this payment");
                    $("#due").val(money(Math.abs(remaining)));
                } else {
                    $("#remaining_label").text("Remaining due");
                    $("#remaining_sub").text("After this payment");
                    $("#due").val(money(remaining));
                }
            }

            $(function() {
                if ($(".select").length && typeof $.fn.select2 === "function") {
                    $(".select").select2({
                        width: "100%"
                    });
                }

                var $date = $("#datetimepicker");
                if ($date.length && typeof $date.datetimepicker === "function") {
                    var options = {
                        format: "Y-m-d",
                        timepicker: false,
                        lang: "en",
                        scrollInput: false
                    };
                    if (!$date.val()) {
                        options.value = new Date();
                    }
                    $date.datetimepicker(options);
                }

                $(document).on("input change", "#paid_amount", recalculateRemaining);
                recalculateRemaining();

                var $form = $("#jqvalidation");
                if ($form.length && typeof $form.validate === "function") {
                    $form.validate({
                        rules: {
                            customer_name: "required",
                            date: "required",
                            accounts_id: "required",
                            paid_amount: {
                                required: true,
                                number: true,
                                min: 0.01
                            }
                        },
                        messages: {
                            customer_name: "Customer is required",
                            date: "Please choose a date",
                            accounts_id: "Please select an account",
                            paid_amount: "Enter an amount to collect"
                        }
                    });
                }
            });
        })(jQuery);
    </script>
@endpush
