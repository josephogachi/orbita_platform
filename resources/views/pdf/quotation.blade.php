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
        .logo-img { height: 60px; max-width: 200px; margin-bottom: 10px; }
        .company-info p { margin: 2px 0; font-size: 11px; color: #555; }
        
        .invoice-title {
            font-size: 32px; font-weight: bold; color: #d48d56;
            text-transform: uppercase; margin-bottom: 5px;
        }
        .invoice-meta p { margin: 2px 0; font-size: 12px; }

        /* --- INFO BAR --- */
        .info-bar { margin-top: 20px; border-top: 2px solid #d48d56; border-bottom: 2px solid #d48d56; padding: 10px 0; }
        .label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .value { font-size: 13px; font-weight: bold; color: #000; text-transform: uppercase; }

        /* --- PRODUCT TABLE --- */
        .product-table { margin-top: 20px; width: 100%; }
        .product-table th {
            background-color: #1a2b4b; color: #fff;
            padding: 8px 10px; text-transform: uppercase; font-size: 11px; text-align: left;
        }
        .product-table td {
            padding: 10px; border-bottom: 1px solid #eee; color: #444;
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
            background: #f3f4f6; border-left: 4px solid #d48d56;
            padding: 10px 15px; font-size: 11px; line-height: 1.6; color: #444;
        }
        
        .totals-table td { padding: 4px 0; font-size: 12px; }
        .grand-total { 
            border-top: 2px solid #1a2b4b; border-bottom: 2px solid #1a2b4b;
            font-size: 14px; font-weight: bold; color: #1a2b4b; padding: 8px 0;
        }

        .divider-line { border-bottom: 1px solid #ddd; margin: 15px 0; }
        .terms-text { font-size: 9px; color: #777; text-align: justify; line-height: 1.3; margin-bottom: 10px; }

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
            <td style="width: 60%; vertical-align: top;">
                {{-- Dynamic Logo from Settings --}}
                @php $settings = \App\Models\ShopSetting::first(); @endphp
                <h1 style="color:#1a2b4b; margin:0; font-size: 24px;">ORBITA KENYA</h1>
                
                <div class="company-info" style="margin-top: 5px;">
                    <p><strong>Showroom:</strong> BBS Mall, Ground Fl Zone E Shop 102</p>
                    <p><strong>Office:</strong> {{ $settings->office_address ?? 'Decale Palace Hotel 2nd Floor' }}</p>
                    <p><strong>Email:</strong> {{ $settings->email_contact ?? 'sales@orbita.co.ke' }} | <strong>Tel:</strong> {{ $settings->phone_contact ?? '+254 726 777 733' }}</p>
                </div>
            </td>

            <td style="width: 40%; text-align: right; vertical-align: top;">
                <div class="invoice-title">QUOTATION</div>
                <div class="invoice-meta">
                    <p><strong>Date:</strong> {{ $quotation->created_at->format('d/m/Y') }}</p>
                    <p><strong>Quotation No:</strong> {{ $quotation->quotation_number }}</p>
                </div>
            </td>
        </tr>
    </table>

    {{-- INFO BAR --}}
    <div class="info-bar">
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="label">Quoted To:</div>
                    <div class="value">{{ $quotation->client_name }}</div>
                    <div style="font-size: 11px; color: #555;">{{ $quotation->hotel_name }} | {{ $quotation->client_email }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="label">Status:</div>
                    <div class="value" style="color: #d48d56;">{{ strtoupper($quotation->status) }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- PRODUCT TABLE --}}
    <table class="product-table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th style="text-align: center;">Price</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            <tr>
                <td>
                    <strong>{{ $item['description'] }}</strong>
                </td>
                <td style="text-align: center;">{{ number_format($item['price'], 2) }}</td>
                <td style="text-align: center;">{{ $item['quantity'] }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- FIXED FOOTER SECTION --}}
    <div class="footer-wrapper">
        <table style="margin-bottom: 20px;">
            <tr>
                {{-- LEFT: BANK DETAILS --}}
                <td style="width: 55%; padding-right: 30px; vertical-align: top;">
                    <div class="label" style="margin-bottom: 5px;">Bank Details for Payment:</div>
                    <div class="bank-box">
                        <strong>BANK:</strong> {{ $settings->bank_name ?? 'COOPERATIVE BANK' }}<br>
                        <strong>PYBILL:</strong> 400200
                        <strong>ACC NAME:</strong> {{ $settings->account_name ?? 'ORBITA KENYA LTD' }}<br>
                        <strong>ACC No:</strong> {{ $settings->account_number ?? '01100542859001' }}<br>
                        
                    </div>
                </td>

                {{-- RIGHT: TOTALS --}}
                <td style="width: 45%; vertical-align: top;">
                    <table class="totals-table">
                        <tr>
                            <td>PRODUCTS SUBTOTAL:</td>
                            <td class="text-right">{{ number_format($quotation->subtotal, 2) }}</td>
                        </tr>
                        @if($quotation->installation_fee > 0)
                        <tr>
                            <td>INSTALLATION FEE:</td>
                            <td class="text-right">{{ number_format($quotation->installation_fee, 2) }}</td>
                        </tr>
                        @endif
                        @if($quotation->shipping_fee > 0)
                        <tr>
                            <td>SHIPPING & HANDLING:</td>
                            <td class="text-right">{{ number_format($quotation->shipping_fee, 2) }}</td>
                        </tr>
                        @endif
                        @if($quotation->has_maintenance)
                        <tr>
                            <td>MAINTENANCE SUBSCRIPTION:</td>
                            <td class="text-right">{{ number_format($quotation->maintenance_fee, 2) }}</td>
                        </tr>
                        @endif
                        <tr><td colspan="2" style="height: 5px;"></td></tr>
                        <tr class="grand-total">
                            <td>ESTIMATED TOTAL:</td>
                            <td class="text-right">KES {{ number_format($quotation->total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="divider-line"></div>
        
        <div class="label">Terms & Conditions:</div>
        <div class="terms-text">
            1. This quotation is valid for 30 days from the date of issue. 2. A 70% deposit is required to initiate hardware procurement and 30% upon completion of installation. 3. Prices are inclusive of all applicable taxes unless otherwise stated. 4. Warranty covers manufacturing defects for 1 year but excludes physical damage, electrical surges, or unauthorized tempering. 5. Installation timelines will be agreed upon following site survey.
        </div>
    </div>

    <div class="bottom-bar">
        Orbita Kenya - Smart Hospitality Solutions - Computer Generated Quotation
    </div>

</body>
</html>