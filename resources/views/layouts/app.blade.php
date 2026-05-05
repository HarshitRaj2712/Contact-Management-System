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
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container py-1">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-address-book text-primary me-2"></i>
                <span class="brand-gradient">Contact Management</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmsNavbar" aria-controls="cmsNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="cmsNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <button id="darkModeToggle" class="btn btn-outline-secondary btn-sm" title="Toggle dark mode">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4 py-lg-5">
        @if (! auth()->user()->hasVerifiedEmail())
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2" role="alert">
                <span><i class="fa-solid fa-triangle-exclamation me-2"></i>Please verify your email to access all features.</span>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">Resend Verification Email</button>
                </form>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @isset($header)
            <div class="mb-4">
                {{ $header }}
            </div>
        @endisset

        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </div>
    <script>
        (function(){
            const toggle = document.getElementById('darkModeToggle');

            const apply = () => {
                const darkEnabled = localStorage.getItem('cms-dark') === '1';

                document.documentElement.classList.toggle('dark-mode', darkEnabled);
                document.documentElement.setAttribute('data-bs-theme', darkEnabled ? 'dark' : 'light');
                document.body.classList.toggle('dark-mode-body', darkEnabled);
            };

            apply();

            toggle?.addEventListener('click', function(){
                const cur = localStorage.getItem('cms-dark') === '1';
                localStorage.setItem('cms-dark', cur ? '0' : '1');
                apply();
            });
        })();
    </script>
</body>
</html>
