<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation #{{ $quotation->quotation_number }}</title>
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px 50px 0 50px;
            color: #1a2b4b; /* Orbita Blue */
            font-size: 12px;
            background: #fff;
        }

        /* --- UTILITIES --- */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .c-gold { color: #d48d56; }
        
        table { width: 100%; border-collapse: collapse; }
        
        /* --- HEADER --- */
        .header-table td { vertical-align: top; }
        .company-info p { margin: 2px 0; font-size: 11px; color: #555; }
        
        .invoice-title {
            font-size: 28px; font-weight: bold; color: #1a2b4b;
            text-transform: uppercase; margin-bottom: 5px; letter-spacing: 2px;
        }
        .invoice-meta p { margin: 2px 0; font-size: 11px; color: #555; }

        /* --- INFO BAR --- */
        .info-bar { margin-top: 20px; border-top: 2px solid #1a2b4b; border-bottom: 2px solid #1a2b4b; padding: 10px 0; }
        .label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .value { font-size: 13px; font-weight: bold; color: #000; text-transform: uppercase; margin-top: 3px; }

        /* --- PRODUCT TABLE --- */
        .product-table { margin-top: 20px; width: 100%; }
        .product-table th {
            background-color: #1a2b4b; color: #fff;
            padding: 8px 10px; text-transform: uppercase; font-size: 11px; text-align: left;
        }
        .product-table td {
            padding: 10px; border-bottom: 1px solid #eee; color: #333; font-size: 11px;
        }
        .product-table tr:nth-child(even) { background-color: #f9f9f9; }

        /* --- FIXED FOOTER SECTION --- */
        .footer-wrapper {
            position: fixed;
            bottom: 40px;
            left: 50px;
            right: 50px;
            height: auto;
        }

        .bank-box {
            background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px;
            padding: 10px 15px; font-size: 11px; line-height: 1.6; color: #444;
        }
        
        .totals-table td { padding: 5px 0; font-size: 12px; }
        .grand-total { 
            border-top: 2px solid #1a2b4b; border-bottom: 2px solid #1a2b4b;
            font-size: 14px; font-weight: bold; color: #1a2b4b; padding: 8px 0; background-color: #f8fafc;
        }

        .divider-line { border-bottom: 1px solid #cbd5e1; margin: 15px 0; }
        .terms-text { font-size: 9px; color: #555; text-align: justify; line-height: 1.5; margin-bottom: 10px; }

        .bottom-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            height: 30px; background: #1a2b4b; color: #fff;
            text-align: center; line-height: 30px; font-size: 9px; text-transform: uppercase; letter-spacing: 1px;
        }
        
        .ribbon {
            position: absolute; top: 0; right: 0; width: 100px; height: 100px; overflow: hidden;
        }
        .ribbon-content {
            position: absolute; top: 20px; right: -30px; width: 150px;
            background: #d48d56; color: #fff; text-align: center;
            transform: rotate(45deg); font-weight: bold; font-size: 10px; padding: 5px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    {{-- RIBBON --}}
    <div class="ribbon">
        <div class="ribbon-content">
            VALID 30 DAYS
        </div>
    </div>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                {{-- DYNAMIC LOGO --}}
                {{-- DYNAMIC LOGO (Using Base64 for guaranteed DOMPDF rendering) --}}
@php
    $logoPath = '/home/orbitate/public_html/images/orbita-logo.png';
    $logoSrc = '';
    if (file_exists($logoPath)) {
        $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
@endphp

@if($logoSrc)
    <img src="{{ $logoSrc }}" style="max-height: 70px; max-width: 220px; margin-bottom: 10px;" alt="Orbita Kenya">
@else
    <h1 style="color:#1a2b4b; margin:0; font-size: 28px; font-weight: bold; margin-bottom: 10px;">ORBITA KENYA</h1>
@endif
                
                @php $settings = \App\Models\ShopSetting::first(); @endphp
                <div class="company-info">
                    <p><strong>Show Room:</strong> G.floor BBS Mall, 12st Eastleigh Nairobi, Kenya</p>
                    <p><strong>Office:</strong> Decale palace hotel 2nd floor 12st, Eastleigh, Nairobi Kenya</p>
                    <p><strong>Email:</strong> {{ $settings->email_contact ?? 'Info@orbitakenya.com' }}</p>
                    <p><strong>Tel:</strong> +254-726-777-733 | +254-726-226-666</p>
                </div>
            </td>

            <td style="width: 45%; text-align: right; vertical-align: top;">
                <div class="invoice-title">QUOTATION</div>
                <div class="invoice-meta">
                    <p><strong>Date Generated:</strong> {{ $quotation->created_at->format('m/d/Y') }}</p>
                    <p><strong>QUOTATION No.</strong> {{ $quotation->quotation_number }}</p>
                    <p><strong>GENERATED BY:</strong> <span class="uppercase">{{ auth()->user() ? auth()->user()->name : 'SYSTEM' }}</span></p>
                </div>
            </td>
        </tr>
    </table>

    {{-- INFO BAR --}}
    <div class="info-bar">
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="label">QUOTED TO:</div>
                    <div class="value">{{ $quotation->client_name }}</div>
                    @if($quotation->hotel_name || $quotation->client_phone)
                        <div style="font-size: 11px; color: #555; margin-top: 3px;">
                            {{ $quotation->hotel_name ? $quotation->hotel_name . ' | ' : '' }} 
                            {{ $quotation->client_phone ?? '' }}
                        </div>
                    @endif
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="label">Status:</div>
                    <div class="value" style="color: #1a2b4b;">{{ strtoupper($quotation->status) }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- PRODUCT TABLE --}}
    <table class="product-table">
        <thead>
            <tr>
                <th style="width: 50%;">PRODUCT</th>
                <th style="width: 15%; text-align: right;">PRICE</th>
                <th style="width: 15%; text-align: center;">QTY</th>
                <th style="width: 20%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @if(is_array($quotation->items))
                @foreach($quotation->items as $item)
                <tr>
                    <td class="uppercase">{{ $item['description'] ?? 'Item' }}</td>
                    <td style="text-align: right;">Ksh. {{ number_format((float)($item['price'] ?? 0), 2) }}</td>
                    <td style="text-align: center;">{{ $item['quantity'] ?? 1 }} PC</td>
                    <td style="text-align: right; font-weight: bold;">Ksh. {{ number_format(((float)($item['price'] ?? 0) * (float)($item['quantity'] ?? 1)), 2) }}</td>
                </tr>
                @endforeach
            @endif

            {{-- Additional Fees --}}
            @if($quotation->installation_fee > 0)
                <tr>
                    <td>INSTALLATION FEE</td>
                    <td class="text-right">-</td><td class="text-center">-</td>
                    <td class="text-right text-bold">Ksh. {{ number_format($quotation->installation_fee, 2) }}</td>
                </tr>
            @endif
            @if($quotation->shipping_fee > 0)
                <tr>
                    <td>SHIPPING & HANDLING</td>
                    <td class="text-right">-</td><td class="text-center">-</td>
                    <td class="text-right text-bold">Ksh. {{ number_format($quotation->shipping_fee, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- FIXED FOOTER SECTION --}}
    <div class="footer-wrapper">
        <table style="margin-bottom: 20px;">
            <tr>
                {{-- LEFT: BANK DETAILS --}}
                <td style="width: 55%; padding-right: 30px; vertical-align: top;">
                    <div class="bank-box">
                        <strong style="color: #1a2b4b; display: block; margin-bottom: 5px;">BANK DETAILS</strong>
                        <strong>BANK NAME:</strong> CO-OPERATIVE BANK ACC<br>
                        <strong>NAME:</strong> ORBITAHTECH SYSTEMS KENYA LTD.<br>
                        <strong>ACC NO:</strong> 01100542859001
                    </div>
                </td>

                {{-- RIGHT: TOTALS --}}
                <td style="width: 45%; vertical-align: top;">
                    <table class="totals-table">
                        <tr>
                            <td style="color:#555;">NET AMOUNT:</td>
                            <td class="text-right">{{ number_format($quotation->subtotal, 2) }}</td>
                        </tr>
                        @if($quotation->is_vat_inclusive && $quotation->vat_amount > 0)
                        <tr>
                            <td style="color:#555;">VAT (16%):</td>
                            <td class="text-right">{{ number_format($quotation->vat_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr><td colspan="2" style="height: 5px;"></td></tr>
                        <tr class="grand-total">
                            <td>TOTAL: KSH.</td>
                            <td class="text-right">{{ number_format($quotation->total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- TERMS AND CONDITIONS --}}
        <div class="label" style="color: #1a2b4b; font-weight: bold; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; margin-bottom: 8px;">TERMS AND CONDITIONS</div>
        <div class="terms-text">
            All products supplied by Orbita Kenya are covered by a one (1) year warranty against manufacturing defects from the date of purchase. This warranty does not cover damage resulting from misuse, negligence, improper installation, accidents, unauthorized modifications, or any form of human-caused damage. Ownership of the goods remains with Orbita Kenya until full payment is received. By accepting this invoice, the client agrees to these terms and conditions.
        </div>
        
        @if($quotation->notes)
            <div style="margin-top: 5px; padding: 8px; background-color:#f8fafc; font-size:10px; border-left: 3px solid #1a2b4b;">
                <strong>Additional Notes:</strong><br>
                {{ $quotation->notes }}
            </div>
        @endif
    </div>

    <div class="bottom-bar">
        This invoice is system-generated by Orbita Kenya
    </div>

</body>
</html>