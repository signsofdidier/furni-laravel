<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 30px;
        }
        .header {
            background-color: #00234D;
            color: #ffffff;
            padding: 1rem;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            background-color: #00234D;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }
        a {
            color: #00234D;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="header">Thank you for your order!</h2>

    <p>Hello {{ $order->user->name }},</p>

    <p>Your order <strong>#{{ $order->id }}</strong> has been placed successfully with <strong>Cash on Delivery</strong>.</p>

    <p>
        <strong>Total:</strong> {{ Number::currency($order->grand_total, 'EUR') }}<br>
        <strong>Payment method:</strong> Cash on Delivery
    </p>

    <h3>Order Summary</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
        <tr style="background-color: #00234D; color: #ffffff;">
            <th style="padding: 8px;">Product</th>
            <th style="padding: 8px;">Qty</th>
            <th style="padding: 8px;">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order->items as $item)
            <tr style="border-bottom: 1px solid #dee2e6;">
                <td style="padding: 8px;">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ $message->embed(public_path('storage/' . $item->product->images[0])) }}" alt="product" style="width: 40px; height: auto; margin-right: 10px;">
                        <a href="{{ url('/products/' . $item->product->slug) }}" style="color: #00234D; text-decoration: none;">
                            {{ $item->product->name }}
                        </a>
                        @if ($item->color)
                            <div style="font-size: 12px; color: #555;">Color: {{ $item->color->name }}</div>
                        @endif
                    </div>
                </td>
                <td style="padding: 8px;">{{ $item->quantity }}</td>
                <td style="padding: 8px;">{{ Number::currency($item->unit_amount, 'EUR') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @php
        // Haal de gratis-verzenddrempel op uit Settings
        $threshold = \App\Models\Setting::first()->free_shipping_threshold ?? 0;
    @endphp

    {{-- Subtotal / Discount / Shipping / Total --}}
    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tr>
            <td style="text-align: left; padding: 8px;"><strong>Subtotal:</strong></td>
            <td style="text-align: right; padding: 8px;">
                {{ Number::currency($order->sub_total, 'EUR') }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 8px;"><strong>Discount:</strong></td>
            <td style="text-align: right; padding: 8px;">
                {{ Number::currency(0, 'EUR') }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 8px;"><strong>Taxes (21%) incl.:</strong></td>
            <td style="text-align: right; padding: 8px;">
                {{ Number::currency($order->sub_total * 0.21, 'EUR') }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 8px;"><strong>Shipping:</strong></td>
            <td style="text-align: right; padding: 8px;">
                @if($threshold > 0 && $order->sub_total >= $threshold)
                    Free Shipping
                @else
                    {{ Number::currency($order->shipping_amount, 'EUR') }}
                @endif
            </td>
        </tr>
        <tr style="border-top: 1px solid #dee2e6;">
            <td style="text-align: left; padding: 8px; font-weight: bold;"><strong>Total:</strong></td>
            <td style="text-align: right; padding: 8px; font-weight: bold;">
                {{ Number::currency($order->grand_total, 'EUR') }}
            </td>
        </tr>
    </table>

    <p style="margin-top: 20px;">You will be asked to pay the total amount upon delivery.</p>

    <p style="margin: 30px 0; text-align: center;">
        <a href="{{ url('/my-orders') }}" class="btn">View My Orders</a>
    </p>

    <p style="margin-top: 40px;">Kind regards,<br><strong>The E-commerce Team</strong></p>
</div>

<div class="footer">
    &copy; {{ date('Y') }} Your Company. All rights reserved.
</div>
</body>
</html>
