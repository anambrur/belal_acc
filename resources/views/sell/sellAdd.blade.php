@extends('layout')

@section('title')
    Sell Create
@endsection

@push('css')
    <style>
        .sell-create-page {
            --s-accent: #ff9f43;
            --s-ink: #1f2937;
            --s-muted: #6b7280;
            --s-line: #e8ecf1;
            --s-soft: #f7f8fa;
            --s-debit: #e11d48;
            --s-credit: #059669;
        }

        .sell-create-page .page-header {
            margin-bottom: 1.25rem;
        }

        .sell-create-page .page-header .page-title h4 {
            margin-bottom: 0.15rem;
        }

        .sell-create-page .page-header .page-title h6 {
            color: var(--s-muted);
            font-weight: 400;
        }

        .sell-create-page .invoice-chip {
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

        .sell-create-page .panel {
            background: #fff;
            border: 1px solid var(--s-line);
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        .sell-create-page .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.9rem 1.15rem;
            background: var(--s-soft);
            border-bottom: 1px solid var(--s-line);
        }

        .sell-create-page .panel-head h6 {
            margin: 0;
            font-weight: 700;
            color: var(--s-ink);
        }

        .sell-create-page .panel-head .hint {
            margin: 0;
            font-size: 0.75rem;
            color: var(--s-muted);
        }

        .sell-create-page .panel-body {
            padding: 1.15rem;
        }

        .sell-create-page .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--s-muted);
            margin-bottom: 0.4rem;
        }

        .sell-create-page .form-control,
        .sell-create-page .select2-container .select2-selection--single {
            min-height: 44px;
            border-radius: 8px;
            border-color: #dbe1ea;
        }

        .sell-create-page .form-control:focus {
            border-color: var(--s-accent);
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.2);
        }

        .sell-create-page .select2-container .select2-selection--single {
            display: flex;
            align-items: center;
        }

        .sell-create-page .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
            padding-left: 12px;
            color: var(--s-ink);
        }

        .sell-create-page .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .sell-create-page .date-field {
            position: relative;
        }

        .sell-create-page .date-field .form-control {
            padding-right: 2.5rem;
        }

        .sell-create-page .date-field .date-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            opacity: 0.55;
            pointer-events: none;
        }

        .sell-create-page .btn-add-customer {
            background: #fff;
            border: 1px solid #f3e4d4;
            color: #c2410c;
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.8125rem;
            padding: 0.35rem 0.7rem;
        }

        .sell-create-page .btn-add-customer:hover {
            background: var(--s-accent);
            border-color: var(--s-accent);
            color: #fff;
        }

        .sell-create-page .amount-grid {
            display: grid;
            gap: 0.85rem;
        }

        .sell-create-page .amount-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 0.75rem;
            align-items: center;
            padding: 0.85rem 1rem;
            border: 1px solid var(--s-line);
            border-radius: 10px;
            background: #fff;
        }

        .sell-create-page .amount-row.is-highlight {
            background: linear-gradient(135deg, #fff 0%, #fff8f1 100%);
            border-color: #f3e4d4;
        }

        .sell-create-page .amount-row.is-due {
            background: #fff5f5;
            border-color: #fecdd3;
        }

        .sell-create-page .amount-row .amount-label {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--s-ink);
        }

        .sell-create-page .amount-row .amount-sub {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--s-muted);
            margin-top: 0.15rem;
        }

        .sell-create-page .input-with-prefix {
            display: flex;
            align-items: stretch;
            border: 1px solid #dbe1ea;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .sell-create-page .input-with-prefix .prefix {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            background: var(--s-soft);
            border-right: 1px solid #dbe1ea;
            color: var(--s-muted);
            font-weight: 700;
        }

        .sell-create-page .input-with-prefix .form-control {
            border: 0;
            border-radius: 0;
            min-height: 44px;
            box-shadow: none;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .sell-create-page .input-with-prefix .form-control:focus {
            box-shadow: none;
        }

        .sell-create-page .summary-note {
            margin-top: 1rem;
            padding: 0.75rem 0.9rem;
            border-radius: 8px;
            background: var(--s-soft);
            color: var(--s-muted);
            font-size: 0.8125rem;
        }

        .sell-create-page .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            justify-content: flex-end;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--s-line);
        }

        .sell-create-page .btn-submit-sale {
            background: var(--s-accent);
            border-color: var(--s-accent);
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            min-width: 140px;
            padding: 0.65rem 1.1rem;
        }

        .sell-create-page .btn-submit-sale:hover {
            background: #e68a39;
            border-color: #e68a39;
            color: #fff;
        }

        .sell-create-page .btn-cancel-sale {
            background: #fff;
            border: 1px solid var(--s-line);
            color: var(--s-ink);
            font-weight: 600;
            border-radius: 8px;
            padding: 0.65rem 1.1rem;
        }

        .sell-create-page .btn-cancel-sale:hover {
            background: var(--s-soft);
            color: var(--s-ink);
        }

        .sell-create-page .field-error {
            font-size: 0.75rem;
            margin-top: 0.35rem;
        }

        @media (max-width: 575.98px) {
            .sell-create-page .amount-row {
                grid-template-columns: 1fr;
                gap: 0.45rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper sell-create-page">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Create sale</h4>
                    <h6>Record a customer invoice and payment</h6>
                </div>
                <div class="page-btn">
                    <span class="invoice-chip">
                        <i class="fa fa-receipt"></i>
                        Invoice MT {{ $invoice_id }}
                    </span>
                </div>
            </div>

            <form action="{{ route('sell.store') }}" method="post" id="jqvalidation">
                @csrf

                <div class="row g-3">
                    {{-- Left: sale details --}}
                    <div class="col-lg-5">
                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h6>Sale details</h6>
                                    <p class="hint">Customer, date &amp; receiving account</p>
                                </div>
                                <a href="{{ route('customer.create') }}" class="btn btn-add-customer">
                                    <i class="fa fa-plus me-1"></i> Customer
                                </a>
                            </div>
                            <div class="panel-body">
                                <div class="mb-3">
                                    <label class="form-label" for="customer_id">Customer</label>
                                    <select class="select" name="customer_id" id="customer_id">
                                        <option value="">Choose customer</option>
                                        @forelse ($customer as $d)
                                            <option value="{{ $d->id }}"
                                                {{ old('customer_id') == $d->id ? 'selected' : '' }}>
                                                {{ $d->customer_name }}
                                                ({{ $d->location->address ?? '—' }})
                                            </option>
                                        @empty
                                            <option value="" disabled>No customers found</option>
                                        @endforelse
                                    </select>
                                    @error('customer_id')
                                        <div class="text-danger field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="datetimepicker">Sale date</label>
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
                                                {{ old('accounts_id') == $d->id ? 'selected' : '' }}>
                                                {{ $d->bank_name }}
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

                    {{-- Right: amounts --}}
                    <div class="col-lg-7">
                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h6>Payment summary</h6>
                                    <p class="hint">Due updates as you change amounts</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="amount-grid">
                                    <div class="amount-row">
                                        <div>
                                            <p class="amount-label">Sub total</p>
                                            <span class="amount-sub">Before discount</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text" value="{{ old('sub_total', 0) }}" name="sub_total"
                                                    id="sub_total" class="form-control" inputmode="decimal">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="amount-row">
                                        <div>
                                            <p class="amount-label">Discount</p>
                                            <span class="amount-sub">Optional</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text" value="{{ old('discount_price', 0) }}"
                                                    name="discount_price" id="discount_price" class="form-control"
                                                    inputmode="decimal">
                                            </div>
                                            @error('discount_price')
                                                <div class="text-danger field-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="amount-row is-highlight">
                                        <div>
                                            <p class="amount-label">Grand total</p>
                                            <span class="amount-sub">Sub total − discount</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text" value="{{ old('total_price', 0) }}"
                                                    name="total_price" id="total_price" class="form-control"
                                                    inputmode="decimal">
                                            </div>
                                            @error('total_price')
                                                <div class="text-danger field-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="amount-row">
                                        <div>
                                            <p class="amount-label">Paid now</p>
                                            <span class="amount-sub">Goes to selected account</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text" value="{{ old('paid_amount', 0) }}"
                                                    name="paid_amount" id="paid_amount" class="form-control"
                                                    inputmode="decimal">
                                            </div>
                                            @error('paid_amount')
                                                <div class="text-danger field-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="amount-row is-due">
                                        <div>
                                            <p class="amount-label">Due</p>
                                            <span class="amount-sub">Remaining receivable</span>
                                        </div>
                                        <div>
                                            <div class="input-with-prefix">
                                                <span class="prefix">৳</span>
                                                <input type="text" value="{{ old('due', 0) }}" name="due"
                                                    id="due" class="form-control" inputmode="decimal" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-note">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Paid amount is posted to the customer ledger and the selected account balance.
                                </div>

                                <div class="form-actions">
                                    <a href="{{ route('sell.index') }}" class="btn btn-cancel-sale">Cancel</a>
                                    <button type="submit" class="btn btn-submit-sale">
                                        <i class="fa fa-check me-1"></i> Save sale
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
    <script src="{{ asset('assets/js/custom_pos_sell.js') }}"></script>
@endpush
