<x-guest-layout>
    <h4 class="fw-semibold mb-1">Confirm Password</h4>
    <p class="text-muted mb-4">Please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-shield-halved me-1"></i> Confirm
            </button>
        </div>
    </form>
</x-guest-layout>
