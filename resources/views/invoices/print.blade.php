<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans 3', 'Segoe UI', sans-serif;
            color: #1e2935;
            margin: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #d7dee5;
        }
        h2 {
            margin: 0 0 6px;
            font-family: 'Merriweather', Georgia, serif;
            color: #17202a;
            letter-spacing: 0.02em;
        }
        p {
            margin: 0 0 4px;
            color: #5d6b78;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d7dee5;
        }
        .table th,
        .table td {
            border: 1px solid #d7dee5;
            padding: 9px;
            text-align: left;
        }
        .table th {
            background: #f1f4f7;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 11px;
        }
        .totals {
            margin-top: 16px;
        }
        .totals th {
            width: 220px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2>Room Rental Invoice</h2>
            <p>Invoice: {{ $invoice->invoice_no }}</p>
            <p>Date: {{ $invoice->invoice_date?->format('Y-m-d') }}</p>
        </div>
        <div>
            <strong>Customer</strong>
            <p>{{ $invoice->rental->customer->full_name ?? '-' }}</p>
            <p>Room: {{ $invoice->rental->room->name ?? '-' }}</p>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                @if ($item->type === 'service')
                    @continue
                @endif
                <tr>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table totals">
        <tbody>
            <tr>
                <th>Total Amount</th>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Total Paid</th>
                <td>{{ number_format($invoice->total_paid, 2) }}</td>
            </tr>
            <tr>
                <th>Due Amount</th>
                <td>{{ number_format($invoice->due_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
