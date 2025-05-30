<div class="w-100 d-flex align-items-center justify-content-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h1 class="fw-bold mb-3" style="color: #00234D;">Thank You!</h1>
                        <p class="mb-4 text-muted">Your order has been received and is now being processed. Below are your order details:</p>

                        <div class="row mb-4">
                            <div class="col-6 text-start">
                                <strong>Order Number:</strong>
                                <div>{{ $order->id }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <strong>Date:</strong>
                                <div>{{ $order->created_at->format('d-m-Y') }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6 text-start">
                                <strong>Total:</strong>
                                <div class="text-secondary fw-semibold">{{ Number::currency($order->grand_total, 'EUR') }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <strong>Payment Method:</strong>
                                <div>{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Card' }}</div>
                            </div>
                        </div>

                        @if($order)
                            <p>Transactie-ID (Stripe): <strong>{{ $order->transaction_id }}</strong></p>
                        @endif

                        <div class="card border bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold mb-3">Shipping Address</h5>
                                <p class="mb-1">{{ $order->address->full_name }}</p>
                                <p class="mb-1">{{ $order->address->street_address }}</p>
                                <p class="mb-1">{{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->zip_code }}</p>
                                <p class="mb-0">Phone: {{ $order->address->phone }}</p>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold mb-3">Order Details</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span><span>{{ Number::currency($order->grand_total, 'EUR') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount</span><span>{{ Number::currency(0, 'EUR') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping</span><span>{{ Number::currency(0, 'EUR') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-semibold">
                                    <span>Total</span><span>{{ Number::currency($order->grand_total, 'EUR') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ url('/products') }}" class="btn btn-outline-secondary px-4">Continue Shopping</a>
                            <a href="{{ url('/my-orders') }}" class="btn btn-primary px-4">View My Orders</a>
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
    .card-body {
        padding: 2rem;
    }
</style>
