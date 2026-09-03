@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<form method="GET" action="{{ route('dashboard') }}" class="row mb-4 g-2">

    <div class="col-md-3">
        <select name="kampung" class="form-select" id="dash_filter_kampung">
            <option value="">Semua Kampung</option>
            @foreach($kampungList as $k)
                <option value="{{ $k }}" {{ request('kampung') == $k ? 'selected' : '' }}>
                    Kampung {{ $k }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select name="rw" class="form-select" id="dash_filter_rw" onchange="this.form.submit()" {{ request('kampung') ? '' : 'disabled' }}>
            <option value="">Semua RW</option>
            @if(request('kampung') && isset($groupedRw[request('kampung')]))
                @foreach($groupedRw[request('kampung')] as $rw)
                    <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>
                        RW {{ $rw }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

</form>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6 stagger-1">
        <div class="glass-card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Lansia</div>
                    <h3 class="mb-0 count-up">{{ $totalLansia }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 stagger-2">
        <div class="glass-card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(59,130,246,0.1);color:#3b82f6;">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Kegiatan</div>
                    <h3 class="mb-0 count-up">{{ $totalKegiatan }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 stagger-3">
        <div class="glass-card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(139,92,246,0.1);color:#8b5cf6;">
                    <i class="bi bi-clipboard2-check-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Kehadiran</div>
                    <h3 class="mb-0 count-up">{{ $totalKehadiran }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 stagger-4">
        <div class="glass-card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <div class="stat-label">Rata-rata Keaktifan</div>
                    <h3 class="mb-0 count-up">{{ $rataKeaktifan }}%</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8 stagger-5">
        <div class="glass-card h-100">
            <div class="glass-header">
                <h6><i class="bi bi-bar-chart-line-fill me-2 text-success"></i>Grafik Keaktifan Bulanan {{ now()->year }}</h6>
            </div>
            <div class="card-body p-4" style="position: relative; height: 320px;">
                <canvas id="chartKeaktifan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 stagger-5">
        <div class="glass-card h-100">
            <div class="glass-header">
                <h6><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribusi Keaktifan</h6>
            </div>
            <div class="card-body p-4 d-flex justify-content-center align-items-center" style="position: relative; height: 320px;">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="glass-card stagger-5 mb-4">
    <div class="glass-header">
        <h6><i class="bi bi-award-fill me-2 text-warning"></i>Top 5 Lansia Teraktif</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama Lansia</th>
                        <th>Kampung</th>
                        <th>RW</th>
                        <th class="text-end">Keaktifan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topLansia as $lansia)
                        <tr>
                            <td>
                                @if($loop->index == 0) <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill me-1"></i> #1</span>
                                @elseif($loop->index == 1) <span class="badge bg-secondary"><i class="bi bi-trophy-fill me-1"></i> #2</span>
                                @elseif($loop->index == 2) <span class="badge" style="background:#cd7f32;"><i class="bi bi-trophy-fill me-1"></i> #3</span>
                                @else <span class="fw-bold text-muted ms-2">#{{ $loop->index + 1 }}</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $lansia->nama }}</td>
                            <td>{{ $lansia->kampung ?? '-' }}</td>
                            <td>RW {{ $lansia->rw }}</td>
                            <td class="text-end">
                                <span class="badge-glass badge-success-glow fw-bold fs-6">
                                    {{ $lansia->persentase_keaktifan }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state py-3">
                                    <i class="bi bi-award text-muted" style="font-size:2rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada data keaktifan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

    const dashKampungSelect = document.getElementById('dash_filter_kampung');
    const dashRwSelect = document.getElementById('dash_filter_rw');
    const dashGroupedRw = @json($groupedRw);

    dashKampungSelect.addEventListener('change', function() {
        const kampung = this.value;
        dashRwSelect.innerHTML = '<option value="">Semua RW</option>';
        if (kampung) {
            dashRwSelect.disabled = false;
            const rwList = dashGroupedRw[kampung] || [];
            rwList.forEach(rw => {
                const opt = document.createElement('option');
                opt.value = rw;
                opt.textContent = 'RW ' + rw;
                dashRwSelect.appendChild(opt);
            });
        } else {
            dashRwSelect.disabled = true;
        }
        this.form.submit();
    });
    
    // Observer for theme changes to update charts dynamically
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "data-theme") {
                location.reload(); // Simple reload to redraw charts with new theme colors
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true });

    // Bar/Line Chart
    const ctx = document.getElementById('chartKeaktifan').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Keaktifan (%)',
                data: @json($chartData['data']),
                borderColor: '#10b981',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: isDark ? '#0b1120' : '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#ffffff',
                    titleColor: isDark ? '#f8fafc' : '#1e293b',
                    bodyColor: isDark ? '#cbd5e1' : '#475569',
                    borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: { label: c => `Keaktifan: ${c.parsed.y}%` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true, max: 100,
                    ticks: { callback: v => v + '%', color: textColor },
                    grid: { color: gridColor }
                },
                x: { ticks: { color: textColor }, grid: { display: false } }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('chartPie').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: @json($pieData['labels']),
            datasets: [{
                data: @json($pieData['data']),
                backgroundColor: [
                    '#10b981', // Sangat Aktif
                    '#3b82f6', // Aktif
                    '#f59e0b', // Cukup Aktif
                    '#ef4444'  // Kurang Aktif
                ],
                borderWidth: 2,
                borderColor: isDark ? '#0b1120' : '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor, padding: 15, usePointStyle: true }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#ffffff',
                    titleColor: isDark ? '#f8fafc' : '#1e293b',
                    bodyColor: isDark ? '#cbd5e1' : '#475569',
                    borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8
                }
            }
        }
    });
});
</script>
@endpush
