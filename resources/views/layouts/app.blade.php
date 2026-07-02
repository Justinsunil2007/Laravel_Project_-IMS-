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
            --bg-primary:    #ffffff;
            --bg-secondary:  #fafafa;
            --bg-card:       #ffffff;
            --bg-hover:      #f4f4f5;
            --border:        #e4e4e7;
            --border-light:  #f4f4f5;
            --text-primary:  #18181b;
            --text-secondary:#3f3f46;
            --text-muted:    #71717a;
            --accent:        #4F46E5;
            --accent-dim:    rgba(79, 70, 229, 0.08);
            --accent-hover:  rgba(79, 70, 229, 0.15);
            --success:       #16a34a;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --info:          #3b82f6;
            --sidebar-width: 260px;
            --navbar-height: 64px;
            --radius:        12px;
            --radius-lg:     16px;
            --shadow:        0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.02);
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
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* ── Navbar ─────────────────────────────────────────── */
        .app-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background: var(--bg-primary);
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
            background: rgba(37,99,235,0.1);
            color: #2563eb;
            border: 1px solid rgba(37,99,235,0.2);
        }

        .role-badge.faculty {
            background: var(--accent-dim);
            color: var(--accent);
            border: 1px solid var(--accent-hover);
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
            font-weight: 600;
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
            background: rgba(220,38,38,0.05);
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
        .card-custom {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .card-custom:hover {
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
            box-shadow: var(--shadow);
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

        .stat-icon.white   { background: rgba(0,0,0,0.05); color: #000; border: 1px solid rgba(0,0,0,0.1); }
        .stat-icon.green   { background: rgba(22,163,74,0.1);   color: #16a34a; }
        .stat-icon.yellow  { background: rgba(217,119,6,0.1);  color: #d97706; }
        .stat-icon.red     { background: rgba(220,38,38,0.1);   color: #dc2626; }
        .stat-icon.blue    { background: rgba(37,99,235,0.1);  color: #2563eb; }
        .stat-icon.purple  { background: rgba(147,51,234,0.1);  color: #9333ea; }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
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
        .table-custom {
            color: var(--text-primary);
            border-color: var(--border);
            background: #ffffff;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .table-custom thead th {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-color: var(--border);
            padding: 0.9rem 1rem;
        }

        .table-custom tbody td {
            border-color: var(--border);
            padding: 0.85rem 1rem;
            vertical-align: middle;
            font-size: 0.875rem;
            background: #ffffff;
        }

        .table-custom tbody tr {
            transition: var(--transition);
        }

        .table-custom tbody tr:hover td {
            background: var(--bg-hover);
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

        .status-badge.pending  { background: rgba(217,119,6,0.1); color: #d97706; border: 1px solid rgba(217,119,6,0.2); }
        .status-badge.approved { background: rgba(22,163,74,0.1);  color: #16a34a; border: 1px solid rgba(22,163,74,0.2); }
        .status-badge.rejected { background: rgba(220,38,38,0.1);  color: #dc2626; border: 1px solid rgba(220,38,38,0.2); }

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
        .alert-banner {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border-radius: var(--radius);
            border: 1px solid;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-banner-success { background: rgba(22,163,74,0.08); border-color: rgba(22,163,74,0.25); color: #14532d; }
        .alert-banner-danger  { background: rgba(220,38,38,0.08);  border-color: rgba(220,38,38,0.25);  color: #7f1d1d; }
        .alert-banner-warning { background: rgba(217,119,6,0.08);  border-color: rgba(217,119,6,0.25);  color: #78350f; }
        .alert-banner-info    { background: rgba(37,99,235,0.08);  border-color: rgba(37,99,235,0.25);  color: #1e3a8a; }

        .alert-banner-icon {
            font-size: 1.15rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .alert-banner-success .alert-banner-icon { color: #16a34a; }
        .alert-banner-danger  .alert-banner-icon { color: #dc2626; }
        .alert-banner-warning .alert-banner-icon { color: #d97706; }
        .alert-banner-info    .alert-banner-icon { color: #3b82f6; }

        .alert-banner-body {
            flex: 1;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        .alert-banner-close {
            background: none;
            border: none;
            font-size: 0.8rem;
            cursor: pointer;
            opacity: 0.6;
            padding: 0;
            color: inherit;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .alert-banner-close:hover { opacity: 1; }

        /* Form Controls for Light Theme */
        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: var(--radius);
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px var(--accent-dim);
            outline: none;
        }
        
        .form-control-custom {
            width: 100%;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            padding: 0.7rem 0.9rem;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .form-control-custom.with-icon {
            padding-left: 2.5rem;
        }
        .form-control-custom:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-dim);
        }
        .form-control-custom::placeholder { color: var(--text-muted); }
        .form-control-custom.is-invalid { border-color: var(--danger); }
        
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
            pointer-events: none;
        }
        .input-wrapper select.form-control-custom {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }

        .invalid-feedback-custom {
            color: var(--danger);
            font-size: 0.78rem;
            margin-top: 0.3rem;
        }
        .form-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.4rem;
        }

        /* Buttons for Light Theme */
        .btn-primary {
            background: var(--accent);
            color: #ffffff;
            border: 1px solid var(--accent);
            border-radius: var(--radius);
            font-weight: 500;
            padding: 0.6rem 1.25rem;
            transition: var(--transition);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: #4338ca;
            color: #ffffff;
            border-color: #4338ca;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transform: translateY(-1px);
        }
        .btn-outline-primary {
            background: #ffffff;
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: var(--radius);
            font-weight: 500;
            padding: 0.6rem 1.25rem;
            transition: var(--transition);
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: var(--accent-dim);
            color: var(--accent);
            border-color: var(--accent);
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
            <div class="alert-banner alert-banner-success alert-banner-dismissible mb-4" role="alert" id="alert-success">
                <div class="alert-banner-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="alert-banner-body">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="alert-banner-close" onclick="this.closest('.alert-banner').remove()" aria-label="Dismiss">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-banner alert-banner-danger alert-banner-dismissible mb-4" role="alert" id="alert-error">
                <div class="alert-banner-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="alert-banner-body">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="alert-banner-close" onclick="this.closest('.alert-banner').remove()" aria-label="Dismiss">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert-banner alert-banner-warning alert-banner-dismissible mb-4" role="alert" id="alert-warning">
                <div class="alert-banner-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
                <div class="alert-banner-body">
                    <strong>Warning!</strong> {{ session('warning') }}
                </div>
                <button type="button" class="alert-banner-close" onclick="this.closest('.alert-banner').remove()" aria-label="Dismiss">
                    <i class="bi bi-x-lg"></i>
                </button>
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
