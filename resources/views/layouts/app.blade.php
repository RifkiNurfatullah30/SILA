<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SILA') - Sistem Informasi Lansia Aktif</title>
    <!-- Prevent dark mode flash -->
    <script>
        (function(){
            var theme = localStorage.getItem('sila-theme');
            if(!theme) theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 270px;
            --glass-bg: rgba(255,255,255,0.85);
            --glass-border: rgba(0,0,0,0.06);
            --glass-shadow: 0 4px 15px rgba(0,0,0,0.03);
            --bg-main: #f3f6f9;
            --bg-card: rgba(255,255,255,0.95);
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent: #10b981;
            --accent-glow: rgba(16,185,129,0.3);
            --accent2: #3b82f6;
            --accent2-glow: rgba(59,130,246,0.3);
            --border: rgba(0,0,0,0.08);
            --sidebar-bg: #ffffff;
            --input-bg: #ffffff;
            --table-hover: rgba(16,185,129,0.03);
            --scrollbar-track: transparent;
            --scrollbar-thumb: rgba(0,0,0,0.1);
        }

        [data-theme="dark"] {
            --glass-bg: rgba(15,23,42,0.85);
            --glass-border: rgba(255,255,255,0.08);
            --glass-shadow: 0 8px 32px rgba(0,0,0,0.2);
            --bg-main: #0b1120;
            --bg-card: rgba(30,41,59,0.7);
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --border: rgba(255,255,255,0.1);
            --sidebar-bg: #0f172a;
            --input-bg: rgba(15,23,42,0.8);
            --table-hover: rgba(255,255,255,0.03);
            --scrollbar-thumb: rgba(255,255,255,0.1);
            color-scheme: dark;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--scrollbar-track); }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 3px; }

        #bgCanvas {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: 0.7;
        }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: transform 0.3s ease, background 0.3s ease;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .brand-logo {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .brand-text h5 { color: var(--text-primary); font-weight: 800; margin: 0; font-size: 1.1rem; }
        .brand-text small { color: var(--text-muted); font-size: 0.7rem; }

        .sidebar-nav { padding: 0.5rem 0; flex-grow: 1; overflow-y: auto; }
        .sidebar-nav .nav-item { margin: 2px 12px; }

        .nav-category {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            padding: 0.8rem 1.5rem 0.35rem;
            margin-top: 0.25rem;
        }

        .nav-category:first-child { margin-top: 0; }

        .sidebar-nav .nav-link {
            color: var(--text-secondary);
            padding: 0.65rem 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            margin-bottom: 2px;
        }

        .sidebar-nav .nav-link:hover {
            color: var(--accent);
            background: var(--table-hover);
        }

        .sidebar-nav .nav-link.active {
            color: var(--accent);
            background: rgba(16,185,129,0.1);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* Pagination Styling Fixes */
        .pagination { margin-bottom: 0; gap: 4px; }
        .page-link {
            background-color: var(--input-bg) !important;
            border: 1px solid var(--border) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            padding: 0.5rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .page-link:hover {
            border-color: var(--accent) !important;
            color: var(--accent) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .page-item.active .page-link {
            background-color: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px var(--accent-glow) !important;
        }
        .page-item.disabled .page-link {
            background-color: var(--bg-card) !important;
            color: var(--text-muted) !important;
            opacity: 0.6;
            pointer-events: none;
        }
        .page-item:first-child .page-link, .page-item:last-child .page-link {
            border-radius: 8px !important;
        }

        .sidebar-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
        }

        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .top-navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .page-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin: 0;
        }

        .btn-toggle-sidebar {
            display: none;
            border: 1px solid var(--border);
            background: var(--input-bg);
            color: var(--text-primary);
            width: 36px; height: 36px;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            position: relative;
        }

        .theme-toggle i { position: absolute; transition: 0.3s ease; }
        [data-theme="light"] .theme-toggle .bi-moon-stars-fill { opacity: 0; transform: scale(0.5); }
        [data-theme="dark"] .theme-toggle .bi-sun-fill { opacity: 0; transform: scale(0.5); }

        .content-wrapper { padding: 1.5rem; }

        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--glass-shadow);
            transition: all 0.3s ease;
            animation: fadeIn 0.4s ease-out both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 1.25rem 1.5rem;
            border-radius: 16px 16px 0 0;
        }

        .glass-header h6 { margin: 0; font-weight: 700; color: var(--text-primary); }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }
        .count-up { font-weight: 800; font-size: 1.6rem; color: var(--text-primary); }

        .table { --bs-table-bg: transparent; --bs-table-color: var(--text-primary); margin-bottom: 0; color: var(--text-primary) !important; }
        .table th { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted) !important; border-bottom: 1px solid var(--border); padding: 1rem; font-weight: 600; }
        .table td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.88rem; vertical-align: middle; color: var(--text-primary) !important; }
        .table tbody tr:hover td { background-color: var(--table-hover) !important; color: var(--text-primary) !important; }

        .form-control, .form-select {
            background-color: var(--input-bg) !important;
            border: 1px solid var(--border);
            color: var(--text-primary) !important;
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--text-muted);
            color: var(--text-primary) !important;
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg) !important;
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--accent-glow) !important;
            color: var(--text-primary) !important;
        }

        .form-control::placeholder { color: var(--text-muted); }
        .input-group-text { background: var(--input-bg); border-color: var(--border); color: var(--text-muted); }

        .form-label { font-weight: 600; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.4rem; }

        .btn-accent {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-accent:hover { background: #059669; border-color: #059669; color: #fff; transform: translateY(-1px); }

        .btn-glass {
            background: var(--input-bg);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-glass:hover { border-color: var(--text-muted); color: var(--text-primary); }

        .btn-action {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--input-bg);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            transition: 0.2s;
            padding: 0;
        }

        .btn-action:hover { transform: translateY(-2px); }
        .btn-action.view:hover { background: var(--accent2); border-color: var(--accent2); color: #fff; }
        .btn-action.edit:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }
        .btn-action.delete:hover { background: #ef4444; border-color: #ef4444; color: #fff; }

        .badge-glass {
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success-glow { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
        .badge-primary-glow { background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3); }
        .badge-warning-glow { background: rgba(245,158,11,0.15); color: #d97706; border: 1px solid rgba(245,158,11,0.3); }
        .badge-danger-glow { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

        .nik-mono {
            font-family: 'SF Mono', Consolas, monospace;
            font-size: 0.85rem;
            background: var(--input-bg);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            border: 1px solid var(--border);
        }

        .dropdown-menu { background: var(--bg-card); border: 1px solid var(--border); box-shadow: var(--glass-shadow); }
        .dropdown-item { color: var(--text-primary); font-size: 0.88rem; }
        .dropdown-item:hover { background: var(--table-hover); color: var(--text-primary); }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        .stagger-1 { animation-delay: 0s; }
        .stagger-2 { animation-delay: 0.05s; }
        .stagger-3 { animation-delay: 0.1s; }
        .stagger-4 { animation-delay: 0.15s; }
        .stagger-5 { animation-delay: 0.2s; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0; }
            .btn-toggle-sidebar { display: flex !important; }
            
            /* Responsive table adjustments */
            .table-responsive { border: 1px solid var(--border); border-radius: 12px; }
            .glass-header { flex-direction: column; align-items: flex-start !important; gap: 10px; }
            .glass-header .badge-glass { align-self: flex-start; }
            
            /* Button group wrapping */
            .d-flex.gap-2.flex-wrap { width: 100%; justify-content: stretch; }
            .d-flex.gap-2.flex-wrap > * { flex: 1 1 auto; text-align: center; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-logo"><i class="bi bi-heart-pulse-fill"></i></div>
                <div class="brand-text">
                    <h5>SILA</h5>
                    <small>Sistem Informasi Lansia Aktif</small>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <div class="nav-category">Utama</div>
                @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    <div class="nav-category">Kelola Data</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lansia.*') ? 'active' : '' }}" href="{{ route('lansia.index') }}">
                            <i class="bi bi-people-fill"></i> <span>Data Lansia</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('health-records.*') ? 'active' : '' }}" href="{{ route('health-records.index') }}">
                            <i class="bi bi-heart-pulse-fill"></i> <span>Rekam Kesehatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}" href="{{ route('kegiatan.index') }}">
                            <i class="bi bi-calendar-event-fill"></i> <span>Kegiatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kehadiran.*') ? 'active' : '' }}" href="{{ route('kehadiran.index') }}">
                            <i class="bi bi-clipboard2-check-fill"></i> <span>Kehadiran</span>
                        </a>
                    </li>

                    <div class="nav-category">Pelaporan</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i> <span>Laporan</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.lansia') ? 'active' : '' }}" href="{{ route('portal.lansia') }}">
                            <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                <div class="nav-category">Akun</div>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="sidebarLogoutForm">
                        @csrf
                        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('sidebarLogoutForm').submit();" style="color: #ef4444;">
                            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-3">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="text-truncate">
                    <div class="fw-bold" style="font-size:0.85rem;color:var(--text-primary);">{{ Auth::user()->name }}</div>
                    <div style="font-size:0.7rem;color:var(--text-muted);">{{ Auth::user()->isAdmin() ? 'Administrator' : 'Lansia' }}</div>
                </div>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-toggle-sidebar" id="toggleSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="theme-toggle" id="themeToggle" title="Toggle dark/light mode">
                    <i class="bi bi-sun-fill text-warning"></i>
                    <i class="bi bi-moon-stars-fill text-info"></i>
                </button>
            </div>
        </nav>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);color:var(--text-primary);">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:var(--text-primary);">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function() {
        const html = document.documentElement;
        const toggle = document.getElementById('themeToggle');

        toggle.addEventListener('click', function() {
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('sila-theme', next);
        });

        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        });
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
            this.classList.remove('show');
        });

        // Count up animation fixed for decimals
        document.querySelectorAll('.count-up').forEach(el => {
            const text = el.textContent.trim();
            const target = parseFloat(text.replace(/,/g, ''));
            if (isNaN(target)) return;
            const suffix = text.includes('%') ? '%' : '';
            const isDecimal = text.includes('.');
            
            const duration = 1500;
            const start = performance.now();
            el.textContent = '0' + suffix;
            
            function step(now) {
                const p = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - p, 4);
                let current = ease * target;
                
                el.textContent = (isDecimal ? current.toFixed(2) : Math.round(current)) + suffix;
                
                if (p < 1) requestAnimationFrame(step);
                else el.textContent = text; // ensure final exact text
            }
            requestAnimationFrame(step);
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
