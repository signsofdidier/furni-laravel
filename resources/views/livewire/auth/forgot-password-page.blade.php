<div>
    <div class="w-100 mt-5 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center mb-5">
                        <h1 class="fw-bold mb-3" style="color: #2c5aa0;">Forgot password?</h1>
                        <p class="text-muted">
                            Remember your password?
                            <a href="/login" class="text-decoration-none fw-semibold" style="color: #2c5aa0;">Sign in here</a>
                        </p>
                    </div>

                    <form wire:submit.prevent="save">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="row">
                            <!-- Email Field -->
                            <div class="col-md-12 mb-4">
                                <label for="email" class="form-label text-dark fw-semibold">Email address</label>
                                <div class="position-relative">
                                    <input type="email"
                                           id="email"
                                           wire:model="email"
                                           class="form-control"
                                           style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                                           aria-describedby="email-error">
                                    @error('email')
                                    <i class="fas fa-exclamation-circle position-absolute"
                                       style="right: 15px; top: 50%; transform: translateY(-50%); color: #dc3545;"></i>
                                    @enderror
                                </div>
                                @error('email')
                                <div class="text-danger mt-2" style="font-size: 0.875rem;" id="email-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Reset Password Button -->
                        <button type="submit"
                                class="btn btn-primary w-100 py-3 fw-semibold"
                                style="background: #2c5aa0; border: none; border-radius: 8px; font-size: 1.1rem; transition: all 0.3s ease;"
                                onmouseover="this.style.background='#1e40af'"
                                onmouseout="this.style.background='#2c5aa0'">
                            Reset password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: #2c5aa0 !important;
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.1) !important;
        }
    </style>

</div>
