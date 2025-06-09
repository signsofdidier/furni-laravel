<div>
    @php
        // map order status naar Bootstrap-kleuren
        $statusColor = match($order->status) {
            'new'        => 'warning',
            'processing' => 'info',
            'shipped', 'delivered' => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };

        // map payment status naar Bootstrap-kleuren
        $paymentStatusColor = match($order->payment_status) {
            'pending' => 'warning',
            'paid'    => 'success',
            'failed'  => 'danger',
            default   => 'secondary',
        };

        // Haal de gratis-verzenddrempel op via Settings (ervan uitgaande dat je dit even meepakt in de view)
        $threshold = \App\Models\Setting::first()->free_shipping_threshold ?? 0;
    @endphp

    <div class="w-100 mt-4">
        <div class="container py-5">
            <h1 class="fw-bold mb-4" style="color: #00234D;">Order Details</h1>

            <!-- Top Cards -->
            <div class="row g-4 mb-5">
                <!-- Customer Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user fa-2x text-secondary me-3"></i>
                            <div>
                                <p class="text-uppercase text-muted mb-1">Customer</p>
                                <h5 class="mb-0">{{ $address->full_name }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Date -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-calendar-alt fa-2x text-secondary me-3"></i>
                            <div>
                                <p class="text-uppercase text-muted mb-1">Order Date</p>
                                <h5 class="mb-0">{{ $order->created_at->format('d-m-Y') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Status -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-truck fa-2x text-secondary me-3"></i>
                            <div>
                                <p class="text-uppercase text-muted mb-1">Status</p>
                                <span class="badge bg-{{ str_replace('bg-','',$statusColor) }} text-white">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Payment Status -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-credit-card fa-2x text-secondary me-3"></i>
                            <div>
                                <p class="text-uppercase text-muted mb-1">Payment</p>
                                <span class="badge bg-{{ str_replace('bg-','',$paymentStatusColor) }} text-white">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <!-- Order Items Table -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">Items</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order_items as $item)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ url('storage', $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="me-3" style="width:50px; height:50px; object-fit:cover;">
                                                <span>{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ Number::currency($item->unit_amount, 'EUR') }}</td>
                                        <td class="align-middle">{{ $item->quantity }}</td>
                                        <td class="align-middle">{{ Number::currency($item->total_amount, 'EUR') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1">{{ $address->street_address }}</p>
                            <p class="mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                            <p class="mb-0">Phone: {{ $address->phone }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <!-- Summary -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>{{ Number::currency($order->sub_total, 'EUR') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>discount</span>
                                <span>{{ Number::currency(0, 'EUR') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Taxes (21%) incl.</span><span class="text-muted">{{ Number::currency($order->sub_total * 0.21 , 'EUR') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                @if($threshold > 0 && $order->sub_total >= $threshold)
                                    <span>Free Shipping</span>
                                @else
                                    <span>Shipping</span>
                                    <span>{{ Number::currency($order->shipping_amount, 'EUR') }}</span>
                                @endif
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-semibold">
                                <span>Total</span>
                                <span>{{ Number::currency($order->grand_total, 'EUR') }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted mt-3">
                                <span>Transactie-ID:</span>
                                <span class="text-break">{{ $order->transaction_id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.4em 0.75em;
        }
    </style>

</div>
