<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Contact Management System') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container py-5">
        <nav class="d-flex justify-content-between align-items-center py-3 mb-5">
            <h1 class="h4 mb-0 fw-semibold">
                <i class="fa-solid fa-address-book text-primary me-2"></i>
                <span class="brand-gradient">Contact Management</span>
            </h1>

            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endauth
            </div>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-12 col-lg-7">
                <h2 class="display-5 fw-bold mb-3">Secure Contact Management For Every User</h2>
                <p class="lead text-muted mb-4">
                    Store, organize, and access your personal contacts with account-level security,
                    password recovery, and email verification built in.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-gauge-high me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-user-plus me-2"></i>Create Free Account
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                        </a>
                    @endauth
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card dashboard-card border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="h5 fw-semibold mb-3">Included in Step 1</h3>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>User Registration</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>User Login & Logout</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>Forgot Password</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>Email Verification</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i>Protected Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
