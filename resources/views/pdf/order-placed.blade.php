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

        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
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
                <img class="product-image" src="{{ public_path('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}">
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

<p class="total">Grand Total (incl. VAT): €{{ number_format($order->grand_total, 2) }}</p>

<p><strong>Note:</strong> Please have the exact amount ready. Payment is due upon delivery.</p>

<div class="footer">
    This invoice was automatically generated.<br>
    BTW/VAT: BE0000.000.000 | Address: Syntrastreet, 8800 Roeselare, Belgium
</div>
</body>
</html>
