<x-layouts.app>
    <div class="error-page mt-100">
        <div class="container">
            <div class="error-content text-center">
                <div class="error-img mx-auto d-flex justify-content-center">
                    <img src="{{ asset('assets/img/error/error.png') }}" alt="error">
                </div>
                <p class="error-subtitle">Page Not Found</p>
                <a href="{{ url('/') }}" class="btn-primary mt-4">BACK TO HOMEPAGE</a>
            </div>
        </div>
    </div>
</x-layouts.app>
