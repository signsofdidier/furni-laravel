<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Factuur - Order #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        h1, h3 { margin-bottom: 0; }
    </style>
</head>
<body>
<h1>Factuur</h1>
<p><strong>Ordernummer:</strong> {{ $order->id }}</p>
<p><strong>Datum:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
<p><strong>Klant:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>

<h3>Producten</h3>
<table>
    <thead>
    <tr>
        <th>Product</th>
        <th>Aantal</th>
        <th>Prijs per stuk</th>
        <th>Totaal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>€{{ number_format($item->unit_amount, 2, ',', '.') }}</td>
            <td>€{{ number_format($item->total_amount, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p><strong>Grand Total:</strong> €{{ number_format($order->grand_total, 2, ',', '.') }}</p>
<p><strong>Betaalstatus:</strong> {{ ucfirst($order->payment_status) }}</p>
<p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
</body>
</html>
