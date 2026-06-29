<nav class="app-navbar" role="navigation" aria-label="Main navigation">
    {{-- Mobile Toggle --}}
    <button class="navbar-toggler-custom" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">
        <i class="bi bi-list" style="font-size:1.2rem;"></i>
    </button>

    {{-- Brand --}}
    <a class="navbar-brand-text" href="{{ auth()->check() ? (auth()->user()->isStudent() ? route('student.dashboard') : route('faculty.dashboard')) : route('home') }}">
        <i class="bi bi-mortarboard-fill me-2" style="color: var(--text-secondary);"></i>
        SA<span>MP</span>
    </a>

    {{-- Divider --}}
    <div style="width:1px; height:20px; background:var(--border); flex-shrink:0;"></div>

    {{-- Current Page --}}
    <span style="font-size:0.875rem; color:var(--text-secondary); font-weight:400;">
        @yield('page_title', 'Dashboard')
    </span>

    {{-- Right side --}}
    <div class="navbar-right">
        @auth
            {{-- Role Badge --}}
            <span class="role-badge {{ auth()->user()->role }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>

            {{-- User Info --}}
            <div class="navbar-user-info text-end">
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ auth()->user()->email }}</small>
            </div>

            {{-- Avatar --}}
            <div class="navbar-avatar" title="{{ auth()->user()->name }}">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn" style="background:transparent; border:1px solid var(--border); color:var(--text-secondary); padding:6px 12px; font-size:0.8rem; border-radius:var(--radius); transition:var(--transition);"
                    onmouseover="this.style.borderColor='var(--danger)'; this.style.color='var(--danger)';"
                    onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)';">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        @endauth
    </div>
</nav>
