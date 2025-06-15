<x-layouts.app :hideBreadcrumb="true">
    <div class="error-page mt-100">
        <div class="container">
            <div class="error-content text-center">
                <h1 class="fw-bold" style="font-size:12rem; line-height:1;">
                    403
                </h1>
                <p class="h4 mb-3">Forbidden</p>
                <p class="mb-4">
                    You do not have permission to access this page.
                </p>
                <a href="{{ url('/') }}" class="btn btn-primary">
                    BACK TO HOMEPAGE
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
