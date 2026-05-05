<x-guest-layout>
    <h4 class="fw-semibold mb-1">Verify Your Email</h4>
    <p class="text-muted mb-4">
        Before continuing, confirm your email address by clicking the verification link we sent you.
        If you did not receive it, request a new one below.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" role="alert">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-envelope-circle-check me-1"></i> Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
            </button>
        </form>
    </div>
</x-guest-layout>
