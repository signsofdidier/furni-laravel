<!-- resources/views/pdf/order-placed.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            background-color: #00234D;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-height: 50px;
            display: block;
            margin: 0 auto 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            vertical-align: middle;
            text-align: left;
        }

        .table-header {
            background-color: #00234D;
            color: white;
        }

        td.image-cell {
            width: 60px;
            text-align: center;
        }

        img.product-image {
            max-width: 50px;
            max-height: 50px;
            margin: 0 auto;
            display: block;
        }

        /* ------ Styling voor de totals-sectie ------ */
        .totals-container {
            margin-top: 20px;
            text-align: right;
        }

        .totals-subtotal {
            font-size: 16px;
            margin: 4px 0;
        }

        .totals-small {
            font-size: 14px;
            margin: 2px 0;
            color: #555;
        }

        .totals-grand {
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0;
            color: #00234D;
        }


        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>

@php
    // Haal de gratis-verzenddrempel op uit de settings
    $threshold = \App\Models\Setting::first()->free_shipping_threshold ?? 0;
@endphp

<div class="header">
    <img src="{{ public_path('assets/img/logo-white.png') }}" alt="Shop Logo">
    <h1>Order Confirmation #{{ $order->id }}</h1>
    <p>Payment Method: <strong>Cash on Delivery</strong></p>
</div>

<p><strong>Customer:</strong> {{ $order->user->name }}</p>
<p><strong>Email:</strong> {{ $order->user->email }}</p>
<p><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>

<table>
    <thead class="table-header">
    <tr>
        <th>Image</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($order->items as $item)
        <tr>
            <td class="image-cell">
                <img class="product-image" src="{{ public_path('storage/' . $item->product->images[0]) }}"
                     alt="{{ $item->product->name }}">
            </td>
            <td>
                {{ $item->product->name }}<br>
                @if($item->color)
                    <small>Color: {{ $item->color->name }}</small>
                @endif
            </td>
            <td>{{ $item->quantity }}</td>
            <td>€{{ number_format($item->unit_amount, 2) }}</td>
            <td>€{{ number_format($item->unit_amount * $item->quantity, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- ------ Aangepaste totals-sectie met Taxes erbij ------ --}}
<div class="totals-container">
    <p class="totals-subtotal">
        Subtotal: €{{ number_format($order->sub_total, 2) }}
    </p>
    <p class="totals-small">
        Discount: €{{ number_format(0, 2) }}
    </p>
    <p class="totals-small">
        Taxes (21%) incl.: €{{ number_format($order->sub_total * 0.21, 2) }}
    </p>
    <p class="totals-small">
        @if($threshold > 0 && $order->sub_total >= $threshold)
            <strong>Free Shipping</strong>
        @else
            Shipping Cost: €{{ number_format($order->shipping_amount, 2) }}
        @endif
    </p>
    <p class="totals-grand">
        Grand Total: €{{ number_format($order->grand_total, 2) }}
    </p>
</div>

<p><strong>Note:</strong> Please have the exact amount ready. Payment is due upon delivery.</p>

<div class="footer">
    This invoice was automatically generated.<br>
    BTW/VAT: BE0000.000.000 | Address: Syntrastreet, 8800 Roeselare, Belgium
</div>
</body>
</html>
