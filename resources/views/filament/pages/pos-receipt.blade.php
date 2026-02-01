<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 80mm; /* Standard Thermal Paper Width */
            background: #fff;
            color: #000;
        }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        
        .divider { border-bottom: 1px dashed #000; margin: 8px 0; }
        
        .details { font-size: 10px; margin-bottom: 10px; }
        .details p { margin: 2px 0; }
        
        .item-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .item-name { flex: 1; }
        .item-price { text-align: right; white-space: nowrap; }

        .totals { margin-top: 10px; text-align: right; }
        .totals .row { display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 2px;}
        .grand-total { font-size: 14px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; margin-top: 5px; }
        
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        
        @media print {
            body { margin: 0; padding: 0; width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>ORBITA SMART SOLUTIONS</h1>
        <p>Nairobi, Kenya</p>
        <p>Tel: +254 700 000 000</p>
        <p>Email: sales@orbita.co.ke</p>
        <p>PIN: P051XXXXXXZ</p>
    </div>

    <div class="divider"></div>

    <div class="details">
        <p><strong>Receipt #:</strong> {{ $order->order_number }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i A') }}</p>
        <p><strong>Cashier:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Customer:</strong> {{ $order->user ? $order->user->name : 'Walk-in Guest' }}</p>
    </div>

    <div class="divider"></div>

    <div class="items">
        @foreach($order->items as $item)
            <div class="item-row">
                <span class="item-name">{{ $item->quantity }}x {{ $item->product->name }}</span>
                <span class="item-price">{{ number_format($item->total_price, 2) }}</span>
            </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="totals">
        <div class="row">
            <span>Subtotal:</span>
            <span>{{ number_format($order->sub_total, 2) }}</span>
        </div>
        <div class="row">
            <span>VAT (16%):</span>
            <span>{{ number_format($order->vat, 2) }}</span>
        </div>
        <div class="row grand-total">
            <span>TOTAL:</span>
            <span>KES {{ number_format($order->grand_total, 2) }}</span>
        </div>
        <div class="row" style="margin-top: 5px; font-weight: normal; font-size: 10px;">
            <span>Payment Mode:</span>
            <span>{{ strtoupper($order->payment_method) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>Goods once sold are not returnable.</p>
        <p>-- ORBITA SYSTEMS --</p>
    </div>

</body>
</html>