<x-guest-layout>
    <h4 class="fw-semibold mb-1">Forgot Password</h4>
    <p class="text-muted mb-4">
        Enter your account email and we will send you a secure password reset link.
    </p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="small text-decoration-none">Back to login</a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane me-1"></i> Send Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>
