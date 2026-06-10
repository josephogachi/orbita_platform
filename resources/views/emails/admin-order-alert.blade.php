<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .wrapper { max-width: 600px; margin: 0 auto; border: 1px solid #eee; }
        .header { background: #000; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .status-badge { background: #e3f2fd; color: #0d47a1; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .order-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .order-table th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; color: #666; }
        .order-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #999; }
        .btn { background: #000; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <img src="{{ asset('favicon.png') }}" alt="Orbita Kenya" width="50">
            <h2 style="color: #fff; margin: 10px 0 0 0;">New Order Received</h2>
        </div>
        <div class="content">
            <p>Hello Team,</p>
            <p>A new order has been placed on <strong>Orbita Kenya</strong>. Here are the details for delivery organization:</p>
            
            <div style="margin: 20px 0;">
                <span class="status-badge">Action Required</span>
            </div>

            <table class="order-table">
                <tr>
                    <th>Order ID</th>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>{{ $order->user->name }} ({{ $order->user->email }})</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td style="font-weight: bold; color: #000;">KES {{ number_format($order->total_price, 2) }}</td>
                </tr>
            </table>

            <p>Please log in to the admin panel to view full product specifications and shipping address.</p>
            
            <a href="{{ url('/admin/orders') }}" class="btn">Process Order in Admin</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Orbita Kenya. All rights reserved.
        </div>
    </div>
</body>
</html>