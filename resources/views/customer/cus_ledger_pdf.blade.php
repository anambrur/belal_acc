<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Customer Ledger — {{ $customer->customer_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }

        .meta {
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .summary {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #ccc;
            padding: 8px;
            width: 25%;
        }

        .summary .label {
            font-size: 10px;
            color: #666;
        }

        .summary .value {
            font-size: 13px;
            font-weight: bold;
        }

        table.ledger {
            width: 100%;
            border-collapse: collapse;
        }

        table.ledger th,
        table.ledger td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }

        table.ledger th {
            background: #e9ecef;
            text-align: left;
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
            margin-top: 18px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Customer Ledger</h1>
    <div class="meta">
        <strong>{{ $customer->customer_name }}</strong><br>
        Mobile: {{ $customer->phone }}<br>
        Address: {{ $customer->road->name ?? '—' }}, {{ $customer->location->address ?? '—' }}<br>
        Date Range:
        @if ($isFiltered)
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '…' }}
            —
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '…' }}
        @else
            All time
        @endif
        <br>
        Generated: {{ $generatedAt->format('d M Y H:i') }}
    </div>

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

    <table class="ledger">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Operation</th>
                <th class="text-end">Debit</th>
                <th class="text-end">Credit</th>
                <th class="text-end">Balance</th>
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
        Formula: Due = Sales − Returns − Payments. Positive balance means customer owes.
    </div>
</body>

</html>
