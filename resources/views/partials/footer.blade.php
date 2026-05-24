<footer class="mt-8 w-full rounded-md bg-[#25D366] text-white shadow-[0_-20px_60px_rgba(37,211,102,0.24)]">
    <div class="w-full px-6 py-12 sm:px-10 lg:px-16">
        <div class="mx-auto max-w-7xl grid gap-10 lg:grid-cols-[1.5fr_1fr_1fr_1fr]">

            <!-- BRAND -->
            <div>
                <a href="#top" class="inline-flex items-center gap-3 text-md font-semibold text-white">
                    <span class="flex h-11 w-11 items-center justify-center rounded-md bg-white/20 text-xl backdrop-blur-sm">
                        <i class="fa-solid fa-address-book"></i>
                    </span>
                    <span>Contact Management System</span>
                </a>

                <p class="mt-4 max-w-md text-sm leading-7 text-white/85">
                    Organize contacts, track relationships, and keep your personal network secure in one clean dashboard.
                </p>

                <div class="mt-6 flex items-center gap-3 text-sm text-white/90">
                    <a href="#top" class="flex h-6 w-6 items-center justify-center rounded-full border border-white/20 bg-white/10 transition hover:bg-white hover:text-[#007FFF]">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#top" class="flex h-6 w-6 items-center justify-center rounded-full border border-white/20 bg-white/10 transition hover:bg-white hover:text-[#007FFF]">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="#top" class="flex h-6 w-6 items-center justify-center rounded-full border border-white/20 bg-white/10 transition hover:bg-white hover:text-[#007FFF]">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#top" class="flex h-6 w-6 items-center justify-center rounded-full border border-white/20 bg-white/10 transition hover:bg-white hover:text-[#007FFF]">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="https://github.com/HarshitRaj2712/contact-management-system" class="flex h-6 w-6 items-center justify-center rounded-full border border-white/20 bg-white/10 transition hover:bg-white hover:text-[#007FFF]">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- NAVIGATION -->
            <div>
                <h3 class="text-base font-semibold">Navigation</h3>
                <ul class="mt-4 space-y-3 text-sm text-white/85">
                    <li><a href="#top" class="transition hover:text-white">Home</a></li>
                    <li><a href="#how-it-works" class="transition hover:text-white">How It Works</a></li>
                    <li><a href="#features" class="transition hover:text-white">Features</a></li>
                </ul>
            </div>

            <!-- RESOURCES -->
            <div>
                <h3 class="text-base font-semibold">Resources</h3>
                <ul class="mt-4 space-y-3 text-sm text-white/85">
                    <li><a href="#features" class="transition hover:text-white">Organize Contacts</a></li>
                    <li><a href="#how-it-works" class="transition hover:text-white">Getting Started</a></li>
                    <li><a href="#top" class="transition hover:text-white">Dashboard Overview</a></li>
                </ul>
            </div>

            <!-- ACCOUNT -->
            <div>
                <h3 class="text-base font-semibold">Account</h3>
                <ul class="mt-4 space-y-3 text-sm text-white/85">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="transition hover:text-white">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">Login</a></li>
                        <li><a href="{{ route('register') }}" class="transition hover:text-white">Create Account</a></li>
                    @endauth
                    <li><a href="#top" class="transition hover:text-white">Support</a></li>
                </ul>
            </div>

        </div>

        <!-- BOTTOM BAR -->
        <div class="mx-auto mt-5 max-w-7xl border-t border-white/20 pt-4">
            <div class="flex flex-col gap-3 text-center text-sm text-white/85 sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p>&copy; {{ date('Y') }} Contact Management System. All rights reserved.</p>
                <p>Built for secure and organized contact management.</p>
            </div>
        </div>
    </div>
</footer>
