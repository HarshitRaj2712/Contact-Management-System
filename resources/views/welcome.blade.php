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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- NAVBAR -->
    <div class="container py-4">
        <nav class="d-flex justify-content-between align-items-center py-3">
            <h1 class="h4 mb-0 fw-semibold">
                <i class="fa-solid fa-address-book text-primary me-2"></i>
                <span class="brand-gradient">Contact Management</span>
            </h1>

            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Register
                    </a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- HERO SECTION -->
    <section class="landing-hero mb-5">
        <img src="{{ asset('images/download.jpg') }}"
             alt="Hero Background"
             class="landing-hero-image">

        <div class="landing-hero-overlay">
            <p class="text-uppercase fw-semibold mb-2">
                Contact Management System
            </p>

            <h2 class="display-3 fw-bold mb-3">
                Secure Contact Management For Every User
            </h2>

            <p class="lead mb-4">
                Store, organize, and access your contacts with powerful tools,
                secure authentication, and modern UI.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fa-solid fa-gauge-high me-2"></i>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fa-solid fa-user-plus me-2"></i>
                        Create Free Account
                    </a>

                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <div class="container">
        @include('partials.features')
    </div>

    <!-- FOOTER -->
    <footer class="landing-footer">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center text-md-start">
                <p class="mb-0">
                    &copy; {{ date('Y') }} Contact Management System. All rights reserved.
                </p>

                <p class="mb-0">
                    Built for secure, organized, and modern contact management.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
