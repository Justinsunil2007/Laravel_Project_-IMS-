<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Achievement Management Portal — @yield('meta_description', 'Sign in to access your portal.')">
    <title>@yield('title', 'Authentication') — Student Achievement Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-primary:   #0a0a0a;
            --bg-secondary: #111111;
            --bg-card:      #161616;
            --border:       #2a2a2a;
            --border-light: #333333;
            --text-primary: #f5f5f5;
            --text-secondary:#a0a0a0;
            --text-muted:   #666666;
            --accent-dim:   rgba(255,255,255,0.06);
            --accent-hover: rgba(255,255,255,0.1);
            --radius:       10px;
            --radius-lg:    16px;
            --transition:   all 0.2s ease;
        }

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

        /* Animated background grid */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.3;
            z-index: 0;
            pointer-events: none;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .auth-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border);
            background: rgba(17,17,17,0.8);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .auth-brand {
            font-weight: 800;
            font-size: 1rem;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .auth-brand i { color: var(--text-secondary); }

        .auth-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            background: var(--accent-dim);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1rem;
        }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-top: 1.5rem;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            display: block;
        }

        .form-control-dark {
            width: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            padding: 0.7rem 0.9rem 0.7rem 2.5rem;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            outline: none;
        }

        .form-control-dark:focus {
            border-color: var(--border-light);
            background: var(--bg-hover, #1e1e1e);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.04);
        }

        .form-control-dark::placeholder { color: var(--text-muted); }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .input-wrapper select.form-control-dark {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }

        /* Error states */
        .form-control-dark.is-invalid {
            border-color: rgba(239,68,68,0.6);
        }

        .invalid-feedback-dark {
            color: #ef4444;
            font-size: 0.78rem;
            margin-top: 0.3rem;
        }

        /* Submit Button */
        .btn-auth {
            width: 100%;
            background: var(--text-primary);
            color: var(--bg-primary);
            border: none;
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: -0.01em;
            margin-top: 0.5rem;
        }

        .btn-auth:hover {
            background: #e5e5e5;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(255,255,255,0.08);
        }

        .btn-auth:active { transform: translateY(0); }

        /* Links */
        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .auth-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .auth-links a:hover { color: var(--text-primary); }

        /* Auth footer */
        .auth-footer {
            padding: 1rem 2rem;
            border-top: 1px solid var(--border);
            background: rgba(17,17,17,0.8);
            backdrop-filter: blur(10px);
            color: var(--text-muted);
            font-size: 0.75rem;
            text-align: center;
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .auth-divider span {
            color: var(--text-muted);
            font-size: 0.75rem;
            white-space: nowrap;
        }

        /* Alert */
        .alert-auth-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #f87171;
            border-radius: var(--radius);
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
<div class="auth-wrapper">

    {{-- Auth Header --}}
    <header class="auth-header">
        <a href="{{ route('home') }}" class="auth-brand">
            <i class="bi bi-mortarboard-fill me-2"></i>
            Student Achievement Management Portal
        </a>
        <div style="font-size:0.75rem; color:var(--text-muted);">
            @yield('header_link')
        </div>
    </header>

    {{-- Auth Content --}}
    <main class="auth-main">
        <div class="auth-container">
            @yield('auth_content')
        </div>
    </main>

    {{-- Auth Footer --}}
    <footer class="auth-footer">
        &copy; {{ date('Y') }} Student Achievement Management Portal. All rights reserved.
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
