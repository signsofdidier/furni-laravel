<div>
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow rounded-4">
                    <div class="card-body p-5">
                        <h1 class="card-title text-center fw-bold mb-4" style="color: #00234D;">Verify Your Email Address</h1>

                        <p class="text-center text-muted mb-4">
                            We've sent a verification link to your email address.<br>
                            Please click the link to activate your account.
                        </p>

                        @if (session('message'))
                            <div class="alert alert-success text-center rounded-3" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.send') }}" class="d-flex justify-content-center">
                            @csrf
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                Resend Verification Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
