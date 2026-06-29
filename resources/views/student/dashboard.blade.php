@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page_title', 'My Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Welcome back, {{ $user->name }} 👋</h1>
    <p class="page-subtitle">Here's an overview of your academic achievements</p>
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
        <div class="card-dark">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; margin:0; letter-spacing:-0.02em;">Recent Achievements</h2>
                    <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">Your latest submitted achievements</p>
                </div>
                <a href="#" style="font-size:0.8rem; color:var(--text-secondary); text-decoration:none; font-weight:500;"
                    onmouseover="this.style.color='var(--text-primary)';"
                    onmouseout="this.style.color='var(--text-secondary)';">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            @if($achievements->isEmpty())
                <div style="text-align:center; padding:3rem 1rem; color:var(--text-muted);">
                    <i class="bi bi-trophy" style="font-size:2.5rem; display:block; margin-bottom:0.75rem; opacity:0.3;"></i>
                    <p style="margin:0; font-size:0.875rem;">No achievements yet.</p>
                    <a href="#" style="color:var(--text-secondary); text-decoration:none; font-size:0.8rem; font-weight:500;">
                        Submit your first achievement →
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
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
        <div class="card-dark" style="margin-bottom:1rem;">
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

        {{-- Quick Actions --}}
        <div class="card-dark">
            <h2 style="font-size:1rem; font-weight:700; margin:0 0 1rem; letter-spacing:-0.02em;">Quick Actions</h2>
            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <a href="#" style="display:flex; align-items:center; gap:0.75rem; padding:0.7rem 0.9rem; background:var(--accent-dim); border:1px solid var(--border); border-radius:var(--radius); text-decoration:none; color:var(--text-primary); font-size:0.85rem; font-weight:500; transition:var(--transition);"
                    onmouseover="this.style.background='var(--accent-hover)';"
                    onmouseout="this.style.background='var(--accent-dim)';">
                    <i class="bi bi-plus-circle-fill" style="color:#22c55e;"></i>
                    Submit New Achievement
                </a>
                <a href="#" style="display:flex; align-items:center; gap:0.75rem; padding:0.7rem 0.9rem; background:var(--accent-dim); border:1px solid var(--border); border-radius:var(--radius); text-decoration:none; color:var(--text-primary); font-size:0.85rem; font-weight:500; transition:var(--transition);"
                    onmouseover="this.style.background='var(--accent-hover)';"
                    onmouseout="this.style.background='var(--accent-dim)';">
                    <i class="bi bi-person-fill" style="color:#3b82f6;"></i>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
