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

        .address-block, .customer-info {
            margin: 20px 0 10px 0;
        }
        .address-block p, .customer-info p {
            margin: 3px 0;
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

        /* Totals section */
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
        .note {
            margin-top: 18px;
            background: #f9f6ee;
            border-left: 5px solid #00234D;
            padding: 10px 18px;
            font-size: 14px;
            color: #00234D;
        }
    </style>
</head>
<body>

@php
    $threshold = \App\Models\Setting::first()->free_shipping_threshold ?? 0;
    $paymentLabel = $order->payment_method == 'stripe' ? 'Bancontact' : 'Cash on Delivery';
@endphp

<div class="header">
    <img src="{{ public_path('assets/img/furni-white-3.png') }}" alt="Furni Logo">
    <h1>Order Confirmation – Order No. {{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
    <p>Payment Method: <strong>{{ $paymentLabel }}</strong></p>
</div>

<div class="customer-info">
    <p><strong>Customer:</strong> {{ Str::title($order->user->name) }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
</div>
<div class="address-block">
    <p><strong>Shipping Address:</strong></p>
    <p>{{ $order->address->first_name }} {{ $order->address->last_name }}</p>
    <p>{{ $order->address->street_address }}</p>
    <p>{{ $order->address->zip_code }} {{ $order->address->city }}</p>
    <p>@if($order->address->state)
          {{ $order->address->state }},
       @endif
        Belgium</p>
    <p>Phone: {{ $order->address->phone }}</p>
</div>

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
                <img class="product-image" src="{{ public_path('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}">
            </td>
            <td>
                {{ $item->product->name }}
                @if($item->color)
                    <br>
                    <small>
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background-color:{{ $item->color->hex }};border:1px solid #ccc;"></span>
                        {{ $item->color->name }}
                    </small>
                @endif
            </td>
            <td>{{ $item->quantity }}</td>
            <td>€{{ number_format($item->unit_amount, 2) }}</td>
            <td>€{{ number_format($item->unit_amount * $item->quantity, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="totals-container">
    <p class="totals-subtotal">
        Subtotal: €{{ number_format($order->sub_total, 2) }}
    </p>
    {{--<p class="totals-small">Discount: €{{ number_format(0, 2) }}</p>--}}
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

@if($order->payment_method == 'cod')
    <div class="note">
        <strong>Important:</strong> Please have the exact amount ready. Payment is due upon delivery.
    </div>
@endif

<div class="footer">
    This invoice was automatically generated.<br>
    VAT: BE0000.000.000 | Address: Syntrastreet, 8800 Roeselare, Belgium
</div>
</body>
</html>
