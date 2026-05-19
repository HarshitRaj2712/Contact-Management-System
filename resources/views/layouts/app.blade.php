<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @php
        $navigationLinks = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fa-gauge', 'active' => 'dashboard'],
            ['label' => 'Contacts', 'route' => 'contacts.index', 'icon' => 'fa-address-book', 'active' => 'contacts.*'],
            ['label' => 'Categories', 'route' => 'categories.index', 'icon' => 'fa-layer-group', 'active' => 'categories.*'],
            ['label' => 'Tags', 'route' => 'tags.index', 'icon' => 'fa-tags', 'active' => 'tags.*'],
            ['label' => 'Activity Logs', 'route' => 'activity.logs', 'icon' => 'fa-list-check', 'active' => 'activity.logs'],
        ];

        $utilityLinks = [
            ['label' => 'Export CSV', 'route' => 'contacts.export', 'icon' => 'fa-file-csv'],
            ['label' => 'Export PDF', 'route' => 'contacts.exportPdf', 'icon' => 'fa-file-pdf'],
        ];
    @endphp

    <div class="cms-shell">
        <div class="container-fluid px-0">
            <div class="row g-0 min-vh-100">
                <aside class="col-12 col-lg-3 col-xl-2 cms-sidebar">
                    <div class="cms-sidebar-inner d-flex flex-column h-100 px-3 px-lg-4 py-4">
                        <a class="cms-brand text-decoration-none" href="{{ route('dashboard') }}">
                            <span class="cms-brand-mark">
                                <i class="fa-solid fa-address-book"></i>
                            </span>
                            <span>
                                <span class="brand-gradient d-block">Contact Management</span>
                                <small class="text-muted">Workspace navigation</small>
                            </span>
                        </a>

                        <nav class="nav flex-column cms-sidebar-nav mt-4">
                            @foreach ($navigationLinks as $item)
                                <a class="nav-link cms-sidebar-link {{ request()->routeIs($item['active']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                                    <i class="fa-solid {{ $item['icon'] }} cms-sidebar-icon"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </nav>

                        <div class="mt-4 pt-4 border-top">
                            <div class="text-uppercase text-muted small fw-semibold mb-2">Quick Actions</div>
                            <div class="d-grid gap-2">
                                @foreach ($utilityLinks as $item)
                                    <a class="btn btn-outline-primary btn-sm text-start" href="{{ route($item['route']) }}">
                                        <i class="fa-solid {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-auto pt-4 cms-sidebar-footer">
                            <div class="mb-3">
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-muted small">{{ auth()->user()->email }}</div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <main class="col-12 col-lg-9 col-xl-10 cms-main">
                    <div class="cms-main-inner px-3 px-md-4 px-xl-10 py-4 py-lg-5">
                        <div class="cms-page-toolbar d-flex justify-content-end mb-3">
                            <button id="darkModeToggle" class="btn btn-outline-secondary btn-sm" title="Toggle dark mode" aria-pressed="false">
                                <i id="darkModeIcon" class="fa-solid fa-moon me-1"></i> Theme
                            </button>
                        </div>

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
                </main>
            </div>
        </div>
    </div>
    <script>
        (function(){
            const toggle = document.getElementById('darkModeToggle');
            const icon = document.getElementById('darkModeIcon');

            const apply = () => {
                const darkEnabled = localStorage.getItem('cms-dark') === '1';

                document.documentElement.classList.toggle('dark-mode', darkEnabled);
                document.documentElement.classList.toggle('dark', darkEnabled);
                document.documentElement.setAttribute('data-theme', darkEnabled ? 'dark' : 'light');
                document.documentElement.setAttribute('data-bs-theme', darkEnabled ? 'dark' : 'light');
                document.body.classList.toggle('dark-mode-body', darkEnabled);

                if (icon) {
                    icon.className = darkEnabled ? 'fa-solid fa-sun me-1' : 'fa-solid fa-moon me-1';
                }

                if (toggle) {
                                document.documentElement.setAttribute('data-theme', darkEnabled ? 'dark' : 'light');
                    toggle.setAttribute('aria-pressed', darkEnabled ? 'true' : 'false');
                    toggle.title = darkEnabled ? 'Switch to light mode' : 'Switch to dark mode';
                }
            };

            apply();

            toggle?.addEventListener('click', function(){
                const cur = localStorage.getItem('cms-dark') === '1';
                localStorage.setItem('cms-dark', cur ? '0' : '1');
                apply();
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
