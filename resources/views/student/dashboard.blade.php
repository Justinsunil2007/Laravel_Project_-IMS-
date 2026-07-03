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
                        
    {{-- Right Column: Recent Updates --}}
    <div class="col-lg-4">
        <div class="card-custom">
            @php
                $statusMap = [
                    'approved'  => ['label' => 'Approved',    'color' => '#16a34a', 'bg' => 'rgba(22,163,74,0.12)',    'icon' => 'check-circle-fill'],
                    'pending'   => ['label' => 'Under Review', 'color' => '#d97706', 'bg' => 'rgba(217,119,6,0.12)',   'icon' => 'hourglass-split'],
                    'rejected'  => ['label' => 'Rejected',    'color' => '#dc2626', 'bg' => 'rgba(220,38,38,0.12)',    'icon' => 'x-circle-fill'],
                    'submitted' => ['label' => 'Submitted',   'color' => '#2563eb', 'bg' => 'rgba(37,99,235,0.12)',    'icon' => 'arrow-up-circle-fill'],
                    'updated'   => ['label' => 'Updated',     'color' => '#7c3aed', 'bg' => 'rgba(124,58,237,0.12)',   'icon' => 'pencil-square'],
                    'profile'   => ['label' => 'Profile',     'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.12)',   'icon' => 'person-fill'],
                    'deleted'   => ['label' => 'Deleted',     'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.12)',    'icon' => 'trash-fill'],
                ];
            @endphp

            {{-- Card Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; margin:0; letter-spacing:-0.02em;">🔔 Recent Updates</h2>
                    <p style="margin:0.35rem 0 0; color:var(--text-muted); font-size:0.8rem; line-height:1.5;">
                        Your latest achievement and profile activity.
                    </p>
                </div>
            </div>

            {{-- Feed --}}
            @if($updates->isEmpty())
                <div style="text-align:center; padding:2.5rem 1rem;">
                    <div style="font-size:2.5rem; margin-bottom:1rem; color:#8b5cf6; opacity:0.6;">🔔</div>
                    <p style="margin:0; font-size:0.95rem; font-weight:700; color:var(--text-primary);">No recent updates yet</p>
                    <p style="margin:0.5rem 0 0; color:var(--text-muted); font-size:0.85rem; line-height:1.6;">
                        Your latest achievement activities will appear here.
                    </p>
                </div>
            @else
                <div style="display:flex; flex-direction:column; gap:0.85rem;">
                    @foreach($updates as $update)
                        @php
                            $data = $update->data;
                            $meta = $statusMap[$data['status'] ?? 'submitted'] ?? $statusMap['submitted'];
                        @endphp
                        <div style="display:flex; align-items:flex-start; gap:0.85rem; padding:1rem; background:#f9fafb; border:1px solid var(--border); border-radius:16px; transition:var(--transition);"
                             onmouseover="this.style.background='#f3f4f6'; this.style.borderColor='var(--border-light)';"
                             onmouseout="this.style.background='#f9fafb'; this.style.borderColor='var(--border)';">

                            {{-- Status Icon --}}
                            <div style="width:42px; height:42px; border-radius:14px; display:flex; align-items:center; justify-content:center; background:{{ $meta['bg'] }}; color:{{ $meta['color'] }}; flex-shrink:0;">
                                <i class="bi bi-{{ $meta['icon'] }}" style="font-size:1.05rem;"></i>
                            </div>

                            {{-- Content --}}
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; flex-wrap:wrap;">
                                    <div style="font-size:0.875rem; font-weight:700; color:var(--text-primary); line-height:1.3;">
                                        {{ $data['title'] ?? 'Activity Update' }}
                                    </div>
                                    <span style="font-size:0.68rem; font-weight:700; color:{{ $meta['color'] }}; background:{{ $meta['bg'] }}; padding:0.2rem 0.6rem; border-radius:999px; white-space:nowrap;">
                                        {{ $meta['label'] }}
                                    </span>
                                </div>

                                <p style="margin:0.45rem 0 0; color:var(--text-secondary); font-size:0.82rem; line-height:1.55;">
                                    {{ $data['message'] ?? '' }}
                                </p>

                                @if(!empty($data['remarks']))
                                    <p style="margin:0.5rem 0 0; color:var(--text-muted); font-size:0.78rem; line-height:1.5; font-style:italic;">
                                        <i class="bi bi-chat-left-text me-1"></i>{{ $data['remarks'] }}
                                    </p>
                                @endif

                                <div style="margin-top:0.65rem; display:flex; flex-wrap:wrap; align-items:center; gap:0.4rem; font-size:0.75rem; color:var(--text-muted);">
                                    <i class="bi bi-clock" style="font-size:0.72rem;"></i>
                                    <span>{{ $update->created_at->diffForHumans() }}</span>
                                    @if(!empty($data['achievement_title']))
                                        <span style="color:var(--border-light);">·</span>
                                        <span style="max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $data['achievement_title'] }}">
                                            {{ $data['achievement_title'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
