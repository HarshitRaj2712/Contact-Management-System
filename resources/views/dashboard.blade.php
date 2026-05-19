<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="h4 mb-1 fw-semibold">Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}.</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge text-bg-primary px-3 py-2">Authenticated</span>
            </div>
        </div>
    </x-slot>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card dashboard-card h-100 border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <p class="text-uppercase text-muted small mb-1">Contacts</p>
                    <h3 class="mb-0">{{ $totalContacts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card dashboard-card h-100 border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <p class="text-uppercase text-muted small mb-1">Favorites</p>
                    <h3 class="mb-0">{{ $favoriteContacts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card dashboard-card h-100 border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <p class="text-uppercase text-muted small mb-1">Categories</p>
                    <h3 class="mb-0">{{ $categoriesCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card dashboard-card h-100 border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <p class="text-uppercase text-muted small mb-1">Upcoming Birthdays</p>
                    <h3 class="mb-0">{{ $upcomingBirthdays->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-chart-line me-2"></i>Contacts Growth</h6>
                </div>
                <div class="card-body">
                    <canvas id="contactsLineChart" height="140"></canvas>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-table-list me-2"></i>Recent Contacts</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Added</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentContacts as $c)
                                    <tr>
                                        <td>{{ $c->first_name }} {{ $c->last_name }}</td>
                                        <td>{{ $c->company }}</td>
                                        <td>{{ $c->created_at->diffForHumans() }}</td>
                                        <td class="text-end"><a href="{{ route('contacts.show', $c) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-pie-chart me-2"></i>Tags Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="tagsDoughnutChart" height="220"></canvas>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-cake-candles me-2"></i>Upcoming Birthdays</h6>
                </div>
                <div class="card-body">
                    @if($upcomingBirthdays->count())
                        <ul class="list-group list-group-flush">
                            @foreach($upcomingBirthdays as $b)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $b->first_name }} {{ $b->last_name }}</strong>
                                        <div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($b->birthday)->format('M d') }} @if($b->company) · {{ $b->company }} @endif</div>
                                    </div>
                                    @if ($b->category)
                                        <span class="badge text-bg-light border">{{ $b->category->category_name }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mb-0 text-muted">No upcoming birthdays in the next 30 days.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Toast container and SweetAlert2 --}}
    <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;"></div>

    @php
        $monthlyData = \App\Models\Contact::query()
            ->forUser(auth()->id())
            ->where('created_at', '>=', now()->subMonths(5))
            ->get()
            ->groupBy(function($c){ return \Carbon\Carbon::parse($c->created_at)->format('Y-m'); })
            ->map->count();

        $monthlyLabels = [];
        $monthlyCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->copy()->subMonthsNoOverflow($i);
            $monthKey = $month->format('Y-m');

            $monthlyLabels[] = $month->format('M');
            $monthlyCounts[] = (int) ($monthlyData[$monthKey] ?? 0);
        }

        $tagData = \App\Models\Tag::withCount(['contacts' => function($q){ $q->where('user_id', auth()->id()); }])
            ->orderBy('contacts_count','desc')
            ->take(6)
            ->get()
            ->map(function($t){ return ['name' => $t->tag_name, 'count' => $t->contacts_count]; });
    @endphp

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            (function(){
                const months = @json($monthlyLabels);
                const counts = @json($monthlyCounts);

                const ctx = document.getElementById('contactsLineChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Contacts',
                                data: counts,
                                borderColor: '#25D366',
                                backgroundColor: 'rgba(37,211,102,0.12)',
                                tension: 0.25,
                                fill: true,
                            }]
                        },
                        options: { responsive: true, plugins: { legend: { display: false } } }
                    });
                }

                // Tags distribution
                const tagData = @json($tagData);

                const tagLabels = tagData.map(t=>t.name);
                const tagCounts = tagData.map(t=>t.count);

                const ctx2 = document.getElementById('tagsDoughnutChart');
                if (ctx2) {
                    new Chart(ctx2, {
                        type: 'doughnut',
                        data: { labels: tagLabels, datasets: [{ data: tagCounts, backgroundColor: ['#25D366','#7ef3a8','#198754','#2A2223','#6b7280','#34d399'] }] },
                        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                    });
                }

                // SweetAlert2 toast for session messages
                @if(session('success'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: @json(session('success')),
                        showConfirmButton: false,
                        timer: 3000
                    });
                @endif
            })();
        </script>
    @endpush

</x-app-layout>
