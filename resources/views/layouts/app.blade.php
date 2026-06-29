<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Student Achievement Management Portal — Track, manage and celebrate student achievements.">
    <title>@yield('title', 'Dashboard') — Student Achievement Portal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── CSS Variables ─────────────────────────────────── */
        :root {
            --bg-primary:    #0a0a0a;
            --bg-secondary:  #111111;
            --bg-card:       #161616;
            --bg-hover:      #1e1e1e;
            --border:        #2a2a2a;
            --border-light:  #333333;
            --text-primary:  #f5f5f5;
            --text-secondary:#a0a0a0;
            --text-muted:    #666666;
            --accent:        #ffffff;
            --accent-dim:    rgba(255,255,255,0.08);
            --accent-hover:  rgba(255,255,255,0.12);
            --success:       #22c55e;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --info:          #3b82f6;
            --sidebar-width: 260px;
            --navbar-height: 64px;
            --radius:        10px;
            --radius-lg:     16px;
            --shadow:        0 4px 24px rgba(0,0,0,0.4);
            --transition:    all 0.2s ease;
        }

        /* ── Base ───────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* ── Scrollbar ──────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* ── Navbar ─────────────────────────────────────────── */
        .app-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1000;
            gap: 1rem;
        }

        .navbar-brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            text-decoration: none;
            white-space: nowrap;
        }

        .navbar-brand-text span {
            color: var(--text-secondary);
            font-weight: 400;
        }

        .navbar-toggler-custom {
            background: none;
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: var(--radius);
            padding: 6px 10px;
            cursor: pointer;
            transition: var(--transition);
            display: none;
        }

        .navbar-toggler-custom:hover {
            background: var(--accent-dim);
        }

        .navbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-dim);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-primary);
            cursor: pointer;
        }

        .navbar-user-info small {
            color: var(--text-muted);
            font-size: 0.7rem;
            line-height: 1;
            display: block;
        }

        .navbar-user-info strong {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            display: block;
        }

        .role-badge {
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .role-badge.student {
            background: rgba(59,130,246,0.15);
            color: #60a5fa;
            border: 1px solid rgba(59,130,246,0.25);
        }

        .role-badge.faculty {
            background: rgba(168,85,247,0.15);
            color: #c084fc;
            border: 1px solid rgba(168,85,247,0.25);
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        .app-sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            overflow-y: auto;
            z-index: 900;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 1.25rem 1.25rem 0.4rem;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            margin: 1px 0.75rem;
            border-radius: var(--radius);
        }

        .sidebar-nav-item:hover {
            background: var(--accent-hover);
            color: var(--text-primary);
        }

        .sidebar-nav-item.active {
            background: var(--accent-dim);
            color: var(--text-primary);
        }

        .sidebar-nav-item.active::before {
            content: '';
            position: absolute;
            left: -0.75rem;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: var(--text-primary);
            border-radius: 0 2px 2px 0;
        }

        .sidebar-nav-item i {
            font-size: 1rem;
            width: 20px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid var(--border);
        }

        .btn-logout {
            width: 100%;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            border-radius: var(--radius);
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-logout:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: rgba(239,68,68,0.05);
        }

        /* ── Main Content ────────────────────────────────────── */
        .app-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - var(--navbar-height));
        }

        .page-content {
            flex: 1;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin: 0 0 0.25rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: 0;
        }

        /* ── Cards ───────────────────────────────────────────── */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: var(--transition);
        }

        .card-dark:hover {
            border-color: var(--border-light);
            box-shadow: var(--shadow);
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--transition);
        }

        .stat-card:hover {
            border-color: var(--border-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-icon.white   { background: rgba(255,255,255,0.08); color: #fff; }
        .stat-icon.green   { background: rgba(34,197,94,0.12);   color: #22c55e; }
        .stat-icon.yellow  { background: rgba(245,158,11,0.12);  color: #f59e0b; }
        .stat-icon.red     { background: rgba(239,68,68,0.12);   color: #ef4444; }
        .stat-icon.blue    { background: rgba(59,130,246,0.12);  color: #3b82f6; }
        .stat-icon.purple  { background: rgba(168,85,247,0.12);  color: #a855f7; }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            line-height: 1;
        }

        /* ── Table ───────────────────────────────────────────── */
        .table-dark-custom {
            color: var(--text-primary);
            border-color: var(--border);
        }

        .table-dark-custom thead th {
            background: var(--bg-secondary);
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-color: var(--border);
            padding: 0.9rem 1rem;
        }

        .table-dark-custom tbody td {
            border-color: var(--border);
            padding: 0.85rem 1rem;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .table-dark-custom tbody tr {
            transition: var(--transition);
        }

        .table-dark-custom tbody tr:hover {
            background: var(--bg-hover) !important;
        }

        /* ── Status Badges ───────────────────────────────────── */
        .status-badge {
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .status-badge.pending  { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
        .status-badge.approved { background: rgba(34,197,94,0.15);  color: #22c55e; border: 1px solid rgba(34,197,94,0.25); }
        .status-badge.rejected { background: rgba(239,68,68,0.15);  color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }

        /* ── Footer ──────────────────────────────────────────── */
        .app-footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid var(--border);
            background: var(--bg-secondary);
            color: var(--text-muted);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        /* ── Alerts ──────────────────────────────────────────── */
        .alert-dark-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.25);
            color: #22c55e;
            border-radius: var(--radius);
        }

        .alert-dark-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #ef4444;
            border-radius: var(--radius);
        }

        /* ── Responsive ──────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .navbar-toggler-custom { display: flex; }
            .app-sidebar { transform: translateX(-100%); }
            .app-sidebar.open { transform: translateX(0); }
            .app-main { margin-left: 0; }
            .page-content { padding: 1.25rem; }
        }

        @media (max-width: 575.98px) {
            .navbar-user-info { display: none; }
            .page-title { font-size: 1.3rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Navbar ─────────────────────────────────────────────────────────────── --}}
@include('components.navbar')

{{-- ── Sidebar ─────────────────────────────────────────────────────────────── --}}
@include('components.sidebar')

{{-- ── Main Wrapper ─────────────────────────────────────────────────────────── --}}
<div class="app-main">
    <div class="page-content">

        {{-- Session Alerts --}}
        @if(session('success'))
            <div class="alert alert-dark-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-dark-error alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- ── Footer ─────────────────────────────────────────────────────────────── --}}
    @include('components.footer')
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('appSidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>
