<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1c1f2a; }
        .header { display: flex; justify-content: space-between; margin-bottom: 24px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        .table th { background: #f8fafc; }
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
                <tr>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table" style="margin-top: 16px;">
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
