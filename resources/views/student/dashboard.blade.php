@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page_title', 'My Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Welcome back, {{ $user->name }} 👋</h1>
    <p class="page-subtitle">Here's an overview of your academic achievements</p>
</div>

<div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:2rem; background:#f3f4f6; border-radius:24px; padding:1.25rem 1.5rem; box-shadow:0 20px 40px rgba(15,23,42,0.08);">
    <div style="display:flex; flex:1 1 420px; min-width:280px; align-items:center; gap:1rem;">
        <a href="{{ route('student.achievements.create') }}" class="btn-primary" style="background:#ffffff; color:#111827; border:1px solid #d1d5db; display:inline-flex; align-items:center; justify-content:center; gap:0.75rem; min-width:220px; padding:0.85rem 1.4rem; font-size:1rem; box-shadow:0 12px 24px rgba(15,23,42,0.08);">
            <i class="bi bi-plus-circle-fill" style="font-size:1.1rem; color:#111827;"></i>
            Submit New Achievement
        </a>

        <div style="min-width:0;">
            <div style="font-size:1rem; font-weight:700; color:#111827; line-height:1.25;">Have a new achievement to share?</div>
            <div style="margin-top:0.25rem; color:#4B5563; font-size:0.92rem;">Submit your achievements and get recognized.</div>
        </div>
    </div>

    <div style="width:96px; height:96px; display:flex; align-items:center; justify-content:center; border-radius:22px; background:rgba(15,23,42,0.06); flex-shrink:0;">
        <i class="bi bi-trophy-fill" style="font-size:2rem; color:#111827;"></i>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon white">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Achievements</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Approved</div>
                <div class="stat-value" style="color:#22c55e;">{{ $stats['approved'] }}</div>
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
                <div class="stat-value" style="color:#f59e0b;">{{ $stats['pending'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Rejected</div>
                <div class="stat-value" style="color:#ef4444;">{{ $stats['rejected'] }}</div>
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
                    <h2 style="font-size:1rem; font-weight:700; margin:0; letter-spacing:-0.02em;">Recent Achievements</h2>
                    <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">Your latest submitted achievements</p>
                </div>
                <a href="{{ route('student.achievements.index') }}" style="font-size:0.8rem; color:var(--text-secondary); text-decoration:none; font-weight:500;"
                    onmouseover="this.style.color='var(--text-primary)';"
                    onmouseout="this.style.color='var(--text-secondary)';">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            @if($achievements->isEmpty())
                <div style="text-align:center; padding:3rem 1rem; color:var(--text-muted);">
                    <i class="bi bi-trophy" style="font-size:2.5rem; display:block; margin-bottom:0.75rem; opacity:0.3;"></i>
                    <p style="margin:0; font-size:0.875rem;">No achievements yet.</p>
                    <a href="{{ route('student.achievements.create') }}" style="color:var(--text-secondary); text-decoration:none; font-size:0.8rem; font-weight:500;">
                        Submit your first achievement →
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($achievements as $achievement)
                            <tr>
                                <td style="font-weight:500;">{{ $achievement->title }}</td>
                                <td style="color:var(--text-secondary);">{{ $achievement->category ?? '—' }}</td>
                                <td style="color:var(--text-secondary);">{{ $achievement->date_achieved->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $achievement->status }}">
                                        {{ ucfirst($achievement->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="col-lg-4">
        <div class="card-custom" style="margin-bottom:1rem;">
            <h2 style="font-size:1rem; font-weight:700; margin:0 0 1rem; letter-spacing:-0.02em;">Student Profile</h2>

            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem; padding-bottom:1.25rem; border-bottom:1px solid var(--border);">
                <div style="width:52px; height:52px; border-radius:50%; background:var(--accent-dim); border:1px solid var(--border-light); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem; flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:600; font-size:0.9rem;">{{ $user->name }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $user->email }}</div>
                </div>
            </div>

            @php
                $profileItems = [
                    ['icon' => 'bi-card-text', 'label' => 'Student ID',   'value' => $user->student_id ?? 'Not set'],
                    ['icon' => 'bi-building',  'label' => 'Department',   'value' => $user->department ?? 'Not set'],
                    ['icon' => 'bi-layers',    'label' => 'Year Level',   'value' => $user->year_level ?? 'Not set'],
                ];
            @endphp

            @foreach($profileItems as $item)
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                    <div style="width:32px; height:32px; border-radius:8px; background:var(--accent-dim); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="bi {{ $item['icon'] }}" style="font-size:0.85rem; color:var(--text-secondary);"></i>
                    </div>
                    <div>
                        <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.04em;">{{ $item['label'] }}</div>
                        <div style="font-size:0.875rem; font-weight:500; color:var(--text-primary);">{{ $item['value'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Notifications & Recent Activity --}}
        <div class="card-custom">
            @php
                $statusMap = [
                    'approved' => ['label' => 'Approved', 'color' => '#16a34a', 'bg' => 'rgba(22,163,74,0.12)', 'icon' => 'check-circle-fill'],
                    'pending'  => ['label' => 'Under Review', 'color' => '#d97706', 'bg' => 'rgba(217,119,6,0.12)', 'icon' => 'hourglass-split'],
                    'rejected' => ['label' => 'Rejected', 'color' => '#dc2626', 'bg' => 'rgba(220,38,38,0.12)', 'icon' => 'x-circle-fill'],
                    'submitted'=> ['label' => 'Submitted', 'color' => '#2563eb', 'bg' => 'rgba(37,99,235,0.12)', 'icon' => 'arrow-up-circle-fill'],
                    'updated'  => ['label' => 'Updated', 'color' => '#7c3aed', 'bg' => 'rgba(124,58,237,0.12)', 'icon' => 'pencil-square'},
                    'profile'  => ['label' => 'Profile', 'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.12)', 'icon' => 'person-fill'],
                    'deleted'  => ['label' => 'Deleted', 'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.12)', 'icon' => 'trash-fill'],
                ];
            @endphp

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; margin:0; letter-spacing:-0.02em;">🔔 Notifications & Recent Activity</h2>
                    <p style="margin:0.45rem 0 0; color:var(--text-secondary); font-size:0.85rem; max-width:320px; line-height:1.5;">See achievement updates and profile activity in one place so you can act quickly.</p>
                </div>
            </div>

            @if($notifications->isEmpty())
                <div style="text-align:center; padding:2rem 1rem; color:var(--text-secondary);">
                    <div style="font-size:2rem; margin-bottom:1rem; color:#8b5cf6;">🔔</div>
                    <p style="margin:0; font-size:1rem; font-weight:700; color:var(--text-primary);">No notifications yet</p>
                    <p style="margin:0.5rem 0 0; color:var(--text-muted); font-size:0.9rem;">Once there’s activity on your achievements or profile, it will appear here.</p>
                </div>
            @else
                <div style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:1.25rem;">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $meta = $statusMap[$data['status'] ?? 'submitted'] ?? $statusMap['submitted'];
                        @endphp
                        <div style="display:flex; align-items:flex-start; gap:0.85rem; padding:1rem; background:#ffffff; border:1px solid var(--border); border-radius:18px; transition:var(--transition);">
                            <div style="width:44px; height:44px; border-radius:16px; display:flex; align-items:center; justify-content:center; background:{{ $meta['bg'] }}; color:{{ $meta['color'] }}; flex-shrink:0;">
                                <i class="bi bi-{{ $meta['icon'] }}" style="font-size:1.1rem;"></i>
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; flex-wrap:wrap;">
                                    <div style="font-size:0.95rem; font-weight:700; color:var(--text-primary);">{{ $data['title'] ?? 'Activity update' }}</div>
                                    <span style="font-size:0.72rem; font-weight:700; color:{{ $meta['color'] }}; background:{{ $meta['bg'] }}; padding:0.25rem 0.65rem; border-radius:999px;">{{ $meta['label'] }}</span>
                                </div>
                                <p style="margin:0.6rem 0 0; color:var(--text-secondary); font-size:0.86rem; line-height:1.6;">{{ $data['message'] ?? '' }}</p>
                                @if(!empty($data['remarks']))
                                    <p style="margin:0.65rem 0 0; color:var(--text-secondary); font-size:0.82rem; line-height:1.5;">Faculty remarks: {{ $data['remarks'] }}</p>
                                @endif
                                <div style="margin-top:0.9rem; display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center; font-size:0.78rem; color:var(--text-muted);">
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    @if(!empty($data['achievement_title']))
                                        <span>&bull; {{ $data['achievement_title'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div style="border-top:1px solid var(--border); padding-top:1rem;">
                <h3 style="font-size:0.95rem; margin:0 0 1rem; color:var(--text-primary);">Recent Activity</h3>
                <div style="display:flex; flex-direction:column; gap:0.85rem;">
                    @forelse($activities as $activity)
                        @php
                            $data = $activity->data;
                            $meta = $statusMap[$data['status'] ?? 'submitted'] ?? $statusMap['submitted'];
                        @endphp
                        <div style="display:flex; align-items:flex-start; gap:0.85rem;">
                            <div style="width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:{{ $meta['bg'] }}; color:{{ $meta['color'] }}; flex-shrink:0;">
                                <i class="bi bi-{{ $meta['icon'] }}" style="font-size:1rem;"></i>
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:0.9rem; font-weight:700; color:var(--text-primary);">{{ $data['title'] ?? 'Activity update' }}</div>
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; flex-wrap:wrap; margin-top:0.35rem; color:var(--text-secondary); font-size:0.82rem;">
                                    <span>{{ \Illuminate\Support\Str::limit($data['message'] ?? '', 68) }}</span>
                                    <span>{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; padding:1.5rem 1rem; color:var(--text-secondary);">
                            <div style="font-size:1.6rem; margin-bottom:0.75rem; color:#8b5cf6;">📌</div>
                            <p style="margin:0; font-size:0.95rem; font-weight:700; color:var(--text-primary);">No recent activity available.</p>
                            <p style="margin:0.5rem 0 0; color:var(--text-muted); font-size:0.88rem;">Your latest achievement events will appear here once you start submitting work.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
