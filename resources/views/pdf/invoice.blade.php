<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { margin-bottom: 30px; }
        .logo { width: 150px; margin-bottom: 10px; }
        .company-info { margin-bottom: 20px; }
        .company-name { font-weight: 900; font-size: 18px; color: #002D62; text-transform: uppercase; }
        .billing-to { background: #f4f4f4; padding: 10px; font-weight: bold; margin-bottom: 20px; }
        .invoice-details { float: right; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #002D62; color: white; text-align: left; padding: 10px; text-transform: uppercase; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { float: right; width: 250px; }
        .total-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .grand-total { font-weight: 900; color: #B8860B; border-top: 2px solid #333; margin-top: 5px; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-details">
            <h2 style="color: #002D62; margin: 0;">INVOICE</h2>
            <p><strong>DATE:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
            <strong>INVOICE NO:</strong> {{ $order->order_number }}</p>
        </div>
        <div class="company-info">
            <div class="company-name">ORBITA KENYA LTD.</div>
            <p>Display shop: G.floor BBS Mall, 12st Eastleigh Nairobi, Kenya<br>
            Office: Decale palace hotel 2nd floor 12st, Eastleigh, Nairobi Kenya<br>
            Email: Info@orbitakenya.com<br>
            Tel: +254-726-777-733, +254-726-226-666</p>
        </div>
    </div>

    <div class="billing-to">BILL TO: {{ $order->customer_name }}</div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_amount, 2) }}</td>
                <td>{{ number_format($item->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row"><strong>SUBTOTAL:</strong> {{ number_format($order->sub_total, 2) }}</div>
        <div class="total-row"><strong>VAT:</strong> {{ number_format($order->vat, 2) }}</div>
        <div class="total-row grand-total"><strong>TOTAL KSH:</strong> {{ number_format($order->grand_total, 2) }}</div>
    </div>
</body>
</html>