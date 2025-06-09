<div>
    <div class="w-100 mt-4">
        <div class="container py-5">
            <h1 class="fw-bold mb-4" style="color: #00234D;">My Orders</h1>
            <div class="row g-4">
                @foreach($orders as $order)
                    @php
                        $statusColor = match($order->status) {
                            'new' => 'warning',
                            'processing' => 'info',
                            'shipped', 'delivered' => 'success',
                            'cancelled' => 'danger',
                            default => 'secondary'
                        };
                        $paymentColor = match($order->payment_status) {
                            'pending' => 'warning',
                            'paid' => 'success',
                            'failed' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Order Info -->
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <h5 class="mb-1">Order #{{ $order->id }} <small class="text-muted">({{ $order->created_at->format('d-m-Y') }})</small></h5>
                                        <p class="mb-0"><span class="fw-semibold">Amount:</span> <span class="text-primary">{{ Number::currency($order->grand_total, 'EUR') }}</span></p>
                                    </div>
                                    <!-- Status -->
                                    <div class="col-md-2 mb-3 mb-md-0">
                                        <span class="fw-semibold">Status:</span>
                                        <span class="badge bg-{{ $statusColor }} text-white">{{ ucfirst($order->status) }}</span>
                                    </div>
                                    <!-- Payment -->
                                    <div class="col-md-2 mb-3 mb-md-0">
                                        <span class="fw-semibold">Payment:</span>
                                        <span class="badge bg-{{ $paymentColor }} text-white">{{ ucfirst($order->payment_status) }}</span>
                                    </div>
                                    <!-- Actions -->
                                    <div class="col-md-4 text-md-end">
                                        <a href="{{ url('/my-orders') }}/{{ $order->id }}" class="btn btn-sm btn-outline-secondary me-2">Details</a>
                                        <a href="{{ url('/my-orders') }}/{{ $order->id }}/invoice" class="btn btn-sm btn-outline-secondary">Download PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <style>
        .badge {
            font-size: 0.9em;
            padding: 0.4em 0.75em;
        }
    </style>


    {{--
    <div class="w-100 mt-4 d-flex align-items-center justify-content-center">
        <div class="container py-5">
            <h1 class="fw-bold mb-4" style="color: #2c5aa0;">My Orders</h1>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Amount</th>
                                <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                @php
                                    $statusClass = match($order->status) {
                                      'new' => 'bg-warning',
                                      'processing' => 'bg-info',
                                      'shipped', 'delivered' => 'bg-success',
                                      'cancelled' => 'bg-danger',
                                      default => 'bg-secondary'
                                    };
                                    $paymentClass = match($order->payment_status) {
                                      'pending' => 'bg-warning',
                                      'paid' => 'bg-success',
                                      'failed' => 'bg-danger',
                                      default => 'bg-secondary'
                                    };
                                @endphp
                                <tr>
                                    <td class="align-middle">{{ $order->id }}</td>
                                    <td class="align-middle">{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td class="align-middle">
                                        <span class="badge {{ $statusClass }} text-white">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ $paymentClass }} text-white">{{ ucfirst($order->payment_status) }}</span>
                                    </td>
                                    <td class="align-middle">{{ Number::currency($order->grand_total, 'EUR') }}</td>
                                    <td class="align-middle text-end">
                                        <a href="/my-orders/{{ $order->id }}" class="btn btn-sm btn-outline-primary me-2">View Details</a>
                                        <a href="/my-orders/{{ $order->id }}/invoice" class="btn btn-sm btn-outline-secondary">Download PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge.bg-warning { background-color: #ffc107 !important; }
        .badge.bg-info { background-color: #0dcaf0 !important; }
        .badge.bg-success { background-color: #198754 !important; }
        .badge.bg-danger { background-color: #dc3545 !important; }
    </style>
    --}}

</div>
