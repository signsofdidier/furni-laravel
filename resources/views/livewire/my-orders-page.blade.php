<div>
    <div class="w-100 mt-4">
        <div class="container py-5">
            <h1 class="fw-bold mb-4" style="color: #00234D;">My Orders</h1>
            <div class="row g-4">
                @if($orders->count() > 0)
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
                @else
                    <div class="pb-5">
                        <p class="text-lg">There are no orders yet.</p>
                    </div>
                @endif
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

</div>
