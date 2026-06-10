@php
    $settings = \App\Models\ShopSetting::first();
    $currency = $order->currency ?? 'KES';
    $vatRate = $settings->vat_percentage ?? 16;
    
    // 1. Calculate the base costs exactly like the A4 Invoice
    $itemsSubtotal = $order->items->sum(fn($item) => $item->unit_price * $item->quantity);
    $installation = $order->installation_fee ?? 0.00;
    $shipping = $order->shipping_cost ?? 0.00;

    // 2. FORWARD MATH (The "Serious" Fix)
    // Items + Installation are the taxable base
    $taxableBase = $itemsSubtotal + $installation;
    
    // VAT is calculated on the taxable base
    $vatAmount = $taxableBase * ($vatRate / 100);
    
    // Net Total (Excl) is the base before tax
    $netTotalExcl = $taxableBase;

    // Grand Total matches the database and the A4 Invoice logic
    $grandTotal = $order->grand_total; 
    
    $salesPerson = auth()->user()->name ?? 'Admin';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Courier', monospace; 
            width: 58mm; 
            margin: 0; 
            padding: 4mm; 
            font-size: 11px; 
            line-height: 1.2;
            color: #000;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .dashed-line { border-bottom: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        .text-right { text-align: right; }
        .footer { font-size: 9px; margin-top: 8px; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="center">
        <span class="bold" style="font-size: 15px;">ORBITA KENYA</span><br>
        {{ $settings->shop_address ?? 'Eastleigh, Nairobi' }}<br>
        Tel: {{ $settings->phone_contact ?? '+254 726 777 733' }}
    </div>

    <div class="dashed-line"></div>
    
    <div style="font-size: 10px;">
        Order: #{{ $order->order_number }}<br>
        Date: {{ $order->created_at->format('d/m/y H:i') }}<br>
        Served by: {{ $salesPerson }}<br>
        Client: {{ $order->client_company ?? $order->client_name ?? 'Guest' }}
    </div>

    <div class="dashed-line"></div>

    {{-- ITEMS SECTION --}}
    <table>
        <thead>
            <tr>
                <th align="left">Description</th>
                <th align="center">Qty</th>
                <th align="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td colspan="3" style="padding-top: 4px;">{{ $item->is_custom_item ? $item->custom_name : $item->product->name }}</td>
            </tr>
            <tr>
                <td style="font-size: 9px;">@ {{ number_format($item->unit_price, 2) }}</td>
                <td align="center">x{{ $item->quantity }}</td>
                <td align="right">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach

            {{-- SERVICES LISTED AS ITEMS --}}
            @if($installation > 0)
            <tr>
                <td colspan="2" style="padding-top: 4px;">Lock Installation</td>
                <td align="right" style="padding-top: 4px;">{{ number_format($installation, 2) }}</td>
            </tr>
            @endif

            @if($shipping > 0)
            <tr>
                <td colspan="2" style="padding-top: 2px;">Shipping/Delivery</td>
                <td align="right" style="padding-top: 2px;">{{ number_format($shipping, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="dashed-line"></div>

    {{-- CALCULATION SUMMARY --}}
    <table>
        <tr>
            <td>NET TOTAL (Excl):</td>
            <td class="text-right">{{ number_format($netTotalExcl, 2) }}</td>
        </tr>
        <tr>
            <td>VAT ({{ $vatRate }}%):</td>
            <td class="text-right">{{ number_format($vatAmount, 2) }}</td>
        </tr>
        <tr>
            <td class="bold" style="font-size: 13px; padding-top: 5px;">BALANCE DUE:</td>
            <td class="text-right bold" style="font-size: 13px; padding-top: 5px;">{{ $currency }} {{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    {{-- USD CONVERSION --}}
    @if($currency === 'USD')
    <div class="center" style="font-size: 9px; font-style: italic;">
        Rate: 1 USD = {{ number_format($order->exchange_rate, 2) }} KES<br>
        Payable KES: {{ number_format($grandTotal * $order->exchange_rate, 0) }}
    </div>
    <div class="dashed-line"></div>
    @endif

    <div class="center footer">
        Goods remain property of Orbita Kenya<br>
        until paid in full. Thank you!<br>
        www.orbitakenya.com
    </div>
</body>
</html>