@extends('layouts.app')

@section('title', 'Faculty Dashboard')
@section('page_title', 'Faculty Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Faculty Dashboard</h1>
    <p class="page-subtitle">Monitor and manage all student achievements across the portal</p>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Students</div>
                <div class="stat-value" style="color:#60a5fa;">{{ $stats['total_students'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon white">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Achievements</div>
                <div class="stat-value">{{ $stats['total_achievements'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="stat-label">Pending Review</div>
                <div class="stat-value" style="color:#f59e0b;">{{ $stats['pending_review'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="stat-label">Approved</div>
                <div class="stat-value" style="color:#22c55e;">{{ $stats['approved'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Achievements --}}
    <div class="col-lg-8">
        <div class="card-custom">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; margin:0; letter-spacing:-0.02em;">Recent Submissions</h2>
                    <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">Latest achievement submissions across all students</p>
                </div>
                @if($stats['pending_review'] > 0)
                    <span class="status-badge pending">
                        {{ $stats['pending_review'] }} Pending
                    </span>
                @endif
            </div>

            @if($recentAchievements->isEmpty())
                <div style="text-align:center; padding:3rem 1rem; color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:0.75rem; opacity:0.3;"></i>
                    <p style="margin:0; font-size:0.875rem;">No achievements submitted yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Achievement</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAchievements as $achievement)
                            <tr>
                                <td>
                                    <div style="font-weight:500; font-size:0.85rem;">{{ $achievement->user->name }}</div>
                                    <div style="font-size:0.72rem; color:var(--text-muted);">{{ $achievement->user->student_id ?? '' }}</div>
                                </td>
                                <td style="font-size:0.85rem;">{{ Str::limit($achievement->title, 30) }}</td>
                                <td style="color:var(--text-secondary); font-size:0.82rem;">{{ $achievement->category ?? '—' }}</td>
                                <td style="color:var(--text-secondary); font-size:0.82rem;">{{ $achievement->date_achieved->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $achievement->status }}">
                                        {{ ucfirst($achievement->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('faculty.achievements.index') }}" style="font-size:0.78rem; color:var(--text-secondary); text-decoration:none; font-weight:500;"
                                        onmouseover="this.style.color='var(--text-primary)';"
                                        onmouseout="this.style.color='var(--text-secondary)';">
                                        Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4">
        {{-- Faculty Info --}}
        <div class="card-custom" style="margin-bottom:1rem;">
            <h2 style="font-size:1rem; font-weight:700; margin:0 0 1rem; letter-spacing:-0.02em;">My Profile</h2>
            <div style="display:flex; align-items:center; gap:0.75rem; padding-bottom:1rem; border-bottom:1px solid var(--border); margin-bottom:1rem;">
                <div style="width:52px; height:52px; border-radius:50%; background:var(--accent-dim); border:1px solid var(--accent-hover); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem; color:var(--accent); flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:600; font-size:0.9rem;">{{ $user->name }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $user->email }}</div>
                </div>
            </div>
            <span class="role-badge faculty">Faculty Member</span>
        </div>

        {{-- Recent Students --}}
        <div class="card-custom">
            <h2 style="font-size:1rem; font-weight:700; margin:0 0 1rem; letter-spacing:-0.02em;">Recent Students</h2>
            @if($students->isEmpty())
                <p style="color:var(--text-muted); font-size:0.85rem; text-align:center; padding:1rem 0;">No students registered yet.</p>
            @else
                @foreach($students as $student)
                <div style="display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0; {{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                    <div style="width:36px; height:36px; border-radius:50%; background:var(--accent-dim); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.75rem; flex-shrink:0;">
                        {{ strtoupper(substr($student->name, 0, 2)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.85rem; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->name }}</div>
                        <div style="font-size:0.72rem; color:var(--text-muted);">{{ $student->department ?? 'No dept.' }} · {{ $student->year_level ?? '' }}</div>
                    </div>
                    <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap;">
                        {{ $student->achievements_count }} achievement{{ $student->achievements_count != 1 ? 's' : '' }}
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
