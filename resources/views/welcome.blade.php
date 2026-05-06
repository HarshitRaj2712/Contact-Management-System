<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Contact Management System') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          crossorigin="anonymous" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #f8fafc !important;
            color: #0f172a !important;
        }

        header a:not(.rounded-full),
        main h1,
        main h2,
        main h3,
        main p,
        footer p {
            color: #0f172a !important;
        }

        main .text-sky-600 {
            color: #0284c7 !important;
        }

        main .text-emerald-600 {
            color: #059669 !important;
        }

        main .text-amber-600 {
            color: #d97706 !important;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
<div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
    <header class="flex flex-col gap-4 border-b border-slate-200 pb-4 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
        <a href="#top" class="flex items-center gap-3 text-lg font-semibold text-slate-900 ">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600">
                <i class="fa-solid fa-address-book"></i>
            </span>
            <span>Contact Management System</span>
        </a>

        <div class="flex items-center gap-4 text-sm font-medium text-slate-600">
            <a href="#how-it-works" class="hover:text-sky-600 dark:hover:text-sky-400">How It Works</a>
            <a href="#features" class="hover:text-sky-600 dark:hover:text-sky-400">Features</a>

            <button id="darkModeToggle"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-sky-500 hover:text-sky-600 dark:border-slate-700 dark:bg-slate-900  dark:hover:text-sky-400"
                    title="Toggle dark mode"
                    aria-pressed="false">
                <i id="darkModeIcon" class="fa-solid fa-moon text-sm"></i>
            </button>

            @auth
                <a href="{{ route('dashboard') }}" class="rounded-full bg-slate-900 px-4 py-2  dark:bg-white dark:text-slate-900">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hover:text-sky-600 dark:hover:text-sky-400">Login</a>
                <a href="{{ route('register') }}" class="rounded-full text-black bg-sky-600 px-4 py-2  hover:bg-sky-400">Sign Up</a>
            @endauth
        </div>
    </header>

    <main class="space-y-16 py-12">
        <section id="top"
    class="relative overflow-hidden rounded-md bg-[#007FFF] px-8 py-14 shadow-[0_20px_50px_rgba(0,127,255,0.28)] sm:px-12 md:px-20 md:py-20">

    <!-- Decorative Floating Circles -->
    <div class="absolute top-0 right-0 h-52 w-52 translate-x-16 -translate-y-16 rounded-full bg-white/20"></div>
    <div class="absolute bottom-0 left-0 h-44 w-44 -translate-x-12 translate-y-12 rounded-full bg-white/20"></div>

    <div class="relative z-10 mx-auto max-w-4xl text-center">

        <!-- Main Heading -->
        <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-2xl md:text-3xl">
            Manage Contacts Securely <br />
            <span class="underline decoration-white/30 underline-offset-8">
                With Your Personal Smart Dashboard
            </span>
        </h1>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">

            @auth
                <a href="{{ route('dashboard') }}"
                   class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-sky-600 shadow-lg transition-all duration-300 hover:scale-105 hover:bg-sky-100">
                    <i class="fa-solid fa-gauge-high mr-2"></i>
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-sky-600 shadow-lg transition-all duration-300 hover:scale-105 hover:bg-sky-100">
                    <i class="fa-solid fa-user-plus mr-2"></i>
                    Create Free Account
                </a>

                <a href="{{ route('login') }}"
                   class="rounded-xl border-2 border-white px-4 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-white hover:text-sky-600">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>
                    Login
                </a>
            @endauth

        </div>

    </div>
</section>

        <section id="how-it-works">
            <div class="mb-6 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600 dark:text-sky-400">How It Works</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-950  sm:text-3xl">A simple workflow from sign up to saved contacts</h2>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">01</div>
                    <h3 class="mt-3 text-lg font-semibold text-slate-950">Create your account</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Register once and get access to your secure dashboard.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">02</div>
                    <h3 class="mt-3 text-lg font-semibold text-slate-950 ">Add and organize contacts</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Store names, emails, phone numbers, and categories easily.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">03</div>
                    <h3 class="mt-3 text-lg font-semibold text-slate--950">Find details instantly</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Search and manage contacts anytime from one place.</p>
                </div>
            </div>
        </section>

        <section id="features">
            @include('partials.features')
        </section>
    </main>

    <footer class="border-t border-slate-200 py-6 text-sm text-slate-600 dark:border-slate-800 dark:text-slate-400">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
            <p>&copy; {{ date('Y') }} Contact Management System. All rights reserved.</p>
            <p>Built for secure and organized contact management.</p>
        </div>
    </footer>
</div>

<script>
(function () {
    const toggle = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');

    const apply = () => {
        const darkEnabled = localStorage.getItem('cms-dark') === '1';

        document.documentElement.classList.toggle('dark', darkEnabled);
        if (icon) {
            icon.className = darkEnabled ? 'fa-solid fa-sun text-sm' : 'fa-solid fa-moon text-sm';
        }

        if (toggle) {
            toggle.setAttribute('aria-pressed', darkEnabled ? 'true' : 'false');
            toggle.title = darkEnabled ? 'Switch to light mode' : 'Switch to dark mode';
        }
    };

    apply();

    toggle?.addEventListener('click', () => {
        const current = localStorage.getItem('cms-dark') === '1';
        localStorage.setItem('cms-dark', current ? '0' : '1');
        apply();
    });
})();
</script>

</body>
</html>
