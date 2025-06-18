<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 30px; }
        .header { background-color: #00234D; color: #fff; padding: 1rem; }
        .container { max-width: 600px; margin: auto; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); padding: 30px; }
        .btn { display: inline-block; text-decoration: none; background-color: #00234D; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; }
        a { color: #00234D; }
        .footer { text-align: center; font-size: 12px; color: #6c757d; margin-top: 20px; }
        .color-dot { display:inline-block; width:12px; height:12px; border-radius:50%; border:1px solid #ccc; margin-right:4px; vertical-align:middle;}
        .product-color-info { font-size: 12px; color: #555; margin-top: 3px;}
    </style>
</head>
<body>
<div class="container">
    <div class="header" style="text-align:center;">
        <img src="{{ $message->embed(public_path('assets/img/furni-white-3.png')) }}" alt="Furni Logo" style="max-height: 50px; margin-bottom: 10px;">

        <h2 style="margin: 0; color: #fff;">Thank you for your order!</h2>
    </div>

    <p>Hello {{ Str::title($order->user->name) }},</p>

    <p>
        @if($order->payment_method === 'cod')
            Your order <strong>Order No. {{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong> has been placed successfully with <strong>Cash on Delivery</strong>.
        @else
            We’re happy to confirm your <strong>Order No. {{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>.
        @endif
    </p>

    <p>
        <strong>Total:</strong> {{ Number::currency($order->grand_total, 'EUR') }}<br>
        <strong>Payment method:</strong>
        @if($order->payment_method === 'cod')
            Cash on Delivery
        @elseif($order->payment_method === 'stripe')
            Bancontact
        @else
            {{ ucfirst($order->payment_method) }}
        @endif
    </p>

    <h3 style="margin-top: 32px;">Shipping Address</h3>
    <table style="width:100%; margin-bottom: 18px;">
        <tr>
            <td style="padding: 6px 0;">
                <strong>{{ $order->address->first_name }} {{ $order->address->last_name }}</strong><br>
                {{ $order->address->street_address }}<br>
                {{ $order->address->zip_code }} {{ $order->address->city }}<br>
                {{ $order->address->state }}<br>
                Belgium<br>
                @if($order->address->phone)
                    Phone: {{ $order->address->phone }}
                @endif
            </td>
        </tr>
    </table>

    <h3 style="font-weight: bold;">Order Summary</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:24px;">
        <thead>
        <tr style="background-color: #00234D; color:#fff;">
            <th style="padding:10px 12px; text-align:left; width:55%;">Product</th>
            <th style="padding:10px 0; text-align:center; width:15%;">Qty</th>
            <th style="padding:10px 18px 10px 0; text-align:right; width:30%;">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order->items as $item)
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:12px;">
                    <div style="display:flex;align-items:center;">
                        <img src="{{ $message->embed(public_path('storage/'.$item->product->images[0])) }}" alt="{{ $item->product->name }}" style="width:40px;height:auto;margin-right:12px;">
                        <div>
                            <div style="font-weight:500;color:#00234D;">{{ $item->product->name }}</div>
                            @if ($item->color)
                                <div style="margin-top:5px;">
                                    <span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:{{ $item->color->hex }};border:1px solid #ccc;vertical-align:middle;margin-right:6px;"></span>
                                    <span style="font-size:13px;color:#444;">Color: {{ $item->color->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="text-align:center;font-size:16px;">{{ $item->quantity }}</td>
                <td style="text-align:right; padding-right:10px; font-size:16px;">{{ Number::currency($item->unit_amount, 'EUR') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @php
        $threshold = \App\Models\Setting::first()->free_shipping_threshold ?? 0;
    @endphp

    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tr>
            <td style="text-align: left; padding: 8px;"><strong>Subtotal:</strong></td>
            <td style="text-align: right; padding: 8px;">
                {{ Number::currency($order->sub_total, 'EUR') }}
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

    {{-- Verschillende extra info afhankelijk van de betaalmethode --}}
    @if($order->payment_method === 'cod')
        <p style="margin-top: 20px;">
            <strong>Note:</strong> You will be asked to pay the total amount upon delivery.
        </p>
    @else
        <p style="margin-top: 20px;">
            Your invoice is attached as a PDF to this email.
        </p>
    @endif

    <p>
        You can view your full order anytime using the button below:
    </p>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/my-orders') }}" class="btn">View My Orders</a>
    </p>

    <p style="margin-top: 40px;">Kind regards,<br><strong>The Furni Team</strong></p>
</div>

<div class="footer">
    &copy; {{ date('Y') }} Your Company. All rights reserved.
</div>

<style>
    @media only screen and (max-width: 600px) {
        .container { padding: 10px !important; }
        table, thead, tbody, th, td, tr { display: block; width: 100% !important; }
        td, th { box-sizing: border-box; }
        .header { padding: 0.5rem !important; font-size: 1.1rem; }
        .btn { width: 100% !important; }
    }

</style>

</body>
</html>
