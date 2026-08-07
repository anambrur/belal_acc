<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Customer Ledger — {{ $customer->customer_name }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            margin: 0;
            padding: 24px;
        }

        .invoice-header {
            width: 100%;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .invoice-header td {
            vertical-align: top;
        }

        .logo {
            max-height: 64px;
            max-width: 140px;
        }

        .store-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a5f;
            margin: 0 0 2px;
        }

        .store-meta {
            font-size: 10px;
            color: #444;
            line-height: 1.55;
            margin: 0;
        }

        .doc-title-row {
            width: 100%;
            margin-bottom: 16px;
        }

        .doc-title-row td {
            vertical-align: middle;
        }

        .doc-badge {
            display: inline-block;
            background: #1e3a5f;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 8px 14px;
        }

        .doc-meta {
            text-align: right;
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }

        .bill-to {
            width: 100%;
            margin-bottom: 16px;
            border: 1px solid #d0d7de;
            background: #f6f8fa;
        }

        .bill-to td {
            padding: 10px 12px;
            vertical-align: top;
        }

        .bill-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1e3a5f;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .bill-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .bill-detail {
            font-size: 10px;
            color: #444;
            line-height: 1.5;
        }

        .summary {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #d0d7de;
            padding: 10px 8px;
            text-align: center;
            background: #fff;
        }

        .summary .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            margin-bottom: 4px;
        }

        .summary .value {
            font-size: 13px;
            font-weight: bold;
        }

        table.ledger {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        table.ledger th {
            background: #1e3a5f;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #1e3a5f;
        }

        table.ledger td {
            border: 1px solid #d0d7de;
            padding: 7px 8px;
            font-size: 10px;
        }

        table.ledger tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        table.ledger tfoot td {
            background: #eef2f7;
            font-weight: bold;
            border-top: 2px solid #1e3a5f;
        }

        .text-end {
            text-align: right;
        }

        .text-danger {
            color: #c0392b;
        }

        .text-success {
            color: #1e8449;
        }

        .muted {
            color: #888;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #d0d7de;
            font-size: 9px;
            color: #666;
            line-height: 1.5;
        }

        .footer-store {
            font-weight: bold;
            color: #1e3a5f;
        }
    </style>
</head>

<body>
    @php
        $storeName = $store->store_name ?? 'Store';
        $logoPath = $store?->logo_path ?? public_path('assets/img/anamsLogo.png');
    @endphp

    {{-- Store header (invoice style) --}}
    <table class="invoice-header">
        <tr>
            <td width="28%">
                @if (file_exists($logoPath))
                    <img class="logo" src="{{ $logoPath }}" alt="{{ $storeName }}">
                @endif
            </td>
            <td width="72%" style="text-align: right;">
                <p class="store-name">{{ $storeName }}</p>
                <p class="store-meta">
                    @if (!empty($store?->owner_name))
                        {{ $store->owner_name }}<br>
                    @endif
                    @if (!empty($store?->phone))
                        Mobile: {{ $store->phone }}<br>
                    @endif
                    @if (!empty($store?->email))
                        Email: {{ $store->email }}<br>
                    @endif
                    @if (!empty($store?->full_address))
                        {{ $store->full_address }}
                    @endif
                </p>
            </td>
        </tr>
    </table>

    {{-- Document title --}}
    <table class="doc-title-row">
        <tr>
            <td width="50%">
                <span class="doc-badge">CUSTOMER LEDGER</span>
            </td>
            <td width="50%" class="doc-meta">
                Generated: {{ $generatedAt->format('d M Y H:i') }}<br>
                Period:
                @if ($isFiltered)
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '…' }}
                    —
                    {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '…' }}
                @else
                    All time
                @endif
            </td>
        </tr>
    </table>

    {{-- Bill To --}}
    <table class="bill-to">
        <tr>
            <td>
                <div class="bill-label">Bill To</div>
                <div class="bill-name">{{ $customer->customer_name }}</div>
                <div class="bill-detail">
                    Mobile: {{ $customer->phone }}<br>
                    Address: {{ $customer->road->name ?? '—' }}, {{ $customer->location->address ?? '—' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- KPI summary --}}
    <table class="summary">
        <tr>
            @if ($isFiltered)
                <td>
                    <div class="label">Opening Balance</div>
                    <div class="value {{ $openingBalance > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($openingBalance, 2) }}
                    </div>
                </td>
            @endif
            <td>
                <div class="label">Total Debit</div>
                <div class="value text-danger">{{ number_format($debitTotal, 2) }}</div>
            </td>
            <td>
                <div class="label">Total Credit</div>
                <div class="value text-success">{{ number_format($creditTotal, 2) }}</div>
            </td>
            <td>
                <div class="label">Closing Due</div>
                <div class="value {{ $dueTotal > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($dueTotal, 2) }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Ledger table --}}
    <table class="ledger">
        <thead>
            <tr>
                <th width="6%">#</th>
                <th width="14%">Date</th>
                <th width="36%">Operation</th>
                <th width="14%" class="text-end">Debit</th>
                <th width="14%" class="text-end">Credit</th>
                <th width="16%" class="text-end">Balance</th>
            </tr>
        </thead>
        <tbody>
            @if ($isFiltered && abs($openingBalance) > 0.00001)
                <tr>
                    <td>—</td>
                    <td>{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '—' }}</td>
                    <td>Opening Balance</td>
                    <td class="text-end muted">—</td>
                    <td class="text-end muted">—</td>
                    <td class="text-end {{ $openingBalance > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($openingBalance, 2) }}
                    </td>
                </tr>
            @endif

            @forelse ($entries as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->date)->format('d M Y') }}</td>
                    <td>
                        @if ($d->type === 'sale')
                            Sale — {{ $d->reference }}
                        @elseif ($d->type === 'return')
                            Return — {{ $d->reference }}
                        @else
                            {{ $d->reference }}
                        @endif
                    </td>
                    <td class="text-end">
                        {{ $d->debit > 0 ? number_format($d->debit, 2) : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $d->credit > 0 ? number_format($d->credit, 2) : '—' }}
                    </td>
                    <td class="text-end {{ $d->running_balance > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($d->running_balance, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Totals</strong></td>
                <td class="text-end text-danger"><strong>{{ number_format($debitTotal, 2) }}</strong></td>
                <td class="text-end text-success"><strong>{{ number_format($creditTotal, 2) }}</strong></td>
                <td class="text-end {{ $dueTotal > 0 ? 'text-danger' : 'text-success' }}">
                    <strong>{{ number_format($dueTotal, 2) }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <span class="footer-store">{{ $storeName }}</span><br>
        {{ $store?->invoice_footer ?? 'Formula: Due = Sales − Returns − Payments. Positive balance means customer owes.' }}
    </div>
</body>

</html>
