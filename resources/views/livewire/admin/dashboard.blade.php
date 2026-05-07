<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Dashboard</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Dashboard</h1>
        <p class="text-xs text-dim mt-1">Overview of your platform activity.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.properties') }}" wire:navigate class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Total Properties</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $totalProperties }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.users') }}" wire:navigate class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $totalUsers }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.reservations') }}" wire:navigate class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Reservations</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $totalReservations }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.inquiries') }}" wire:navigate class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Pending Inquiries</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $pendingInquiries }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                </div>
            </div>
        </a>
    </div>

    @php
        $clrTeal   = 'rgba(20,184,166,1)';
        $clrTealBg = 'rgba(20,184,166,0.12)';
        $clrPurple = 'rgba(139,92,246,1)';
        $donutColors = ['rgba(20,184,166,1)', 'rgba(139,92,246,1)', 'rgba(34,197,94,1)', 'rgba(245,158,11,1)', 'rgba(239,68,68,1)', 'rgba(59,130,246,1)'];
    @endphp

    {{-- ── Row 1: Reservations over time + Reservation status ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Chart 1: Reservations over time --}}
        <div class="lg:col-span-2 border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-foreground font-serif">Reservations Over Time</h2>
                    <p class="text-[10px] text-dim mt-0.5">New reservations per period</p>
                </div>
                <div class="flex items-center gap-1 bg-subtle rounded-sm p-0.5">
                    @foreach(['7days' => '7 Days', '30days' => '30 Days', '6months' => '6 Months'] as $val => $label)
                        <button wire:click="$set('reservationRange', '{{ $val }}')"
                            class="px-2.5 py-1 text-[10px] font-semibold rounded-[3px] transition-all
                                {{ $reservationRange === $val ? 'bg-foreground text-on-primary' : 'text-dim hover:text-foreground' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div wire:ignore
                x-data="{
                    chart: null,
                    init() {
                        this.chart = new Chart(this.$refs.canvas, {
                            type: 'line',
                            data: {
                                labels: {{ json_encode($reservationsOverTime['labels']) }},
                                datasets: [{
                                    label: 'Reservations',
                                    data: {{ json_encode($reservationsOverTime['data']) }},
                                    borderColor: '{{ $clrTeal }}',
                                    backgroundColor: '{{ $clrTealBg }}',
                                    borderWidth: 2, pointRadius: 3, pointHoverRadius: 5,
                                    fill: true, tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false,
                                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: 'rgba(120,120,120,0.7)', font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                                    y: { beginAtZero: true, ticks: { color: 'rgba(120,120,120,0.7)', font: { size: 10 }, precision: 0 }, grid: { color: 'rgba(120,120,120,0.08)' } }
                                }
                            }
                        });
                        this.$wire.$watch('reservationsChartData', (newData) => {
                            this.chart.data.labels = newData.labels;
                            this.chart.data.datasets[0].data = newData.data;
                            this.chart.update('active');
                        });
                    }
                }">
                <div style="position: relative; height: 180px;">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 2: Reservation status --}}
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-foreground font-serif">Reservation Status</h2>
                <p class="text-[10px] text-dim mt-0.5">Breakdown by current status</p>
            </div>
            @php
                $statusLabels = array_map('ucfirst', array_keys($reservationStatuses));
                $statusData   = array_values($reservationStatuses);
                $statusEmpty  = array_sum($statusData) === 0;
            @endphp
            @if($statusEmpty)
                <div class="flex items-center justify-center h-40 text-dim text-xs">No reservation data yet</div>
            @else
                <div wire:ignore x-data="{
                        init() {
                            new Chart(this.$refs.canvas, {
                                type: 'doughnut',
                                data: {
                                    labels: {{ json_encode($statusLabels) }},
                                    datasets: [{ data: {{ json_encode($statusData) }}, backgroundColor: {{ json_encode(array_slice($donutColors, 0, count($statusData))) }}, borderWidth: 0, hoverOffset: 4 }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutout: '68%',
                                    plugins: {
                                        legend: { position: 'bottom', labels: { color: 'rgba(120,120,120,0.9)', font: { size: 10 }, padding: 10, boxWidth: 10, boxHeight: 10 } },
                                        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
                                    }
                                }
                            });
                        }
                    }">
                    <div style="position: relative; height: 180px;">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Row 2: Properties by type + Top Cities + Review Ratings ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Chart 3: Properties by type --}}
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-foreground font-serif">Properties by Type</h2>
                <p class="text-[10px] text-dim mt-0.5">Listing breakdown by property category</p>
            </div>
            @php
                $typeLabels = array_keys($propertiesByType);
                $typeData   = array_values($propertiesByType);
                $typeEmpty  = array_sum($typeData) === 0;
            @endphp
            @if($typeEmpty)
                <div class="flex items-center justify-center h-40 text-dim text-xs">No property data yet</div>
            @else
                <div wire:ignore x-data="{
                        init() {
                            new Chart(this.$refs.canvas, {
                                type: 'doughnut',
                                data: {
                                    labels: {{ json_encode($typeLabels) }},
                                    datasets: [{ data: {{ json_encode($typeData) }}, backgroundColor: {{ json_encode(array_slice($donutColors, 0, count($typeData))) }}, borderWidth: 0, hoverOffset: 4 }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutout: '68%',
                                    plugins: {
                                        legend: { position: 'bottom', labels: { color: 'rgba(120,120,120,0.9)', font: { size: 10 }, padding: 10, boxWidth: 10, boxHeight: 10 } },
                                        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
                                    }
                                }
                            });
                        }
                    }">
                    <div style="position: relative; height: 200px;">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            @endif
        </div>

        {{-- Chart 4: Properties by city --}}
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-foreground font-serif">Top Cities by Listings</h2>
                <p class="text-[10px] text-dim mt-0.5">Where are the properties located?</p>
            </div>
            @php
                $cityLabels = array_keys($propertiesByCity);
                $cityData   = array_values($propertiesByCity);
                $cityEmpty  = count($cityLabels) === 0;
                $cityHeight = max(160, count($cityLabels) * 40);
            @endphp
            @if($cityEmpty)
                <div class="flex items-center justify-center h-40 text-dim text-xs">No location data yet</div>
            @else
                <div wire:ignore x-data="{
                        init() {
                            new Chart(this.$refs.canvas, {
                                type: 'bar',
                                data: {
                                    labels: {{ json_encode($cityLabels) }},
                                    datasets: [{
                                        label: 'Listings',
                                        data: {{ json_encode($cityData) }},
                                        backgroundColor: '{{ $clrTealBg }}',
                                        borderColor: '{{ $clrTeal }}',
                                        borderWidth: 1.5, borderRadius: 3
                                    }]
                                },
                                options: {
                                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.x} listing${ctx.parsed.x !== 1 ? 's' : ''}` } } },
                                    scales: {
                                        x: { beginAtZero: true, ticks: { color: 'rgba(120,120,120,0.7)', font: { size: 10 }, precision: 0 }, grid: { color: 'rgba(120,120,120,0.08)' } },
                                        y: { ticks: { color: 'rgba(120,120,120,0.8)', font: { size: 10 } }, grid: { display: false } }
                                    }
                                }
                            });
                        }
                    }">
                    <div style="position: relative; height: {{ $cityHeight }}px;">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            @endif
        </div>

        {{-- Chart 7: Review ratings distribution --}}
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-foreground font-serif">Review Ratings</h2>
                <p class="text-[10px] text-dim mt-0.5">How renters rate the properties</p>
            </div>
            @php
                $ratingEmpty  = array_sum($ratingsData) === 0;
                $ratingColors = ['rgba(239,68,68,0.85)', 'rgba(249,115,22,0.85)', 'rgba(245,158,11,0.85)', 'rgba(34,197,94,0.85)', 'rgba(20,184,166,0.85)'];
            @endphp
            @if($ratingEmpty)
                <div class="flex items-center justify-center h-40 text-dim text-xs">No review data yet</div>
            @else
                <div wire:ignore x-data="{
                        init() {
                            new Chart(this.$refs.canvas, {
                                type: 'bar',
                                data: {
                                    labels: ['1 ★', '2 ★', '3 ★', '4 ★', '5 ★'],
                                    datasets: [{
                                        label: 'Reviews',
                                        data: {{ json_encode(array_values($ratingsData)) }},
                                        backgroundColor: {{ json_encode($ratingColors) }},
                                        borderWidth: 0, borderRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} review${ctx.parsed.y !== 1 ? 's' : ''}` } } },
                                    scales: {
                                        x: { grid: { display: false }, ticks: { color: 'rgba(120,120,120,0.8)', font: { size: 11 } } },
                                        y: { beginAtZero: true, ticks: { color: 'rgba(120,120,120,0.7)', font: { size: 10 }, precision: 0 }, grid: { color: 'rgba(120,120,120,0.08)' } }
                                    }
                                }
                            });
                        }
                    }">
                    <div style="position: relative; height: 200px;">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Row 3: User registrations + Verification status ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Chart 5: User registrations over time --}}
        <div class="lg:col-span-2 border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-foreground font-serif">User Registrations Over Time</h2>
                    <p class="text-[10px] text-dim mt-0.5">New renter sign-ups per period</p>
                </div>
                <div class="flex items-center gap-1 bg-subtle rounded-sm p-0.5">
                    @foreach(['7days' => '7 Days', '30days' => '30 Days', '6months' => '6 Months'] as $val => $label)
                        <button wire:click="$set('userRange', '{{ $val }}')"
                            class="px-2.5 py-1 text-[10px] font-semibold rounded-[3px] transition-all
                                {{ $userRange === $val ? 'bg-foreground text-on-primary' : 'text-dim hover:text-foreground' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div wire:ignore
                x-data="{
                    chart: null,
                    init() {
                        this.chart = new Chart(this.$refs.canvas, {
                            type: 'line',
                            data: {
                                labels: {{ json_encode($usersOverTime['labels']) }},
                                datasets: [{
                                    label: 'New Users',
                                    data: {{ json_encode($usersOverTime['data']) }},
                                    borderColor: '{{ $clrPurple }}',
                                    backgroundColor: 'rgba(139,92,246,0.1)',
                                    borderWidth: 2, pointRadius: 3, pointHoverRadius: 5,
                                    fill: true, tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false,
                                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: 'rgba(120,120,120,0.7)', font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                                    y: { beginAtZero: true, ticks: { color: 'rgba(120,120,120,0.7)', font: { size: 10 }, precision: 0 }, grid: { color: 'rgba(120,120,120,0.08)' } }
                                }
                            }
                        });
                        this.$wire.$watch('usersChartData', (newData) => {
                            this.chart.data.labels = newData.labels;
                            this.chart.data.datasets[0].data = newData.data;
                            this.chart.update('active');
                        });
                    }
                }">
                <div style="position: relative; height: 180px;">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 6: User verification status --}}
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-foreground font-serif">Verification Status</h2>
                <p class="text-[10px] text-dim mt-0.5">Renter ID verification breakdown</p>
            </div>
            @php
                $vLabels = array_map('ucfirst', array_keys($verificationStatus));
                $vData   = array_values($verificationStatus);
                $vEmpty  = array_sum($vData) === 0;
                $vColorMap = ['verified' => 'rgba(34,197,94,1)', 'pending' => 'rgba(245,158,11,1)', 'rejected' => 'rgba(239,68,68,1)', 'none' => 'rgba(150,150,150,0.45)'];
                $vColorList = array_values(array_map(fn($k) => $vColorMap[$k] ?? 'rgba(150,150,150,0.45)', array_keys($verificationStatus)));
            @endphp
            @if($vEmpty)
                <div class="flex items-center justify-center h-40 text-dim text-xs">No user data yet</div>
            @else
                <div wire:ignore x-data="{
                        init() {
                            new Chart(this.$refs.canvas, {
                                type: 'doughnut',
                                data: {
                                    labels: {{ json_encode($vLabels) }},
                                    datasets: [{ data: {{ json_encode($vData) }}, backgroundColor: {{ json_encode($vColorList) }}, borderWidth: 0, hoverOffset: 4 }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutout: '68%',
                                    plugins: {
                                        legend: { position: 'bottom', labels: { color: 'rgba(120,120,120,0.9)', font: { size: 10 }, padding: 10, boxWidth: 10, boxHeight: 10 } },
                                        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
                                    }
                                }
                            });
                        }
                    }">
                    <div style="position: relative; height: 180px;">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Row 4: Quick Actions & System Info ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <h2 class="text-sm font-semibold text-foreground font-serif tracking-tight mb-4 pb-3 border-b border-line">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-2.5">
                <a href="{{ route('admin.properties') }}" wire:navigate class="group flex items-center gap-2.5 rounded-sm border border-line px-3 py-2.5 text-sm font-medium text-dim hover:text-foreground hover:border-foreground/30 transition-all">
                    <div class="w-7 h-7 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M3.75 3v18m4.5-18v18M15.75 3v18m4.5-18v18M6 6.75h1.5M6 9.75h1.5m7.5-3H16.5m-1.5 3H16.5M6 15.75h1.5m7.5 0H16.5M6 12.75h1.5m7.5 0H16.5m-10.5 6h12"/></svg>
                    </div>
                    Properties
                </a>
                <a href="{{ route('admin.users') }}" wire:navigate class="group flex items-center gap-2.5 rounded-sm border border-line px-3 py-2.5 text-sm font-medium text-dim hover:text-foreground hover:border-foreground/30 transition-all">
                    <div class="w-7 h-7 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    Users
                </a>
                <a href="{{ route('admin.reservations') }}" wire:navigate class="group flex items-center gap-2.5 rounded-sm border border-line px-3 py-2.5 text-sm font-medium text-dim hover:text-foreground hover:border-foreground/30 transition-all">
                    <div class="w-7 h-7 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    Reservations
                </a>
                <a href="{{ route('admin.inquiries') }}" wire:navigate class="group flex items-center gap-2.5 rounded-sm border border-line px-3 py-2.5 text-sm font-medium text-dim hover:text-foreground hover:border-foreground/30 transition-all">
                    <div class="w-7 h-7 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    </div>
                    Inquiries
                </a>
            </div>
        </div>

        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <h2 class="text-sm font-semibold text-foreground font-serif tracking-tight mb-4 pb-3 border-b border-line">System Info</h2>
            <dl class="space-y-0 divide-y divide-line">
                <div class="flex items-center justify-between py-3 first:pt-0">
                    <dt class="text-xs text-dim">Laravel Version</dt>
                    <dd class="text-xs font-medium text-foreground font-mono">{{ app()->version() }}</dd>
                </div>
                <div class="flex items-center justify-between py-3">
                    <dt class="text-xs text-dim">PHP Version</dt>
                    <dd class="text-xs font-medium text-foreground font-mono">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex items-center justify-between py-3 last:pb-0">
                    <dt class="text-xs text-dim">Environment</dt>
                    <dd>
                        <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ app()->environment('production') ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' }}">
                            {{ app()->environment() }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
