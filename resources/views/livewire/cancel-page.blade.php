<div>
    <div class="w-100 mt-4">
        <div class="container py-5">
            <section class="text-center">
                <div class="p-5 bg-white border rounded-md dark:bg-gray-900 dark:border-gray-800">
                    <h1 class="mb-4 text-3xl font-semibold text-red-600 dark:text-red-400">
                        Payment Failed
                    </h1>
                    <p class="mb-6 text-gray-700 dark:text-gray-300">
                        Unfortunately your payment could not be processed and your order has been cancelled.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ url('/products') }}" class="btn btn-outline-primary">
                            Continue Shopping
                        </a>
                        <a href="{{ url('/contact') }}" class="btn btn-outline-secondary">
                            Contact Support
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style>
        .btn-outline-primary {
            color: #00234D;
            border-color: #00234D;
        }
        .btn-outline-primary:hover {
            background-color: #00234D;
            color: #ffffff;
        }
        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #ffffff;
        }
    </style>

</div>
