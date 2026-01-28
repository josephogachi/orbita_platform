<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Orbita Kenya Invoice #{{ $order->order_number }}</title>

<style>
@page { margin: 0px; }
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Helvetica, Arial, sans-serif;
    background: #fff;
    color: #0c2f5a;
}

/* ================= RIBBON (FIXED OVERLAY) ================= */
.ribbon-wrapper {
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    overflow: hidden;
    z-index: 100;
}
.ribbon {
    position: absolute;
    top: 30px;
    right: -55px;
    width: 230px;
    padding: 10px 0;
    text-align: center;
    transform: rotate(45deg);
    font-weight: bold;
    font-size: 16px;
    color: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.ribbon.paid { background: #148c14; }
.ribbon.unpaid { background: #ef4444; }

/* ================= HEADER ================= */
.header-table {
    width: 100%;
    padding: 40px 50px 10px 50px;
    border-collapse: collapse;
}

.logo-box { width: 110px; vertical-align: top; }
.logo-img { width: 100px; height: auto; }

.company-info {
    font-size: 12px;
    line-height: 1.6;
    padding-left: 15px;
    vertical-align: top;
}

.invoice-meta {
    text-align: right;
    vertical-align: top;
}

.invoice-meta h1 {
    margin: 0;
    font-size: 35px;
    letter-spacing: 2px;
    color: #d18b54;
    font-weight: bold;
}

.meta-text {
    margin-top: 12px;
    font-size: 13px;
    line-height: 1.5;
}

/* ================= DIVIDERS ================= */
.orange-line {
    height: 2px;
    background: #d18b54;
    margin: 20px 50px;
}
.gray-line {
    height: 1px;
    background: #d9d9d9;
    margin: 14px 50px 20px 50px;
}

/* ================= CLIENT INFO ================= */
.client-table {
    width: 100%;
    padding: 0 50px;
    font-size: 14px;
    font-weight: bold;
    border-collapse: collapse;
}

/* ================= PRODUCTS ================= */
.product-table {
    width: calc(100% - 100px);
    margin: 0 50px 20px 50px;
    border-collapse: collapse;
    font-size: 12px;
}

.product-table th {
    background: #0c2f5a;
    color: #fff;
    padding: 12px;
    text-align: left;
    text-transform: uppercase;
}

.product-table td {
    background: #f1f1f1;
    padding: 12px;
    border-bottom: 12px solid #fff;
}

.product-table th:nth-child(2),
.product-table th:nth-child(3),
.product-table th:nth-child(4),
.product-table td:nth-child(2),
.product-table td:nth-child(3),
.product-table td:nth-child(4) {
    text-align: center;
}

.product-table td:last-child,
.product-table th:last-child {
    text-align: right;
}

/* ================= TOTALS & STICKY FOOTER ================= */
.footer-container {
    position: absolute;
    bottom: 0;
    width: 100%;
}

.totals {
    padding: 0 50px;
    font-size: 12px;
    margin-bottom: 20px;
}

.bank {
    float: left;
    width: 50%;
    line-height: 1.6;
}

.summary {
    float: right;
    width: 330px;
}

.summary table {
    width: 100%;
    border-collapse: collapse;
}

.summary td {
    padding: 4px 0;
}

.balance {
    border-top: 2px solid #000;
    font-weight: bold;
    padding-top: 10px;
    font-size: 14px;
}

.clear { clear: both; }

.terms {
    padding: 15px 50px 10px 50px;
    font-size: 10px;
    color: #333;
    line-height: 1.4;
    border-top: 1px solid #eee;
}

.terms h3 {
    margin: 0 0 6px 0;
    font-size: 13px;
    color: #0c2f5a;
    text-decoration: underline;
}

.footer-icons {
    width: 100%;
    padding: 15px 50px;
    font-size: 11px;
    border-collapse: collapse;
}

.footer-icons td {
    width: 33.33%;
    vertical-align: top;
}

.icon {
    font-size: 16px;
    color: #d18b54;
    padding-right: 8px;
    vertical-align: top;
}

/* ================= LOWER BAR ================= */
.bottom-bar {
    position: relative;
    height: 46px;
    background: #0c2f5a;
}

.bottom-accent {
    position: absolute;
    left: 0;
    top: 0;
    height: 46px;
    width: 35%;
    background: #d18b54;
    border-top-right-radius: 60px;
}

.bottom-text {
    position: absolute;
    width: 100%;
    text-align: center;
    line-height: 46px;
    font-size: 11px;
    color: #fff;
}
</style>
</head>

<body>

{{-- Ribbon for PAID/UNPAID --}}
<div class="ribbon-wrapper">
    <div class="ribbon {{ $order->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
        {{ strtoupper($order->payment_status) }}
    </div>
</div>

{{-- Header --}}
<table class="header-table">
<tr>
    <td class="logo-box">
        <img src="{{ resource_path('images/logo.png') }}" class="logo-img">
    </td>
    <td class="company-info">
        Show Room: G.floor BBS Mall, 12st Eastleigh Nairobi, Kenya [cite: 2, 28, 61]<br>
        Office: Decale palace hotel 2nd floor 12st, Eastleigh, Nairobi Kenya [cite: 2, 28, 61]<br>
        Email: Info@orbitakenya.com [cite: 2, 28, 61]<br>
        Tel: +254-726-777-733 | +254-726-226-666 | +254-727-229-999 [cite: 3, 29, 62]
    </td>
    <td class="invoice-meta">
        <h1>INVOICE [cite: 7, 33, 56]</h1>
        <div class="meta-text">
            Date Generated: <strong>{{ $order->created_at->format('m/d/Y') }}</strong> [cite: 8, 34, 64]<br>
            INVOICE No.# <strong>#{{ $order->order_number }}</strong> [cite: 9, 35, 64]
        </div>
    </td>
</tr>
</table>

<div class="orange-line"></div>

{{-- Client and Staff Info --}}
<table class="client-table">
<tr>
    <td>INVOICED TO : {{ strtoupper($order->shipping_address) }} [cite: 4, 30, 58]</td>
    <td style="text-align:right;">GENERATED BY : {{ strtoupper($order->staff_name ?? 'OGACHI') }} [cite: 11, 37, 64]</td>
</tr>
</table>

<div class="gray-line"></div>

{{-- Main Product Table --}}
<table class="product-table">
<thead>
<tr>
    <th>PRODUCT [cite: 5, 31, 60]</th>
    <th>PRICE [cite: 5, 31, 69]</th>
    <th>QTY [cite: 5, 31, 70]</th>
    <th>TOTAL [cite: 5, 31, 71]</th>
</tr>
</thead>
<tbody>
@foreach($order->items as $item)
<tr>
    <td>{{ $item->product->name }} [cite: 5, 31]</td>
    <td>Ksh. {{ number_format($item->unit_price, 2) }} [cite: 5, 31]</td>
    <td>{{ $item->quantity }} [cite: 5, 31]</td>
    <td>Ksh. {{ number_format($item->unit_price * $item->quantity, 2) }} [cite: 5, 31]</td>
</tr>
@endforeach

{{-- Padding rows to maintain design height --}}
@for($i = count($order->items); $i < 4; $i++)
<tr><td colspan="4" style="background:#fff;">&nbsp;</td></tr>
@endfor
</tbody>
</table>

{{-- Sticky Footer Wrapper --}}
<div class="footer-container">
    
    {{-- Totals and Bank Section --}}
    <div class="totals">
        <div class="bank">
            <strong>BANK NAME: CO-OPERATIVE BANK ACC [cite: 12, 38, 66]</strong><br>
            <strong>NAME: ORBITAHTECH SYSTEMS KENYA LTD. [cite: 13, 39, 66]</strong><br>
            <strong>ACC NO: 01100542859001 [cite: 13, 39, 67]</strong>
        </div>

        <div class="summary">
            <table>
            <tr><td>SUBTOTAL :</td><td style="text-align:right;">{{ number_format($order->subtotal, 2) }} </td></tr>
            <tr><td>DISCOUNT (40%) :</td><td style="text-align:right;">-{{ number_format($order->subtotal * 0.40, 2) }} </td></tr>
            <tr><td>SUBTOTAL LESS DISCOUNT :</td><td style="text-align:right;">{{ number_format($order->subtotal * 0.60, 2) }} </td></tr>
            <tr><td>TAX RATE (16%) :</td><td style="text-align:right;">{{ number_format(($order->subtotal * 0.60) * 0.16, 2) }} </td></tr>
            <tr><td>SHIPPING/HANDLING :</td><td style="text-align:right;">{{ number_format($order->shipping_cost, 2) }} </td></tr>
            <tr class="balance">
                <td>BALANCE DUE :</td>
                <td style="text-align:right;">KSH. {{ number_format($order->grand_total, 2) }} </td>
            </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>

    {{-- Terms and Conditions --}}
    <div class="terms">
        <h3>TERMS AND CONDITIONS [cite: 15, 41, 73]</h3>
        1. All products supplied by Orbita Kenya are covered by a one (1) year warranty against manufacturing defects from the date of purchase. [cite: 16, 42, 74, 75]<br>
        2. This warranty does not cover damage resulting from misuse, negligence, improper installation, accidents, unauthorized modifications, or human-caused damage. [cite: 17, 43, 76]<br>
        3. This invoice is system-generated and serves as an official record of payment status, whether paid or unpaid. [cite: 18, 44, 77]<br>
        4. Ownership of goods remains with Orbita Kenya until full payment is received. [cite: 19, 45, 78]<br>
        5. By accepting this invoice, the client agrees to these terms and conditions. [cite: 20, 46, 79]
    </div>

    {{-- Iconic Footer with Icons --}}
    <table class="footer-icons">
    <tr>
        <td>
            <span class="icon">☎</span>
            <span class="footer-text">
                +254-726-777-733<br>
                +254-726-226-666<br>
                +254-727-229-999 [cite: 21, 22, 23, 47, 48, 49]
            </span>
        </td>
        <td>
            <span class="icon">🌐</span>
            <span class="footer-text">
                www.orbitakenya.com<br>
                info@orbitakenya.com [cite: 24, 50]
            </span>
        </td>
        <td>
            <span class="icon">📍</span>
            <span class="footer-text">
                G.floor BBS Mall, General Wairunge Street<br>
                Eastleigh Nairobi, Kenya [cite: 25, 51]
            </span>
        </td>
    </tr>
    </table>

    {{-- Dark Blue Bottom Bar with Gold Accent --}}
    <div class="bottom-bar">
        <div class="bottom-accent"></div>
        <div class="bottom-text">This invoice is system-generated by Orbita Kenya [cite: 26, 52, 80]</div>
    </div>
</div>

</body>
</html>