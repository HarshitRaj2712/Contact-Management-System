<section>
    <div class="mb-6 flex items-end justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600 dark:text-sky-400">Features</p>
            <h3 class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">Everything you need to manage contacts</h3>
            <p class="mt-2 text-slate-600 dark:text-slate-300">A clean workflow for storing, organizing, and finding contacts without clutter.</p>
        </div>

        <a href="{{ route('register') }}" class="hidden rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-slate-900 sm:inline-flex">Get Started</a>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-sky-600 dark:text-sky-400">
                <i class="fa-solid fa-shield-heart mr-2"></i>Secure Access
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Keep contacts protected with authentication, email verification, and user-level access.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                <i class="fa-solid fa-folder-tree mr-2"></i>Organized Storage
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Use categories, tags, favorites, and notes to keep your contact list structured.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-amber-600 dark:text-amber-400">
                <i class="fa-solid fa-chart-column mr-2"></i>Smart Insights
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Track activity, analyze trends, and export your contact data when needed.</p>
        </div>
    </div>
</section>
