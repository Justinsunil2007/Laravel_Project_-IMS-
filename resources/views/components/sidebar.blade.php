<aside class="app-sidebar" id="appSidebar" role="complementary" aria-label="Sidebar navigation">

    @auth
        {{-- User Card at Top of Sidebar --}}
        <div style="padding:1.25rem; border-bottom:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:42px; height:42px; border-radius:50%; background:var(--accent-dim); border:1px solid var(--border-light); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem; color:var(--text-primary); flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div style="min-width:0;">
                    <div style="font-weight:600; font-size:0.875rem; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size:0.7rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        @if(auth()->user()->isStudent())
                            {{ auth()->user()->department ?? 'Student' }} · {{ auth()->user()->year_level ?? '' }}
                        @else
                            Faculty Member
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Links --}}
        @if(auth()->user()->isStudent())
            {{-- STUDENT NAVIGATION --}}
            <div class="sidebar-section-label">Overview</div>
            <a href="{{ route('student.dashboard') }}" id="nav-student-dashboard"
               class="sidebar-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>

            <div class="sidebar-section-label">Achievements</div>
            <a href="{{ route('student.achievements.index') }}" class="sidebar-nav-item {{ request()->routeIs('student.achievements.index') ? 'active' : '' }}">
                <i class="bi bi-trophy-fill"></i>
                My Achievements
            </a>
            <a href="{{ route('student.achievements.create') }}" class="sidebar-nav-item {{ request()->routeIs('student.achievements.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i>
                Submit Achievement
            </a>

            <div class="sidebar-section-label">Profile</div>
            <a href="{{ route('student.profile.edit') }}" class="sidebar-nav-item {{ request()->routeIs('student.profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person-fill"></i>
                My Profile
            </a>

        @elseif(auth()->user()->isFaculty())
            {{-- FACULTY NAVIGATION --}}
            <div class="sidebar-section-label">Overview</div>
            <a href="{{ route('faculty.dashboard') }}" id="nav-faculty-dashboard"
               class="sidebar-nav-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>

            <div class="sidebar-section-label">Management</div>
            <a href="{{ route('faculty.achievements.index') }}" class="sidebar-nav-item {{ request()->routeIs('faculty.achievements.index') ? 'active' : '' }}">
                <i class="bi bi-trophy-fill"></i>
                All Achievements
            </a>
            <a href="{{ route('faculty.achievements.index', ['status' => 'pending']) }}" class="sidebar-nav-item {{ request()->fullUrlIs('*status=pending*') ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i>
                Pending Review
                @php $pending = \App\Models\Achievement::where('status','pending')->count(); @endphp
                @if($pending > 0)
                    <span style="margin-left:auto; background:var(--warning); color:#fff; font-size:0.65rem; font-weight:700; border-radius:20px; padding:1px 7px;">{{ $pending }}</span>
                @endif
            </a>

        @endif

        {{-- Logout at Bottom --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" id="sidebar-logout-btn">
                    <i class="bi bi-box-arrow-left"></i>
                    Sign Out
                </button>
            </form>
        </div>
    @endauth
</aside>
