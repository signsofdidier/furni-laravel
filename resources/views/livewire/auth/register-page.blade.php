<div>
    <div class="w-100 mt-5 d-flex align-items-center justify-content-center" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-6">
                    <div class="text-center mb-5">
                        <h1 class="fw-bold mb-3" style="color: #2c5aa0;">Sign up</h1>
                        <p class="text-muted">
                            Already have an account?
                            <a href="/login" class="text-decoration-none fw-semibold" style="color: #2c5aa0;">Sign in here</a>
                        </p>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-12 mb-4">
                                <label for="name" class="form-label text-dark fw-semibold">Name</label>
                                <div class="position-relative">
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           wire:model="name"
                                           style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                    @error('name')
                                    <i class="fas fa-exclamation-circle position-absolute"
                                       style="right: 15px; top: 50%; transform: translateY(-50%); color: #dc3545;"></i>
                                    @enderror
                                </div>
                                @error('name')
                                <div class="text-danger mt-2" style="font-size: 0.875rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-12 mb-4">
                                <label for="email" class="form-label text-dark fw-semibold">Email address</label>
                                <div class="position-relative">
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           wire:model="email"
                                           style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                    @error('email')
                                    <i class="fas fa-exclamation-circle position-absolute"
                                       style="right: 15px; top: 50%; transform: translateY(-50%); color: #dc3545;"></i>
                                    @enderror
                                </div>
                                @error('email')
                                <div class="text-danger mt-2" style="font-size: 0.875rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label text-dark fw-semibold">Password</label>
                                <div class="position-relative">
                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           wire:model="password"
                                           style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                    @error('password')
                                    <i class="fas fa-exclamation-circle position-absolute"
                                       style="right: 15px; top: 50%; transform: translateY(-50%); color: #dc3545;"></i>
                                    @enderror
                                </div>
                                @error('password')
                                <div class="text-danger mt-2" style="font-size: 0.875rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Confirmation Field -->
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label text-dark fw-semibold">Password Confirmation</label>
                                <div class="position-relative">
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           wire:model="password_confirmation"
                                           style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                    @error('password_confirmation')
                                    <i class="fas fa-exclamation-circle position-absolute"
                                       style="right: 15px; top: 50%; transform: translateY(-50%); color: #dc3545;"></i>
                                    @enderror
                                </div>
                                @error('password_confirmation')
                                <div class="text-danger mt-2" style="font-size: 0.875rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="btn btn-primary w-100 py-3 fw-semibold"
                                style="background: #2c5aa0; border: none; border-radius: 8px; font-size: 1.1rem; transition: all 0.3s ease;"
                                onmouseover="this.style.background='#1e40af'"
                                onmouseout="this.style.background='#2c5aa0'">
                            Sign up
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
