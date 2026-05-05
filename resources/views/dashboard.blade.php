<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="h4 mb-1 fw-semibold">Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}.</p>
            </div>
            <span class="badge text-bg-primary px-3 py-2">Authenticated</span>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <h3 class="h5 fw-semibold mb-3"><i class="fa-solid fa-circle-check text-success me-2"></i>Authentication System Ready</h3>
                    <p class="text-muted mb-0">
                        Your account system is active with registration, login, logout, password reset, and email verification.
                        You can now continue with contact management features in Step 2.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <h3 class="h6 text-uppercase text-muted mb-3">Account Summary</h3>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fa-solid fa-user me-2 text-primary"></i>{{ auth()->user()->name }}</li>
                        <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-primary"></i>{{ auth()->user()->email }}</li>
                        <li>
                            <i class="fa-solid fa-circle-check me-2 text-primary"></i>
                            {{ auth()->user()->hasVerifiedEmail() ? 'Email verified' : 'Email pending verification' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
