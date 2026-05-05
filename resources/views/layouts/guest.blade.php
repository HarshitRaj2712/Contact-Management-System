<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container auth-shell d-flex align-items-center justify-content-center py-5">
        <div class="w-100" style="max-width: 520px;">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <h3 class="fw-semibold mb-1"><i class="fa-solid fa-address-book text-primary me-2"></i><span class="brand-gradient">Contact Management</span></h3>
                </a>
                <p class="text-muted mb-0">Securely manage your contacts from one place.</p>
            </div>

            <div class="card auth-card">
                <div class="card-body p-4 p-lg-5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
