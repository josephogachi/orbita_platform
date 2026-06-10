@php
    $settings = \App\Models\ShopSetting::first();

    // --- 1. SETTINGS & FALLBACKS ---
    $showroom = $settings->shop_address ?? 'G.floor BBS Mall, 12st Eastleigh Nairobi, Kenya';
    $office = $settings->office_address ?? 'Decale palace hotel 2nd floor 12st, Eastleigh, Nairobi';
    $email = $settings->email_contact ?? 'info@orbitakenya.com';
    $phone = $settings->phone_contact ?? '+254-726-777-733';
    $bankName = $settings->bank_name ?? 'CO-OPERATIVE BANK';
    $accName = $settings->account_name ?? 'ORBITAHTECH SYSTEMS KENYA LTD.';
    $accNumber = $settings->account_number ?? '01100542859001';
    $generatedBy = auth()->user()->name ?? 'System Admin';

    // --- 2. LOGO LOGIC ---
    $logoData = null;
    $rawPath = $settings->logo_path;

    if (is_string($rawPath) && (str_contains($rawPath, '["') || str_contains($rawPath, '[\"'))) {
        $decoded = json_decode($rawPath, true);
        if (is_array($decoded)) { $rawPath = reset($decoded); }
    } elseif (is_array($rawPath)) {
        $rawPath = reset($rawPath);
    }

    if (!empty($rawPath) && $rawPath !== '[]') {
        $fullPath = storage_path('app/public/' . $rawPath);
        if (file_exists($fullPath)) {
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $data = file_get_contents($fullPath);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }

    // --- 3. DYNAMIC CURRENCY & CALCULATIONS ---
    $currency = $order->currency ?? 'KES'; 
    $itemsSubtotal = $order->items->sum(fn($item) => $item->unit_price * $item->quantity);
    $installation = $order->installation_fee ?? 0.00;
    
    // Subtotal includes items + installation before tax
    $subtotal = $itemsSubtotal + $installation; 
    
    $vatRate = $settings->vat_percentage ?? 16; 
    $discountAmount = 0.00;
    $taxableAmount = $subtotal - $discountAmount;
    $taxAmount = $taxableAmount * ($vatRate / 100);
    $shipping = $order->shipping_cost ?? 0.00; 
    $shippingMethod = $order->shipping_method ?? 'Standard Delivery';
    
    $grandTotal = $taxableAmount + $taxAmount + $shipping;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 30px 40px 0 40px; 
            color: #1a2b4b;
            font-size: 11px; 
            background-color: #ffffff;
        }

        .watermark-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -999;
            background-image: url("data:image/svg+xml,%3Csvg width='250' height='250' xmlns='http://www.w3.org/2000/svg'%3E%3Ctext x='10' y='125' font-family='Helvetica, Arial, sans-serif' font-size='14' font-weight='bold' fill='%231a2b4b' fill-opacity='0.03' transform='rotate(-35 125 125)' letter-spacing='3'%3EORBITA KENYA%3C/text%3E%3Cpath d='M0 250 L250 0' stroke='%231a2b4b' stroke-width='0.5' stroke-opacity='0.02'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        .text-right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        
        .header-table td { vertical-align: top; }
        .company-info p { margin: 1px 0; font-size: 10px; color: #555; line-height: 1.3; } 
        
        .invoice-title { font-size: 28px; font-weight: bold; color: #d48d56; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 2px; }
        .invoice-meta p { margin: 1px 0; font-size: 11px; font-weight: bold; }

        .info-bar { margin-top: 15px; border-top: 2px solid #d48d56; border-bottom: 2px solid #d48d56; padding: 8px 0; background: rgba(255,255,255,0.8); }
        .label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
        .value { font-size: 12px; font-weight: bold; color: #000; text-transform: uppercase; line-height: 1.2; }
        .sub-value { font-size: 10px; color: #555; text-transform: none; font-weight: normal; }

        .product-table { margin-top: 15px; width: 100%; background: rgba(255,255,255,0.9); }
        .product-table th { background-color: #1a2b4b; color: #fff; padding: 8px; text-transform: uppercase; font-size: 10px; }
        .product-table td { padding: 6px 8px; border-bottom: 1px solid #e5e5e5; color: #333; vertical-align: middle; }
        .product-table tr:nth-child(even) { background-color: rgba(249,249,249,0.9); }
        
        .item-img-container { width: 45px; height: 45px; text-align: center; background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 2px; margin: 0 auto; }
        .item-img { max-width: 41px; max-height: 41px; vertical-align: middle; }

        .footer-wrapper { position: fixed; bottom: 35px; left: 40px; right: 40px; height: auto; background: rgba(255,255,255,0.95); }
        .bank-box { background: #f3f4f6; border-left: 4px solid #d48d56; padding: 10px 12px; font-size: 10px; line-height: 1.5; color: #333; }
        
        .totals-table td { padding: 4px 0; font-size: 11px; }
        .grand-total { border-top: 2px solid #1a2b4b; border-bottom: 2px solid #1a2b4b; font-size: 13px; font-weight: bold; color: #1a2b4b; padding: 8px 0; }

        .divider-line { border-bottom: 1px solid #ddd; margin: 12px 0; }
        .terms-text { font-size: 8px; color: #777; text-align: justify; line-height: 1.3; margin-bottom: 5px; }

        .bottom-bar { position: fixed; bottom: 0; left: 0; right: 0; height: 25px; background: #1a2b4b; color: #fff; text-align: center; line-height: 25px; font-size: 8px; text-transform: uppercase; letter-spacing: 1px; }
        
        .ribbon { position: absolute; top: 0; right: 0; width: 100px; height: 100px; overflow: hidden; }
        .ribbon-content { position: absolute; top: 20px; right: -30px; width: 140px; background: #009933; color: #fff; text-align: center; transform: rotate(45deg); font-weight: bold; font-size: 11px; padding: 5px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.2); letter-spacing: 1px; }
        .unpaid { background: #cc0000; }
    </style>
</head>
<body>

    <div class="watermark-bg"></div>

    <div class="ribbon">
        <div class="ribbon-content {{ $order->payment_status !== 'paid' ? 'unpaid' : '' }}">
            {{ $order->payment_status === 'paid' ? 'PAID' : 'UNPAID' }}
        </div>
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                @if($logoData)
                    <img src="{{ $logoData }}" style="height: 65px; max-width: 250px; margin-bottom: 5px; object-fit: contain;" alt="Orbita Logo">
                @else
                    <h1 style="color:#1a2b4b; margin:0; font-size: 28px; margin-bottom: 5px;">ORBITA KENYA</h1>
                @endif
                
                <div class="company-info">
                    <p><strong>Showroom:</strong> {{ $showroom }}</p>
                    <p><strong>Office:</strong> {{ $office }}</p>
                    <p><strong>Email:</strong> {{ $email }} | <strong>Tel:</strong> {{ $phone }}</p>
                </div>
            </td>

            <td style="width: 40%; text-align: right; vertical-align: top;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <p>Date: <span style="font-weight: normal;">{{ $order->created_at->format('d/m/Y') }}</span></p>
                    <p>Invoice No: <span style="font-weight: normal;">#{{ str_pad($order->id, 7, '0', STR_PAD_LEFT) }}</span></p>
                </div>
            </td>
        </tr>
    </table>

   <div class="info-bar">
    <table>
        <tr>
            <td style="width: 45%;">
                <div class="label">Invoiced To:</div>
                <div class="value">
                    @if($order->client_company)
                        {{ $order->client_company }}<br>
                        <span class="sub-value">ATTN: {{ $order->client_name ?? 'Purchasing Dept' }}</span>
                    @elseif($order->client_name)
                        {{ $order->client_name }}
                    @else
                        {{ $order->user->name ?? 'Guest Customer' }}
                    @endif
                </div>
                <div style="font-size: 10px; color: #333; margin-top: 2px;">{{ $order->shipping_address }}</div>
            </td>
            <td style="width: 25%; text-align: center;">
                <div class="label">Shipping Via:</div>
                <div class="value" style="color: #d48d56;">{{ strtoupper($shippingMethod) }}</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div class="label">Generated By:</div>
                <div class="value">{{ $generatedBy }}</div>
            </td>
        </tr>
    </table>
</div>

    <table class="product-table">
        <thead>
            <tr>
                <th style="text-align: left;">Item</th>
                <th style="width: 60px; text-align: center;">Image</th>
                <th style="text-align: center; width: 80px;">Price</th>
                <th style="text-align: center; width: 40px;">Qty</th>
                <th style="text-align: right; width: 90px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            @php
                $itemImageData = null;
                $imgPath = null;

                if ($item->is_custom_item && $item->custom_image) {
                    $imgPath = $item->custom_image;
                } elseif (!$item->is_custom_item && $item->product) {
                    $images = $item->product->images;
                    if (is_string($images)) {
                        $decoded = json_decode($images, true);
                        $imgPath = is_array($decoded) ? ($decoded[0] ?? null) : null;
                    } elseif (is_array($images)) {
                        $imgPath = $images[0] ?? null;
                    }
                }

                if ($imgPath) {
                    $fullItemPath = storage_path('app/public/' . $imgPath);
                    if (file_exists($fullItemPath)) {
                        $itemType = pathinfo($fullItemPath, PATHINFO_EXTENSION);
                        $itemData = file_get_contents($fullItemPath);
                        $itemImageData = 'data:image/' . $itemType . ';base64,' . base64_encode($itemData);
                    }
                }

                $itemName = $item->is_custom_item ? $item->custom_name : ($item->product->name ?? 'Unknown Product');
            @endphp
            <tr>
                <td>
                    <strong style="font-size: 12px;">{{ $itemName }}</strong>
                </td>
                <td style="text-align: center;">
                    <div class="item-img-container">
                        @if($itemImageData)
                            <img src="{{ $itemImageData }}" class="item-img">
                        @else
                            <div style="font-size: 7px; color: #bbb; margin-top: 15px;">NO IMAGE</div>
                        @endif
                    </div>
                </td>
                <td style="text-align: center;">{{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-wrapper">
        <table style="margin-bottom: 15px;">
            <tr>
                <td style="width: 55%; padding-right: 20px; vertical-align: top;">
                    <div class="label" style="margin-bottom: 4px;">Payment Details:</div>
                    <div class="bank-box">
                        <strong>BANK:</strong> {{ $bankName }}<br>
                        <strong>ACC NAME:</strong> {{ $accName }}<br>
                        <strong>ACC NO:</strong> {{ $accNumber }}<br>
                        <strong>PAYBILL:</strong> N/A (Contact Admin)
                    </div>
                    {{-- 🌟 Show Exchange Rate used for transparency on USD Invoices --}}
                    @if($currency === 'USD')
                        <div style="margin-top: 10px; font-size: 9px; color: #666; font-style: italic;">
                            *Converted at 1 USD = {{ number_format($order->exchange_rate, 2) }} KES
                        </div>
                    @endif
                </td>

                <td style="width: 45%; vertical-align: top;">
                    <table class="totals-table">
                        <tr>
                            <td>ITEMS SUBTOTAL:</td>
                            <td class="text-right">{{ $currency }} {{ number_format($itemsSubtotal, 2) }}</td>
                        </tr>
                        @if($installation > 0)
                        <tr>
                            <td>INSTALLATION FEE:</td>
                            <td class="text-right">{{ $currency }} {{ number_format($installation, 2) }}</td>
                        </tr>
                        @endif
                        @if($discountAmount > 0)
                        <tr>
                            <td style="color: green;">DISCOUNT:</td>
                            <td class="text-right" style="color: green;">- {{ $currency }} {{ number_format($discountAmount, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>TAX ({{ $vatRate }}%):</td>
                            <td class="text-right">{{ $currency }} {{ number_format($taxAmount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>SHIPPING/HANDLING:</td>
                            <td class="text-right">{{ $currency }} {{ number_format($shipping, 2) }}</td>
                        </tr>
                        <tr><td colspan="2" style="height: 4px;"></td></tr>
                        <tr class="grand-total">
                            <td>BALANCE DUE:</td>
                            <td class="text-right">{{ $currency }} {{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="divider-line"></div>
        
        <div class="label">Terms & Conditions:</div>
        <div class="terms-text">
            All products supplied by Orbita Kenya are covered by a one (1) year warranty against manufacturing defects from the date of purchase. This warranty does not cover damage resulting from misuse, negligence, improper installation, accidents, unauthorized modifications, or any form of human-caused damage. This invoice is system-generated by Orbita Kenya and serves as an official record of payment status. Ownership of the goods remains with Orbita Kenya until full payment is received.
        </div>
    </div>

    <div class="bottom-bar">
        THIS INVOICE IS COMPUTER GENERATED AND IS VALID WITHOUT A SIGNATURE.
    </div>

</body>
</html>