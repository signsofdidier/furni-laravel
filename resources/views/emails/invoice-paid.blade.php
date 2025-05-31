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

    <p>We’re happy to confirm your order <strong>#{{ $order->id }}</strong>.</p>

    <p>
        <strong>Total:</strong> {{ Number::currency($order->grand_total, 'EUR') }}<br>
        <strong style="margin-top:5px">Payment:</strong> {{ ucfirst($order->payment_method) }}
    </p>

    <h3>Order Summary</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
        <tr style="background-color: #00234D; color: #ffffff;">
            <th style="padding: 8px; text-align: left;">Product</th>
            <th style="padding: 8px; text-align: left;">Qty</th>
            <th style="padding: 8px; text-align: left;">Price</th>
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

                    </div>
                </td>
                <td style="padding: 8px;">
                    {{ $item->quantity }}
                </td>
                <td style="padding: 8px;">
                    {{ Number::currency($item->unit_amount, 'EUR') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <p>
        You can view your full order anytime using the button below:
    </p>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/my-orders') }}" class="btn">View My Orders</a>
    </p>

    <p>Your invoice is attached as a PDF to this email.</p>

    <p>We’ll notify you again when your order is shipped.</p>

    <p style="margin-top: 40px;">Kind regards,<br><strong>The E-commerce Team</strong></p>
</div>

<div class="footer">
    &copy; {{ date('Y') }} Your Company. All rights reserved.
</div>
</body>
</html>
