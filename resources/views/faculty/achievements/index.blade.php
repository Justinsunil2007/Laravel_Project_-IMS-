@extends('layouts.app')

@section('title', 'Review Achievements')
@section('page_title', 'Achievement Management')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">Achievement Management</h1>
        <p class="page-subtitle">Search, filter, and review all student achievement submissions.</p>
    </div>
    @php
        $pendingCount = \App\Models\Achievement::where('status','pending')->count();
    @endphp
    @if($pendingCount > 0)
        <span class="status-badge pending" style="font-size:0.8rem; padding:6px 14px; display:inline-flex; align-items:center; gap:5px;">
            <i class="bi bi-hourglass-split"></i>
            {{ $pendingCount }} Pending Review
        </span>
    @endif
</div>

{{-- Filters & Search --}}
<div class="card-custom mb-4">
    <form method="GET" action="{{ route('faculty.achievements.index') }}" class="filter-row">
        <div class="filter-search-group" style="flex:3;">
            <i class="bi bi-search filter-icon"></i>
            <input type="text" name="search" class="form-control-custom with-icon"
                placeholder="Search by student name or roll no..." value="{{ request('search') }}">
        </div>
        <div class="filter-select-group">
            <i class="bi bi-tags-fill filter-icon"></i>
            <select name="category" class="form-control-custom with-icon">
                <option value="">All Categories</option>
                <option value="Academic"   {{ request('category') == 'Academic' ? 'selected' : '' }}>Academic</option>
                <option value="Sports"     {{ request('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                <option value="Arts"       {{ request('category') == 'Arts' ? 'selected' : '' }}>Arts / Culture</option>
                <option value="Technology" {{ request('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                <option value="Research"   {{ request('category') == 'Research' ? 'selected' : '' }}>Research</option>
                <option value="Other"      {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="filter-select-group">
            <i class="bi bi-funnel-fill filter-icon"></i>
            <select name="status" class="form-control-custom with-icon">
                <option value="">All Statuses</option>
                <option value="pending"  {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn-primary-custom" id="filter-btn">
            <i class="bi bi-search"></i> Filter
        </button>
        @if(request('search') || request('category') || request('status'))
            <a href="{{ route('faculty.achievements.index') }}" class="btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        @endif
    </form>
</div>

{{-- Results --}}
<div class="card-custom">
    @if($achievements->isEmpty())
        {{-- Empty State --}}
        <div class="empty-state-container">
            <div class="empty-state-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h3 class="empty-state-title">
                @if(request('search') || request('category') || request('status'))
                    No Matching Achievements
                @else
                    No Achievements Submitted
                @endif
            </h3>
            <p class="empty-state-desc">
                @if(request('search') || request('category') || request('status'))
                    No achievement records match your current filter criteria. Try adjusting your search.
                @else
                    No students have submitted achievements yet. They will appear here once submitted.
                @endif
            </p>
            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('faculty.achievements.index') }}" class="btn-secondary-custom">
                    <i class="bi bi-x-circle"></i> Clear Filters
                </a>
            @endif
        </div>

    @else
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width:22%;">Student</th>
                        <th style="width:28%;">Achievement</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end" style="width:120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($achievements as $achievement)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.65rem;">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($achievement->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:0.875rem; color:var(--text-primary);">
                                        {{ $achievement->user->name }}
                                    </div>
                                    <div style="font-size:0.72rem; color:var(--text-muted);">
                                        {{ $achievement->user->student_id ?? 'No ID' }}
                                        @if($achievement->user->department)
                                            · {{ $achievement->user->department }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.875rem; color:var(--text-primary);">
                                {{ Str::limit($achievement->title, 40) }}
                            </div>
                            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:2px;">
                                {{ $achievement->award_level ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="category-pill">{{ $achievement->category ?? '—' }}</span>
                        </td>
                        <td style="font-size:0.82rem; color:var(--text-secondary); white-space:nowrap;">
                            {{ $achievement->date_achieved->format('M d, Y') }}
                        </td>
                        <td>
                            <span class="status-badge {{ $achievement->status }}" style="display:inline-flex; align-items:center; gap:4px;">
                                @if($achievement->status === 'pending')
                                    <i class="bi bi-hourglass-split"></i>
                                @elseif($achievement->status === 'approved')
                                    <i class="bi bi-check-circle-fill"></i>
                                @else
                                    <i class="bi bi-x-circle-fill"></i>
                                @endif
                                {{ ucfirst($achievement->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn-review-trigger"
                                data-bs-toggle="modal"
                                data-bs-target="#reviewModal{{ $achievement->id }}">
                                <i class="bi bi-eye-fill"></i> Review
                            </button>
                        </td>
                    </tr>

                    {{-- Review Modal --}}
                    <div class="modal fade" id="reviewModal{{ $achievement->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content modal-custom">
                                {{-- Modal Header --}}
                                <div class="modal-header-custom">
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <div class="modal-icon-badge">
                                            <i class="bi bi-file-earmark-check-fill"></i>
                                        </div>
                                        <div>
                                            <h5 class="modal-title-custom">Review Achievement</h5>
                                            <p class="modal-subtitle-custom">Submitted by {{ $achievement->user->name }}</p>
                                        </div>
                                    </div>
                                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                {{-- Modal Body --}}
                                <div class="modal-body" style="padding:1.5rem;">
                                    {{-- Info Grid --}}
                                    <div class="review-info-grid">
                                        <div class="review-info-section">
                                            <div class="review-section-title">
                                                <i class="bi bi-person-fill"></i> Student Details
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Name</span>
                                                <span class="review-info-value">{{ $achievement->user->name }}</span>
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Roll No.</span>
                                                <span class="review-info-value">{{ $achievement->user->student_id ?? '—' }}</span>
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Department</span>
                                                <span class="review-info-value">{{ $achievement->user->department ?? '—' }}</span>
                                            </div>
                                        </div>

                                        <div class="review-info-section">
                                            <div class="review-section-title">
                                                <i class="bi bi-trophy-fill"></i> Achievement Details
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Title</span>
                                                <span class="review-info-value">{{ $achievement->title }}</span>
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Category</span>
                                                <span class="review-info-value">
                                                    <span class="category-pill">{{ $achievement->category }}</span>
                                                </span>
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Level</span>
                                                <span class="review-info-value">{{ $achievement->award_level ?? '—' }}</span>
                                            </div>
                                            <div class="review-info-item">
                                                <span class="review-info-label">Date</span>
                                                <span class="review-info-value">{{ $achievement->date_achieved->format('F d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    @if($achievement->description)
                                    <div style="margin-bottom:1.25rem;">
                                        <div class="review-section-title">
                                            <i class="bi bi-text-paragraph"></i> Description
                                        </div>
                                        <div class="remark-box">{{ $achievement->description }}</div>
                                    </div>
                                    @endif

                                    {{-- Certificate --}}
                                    <div style="margin-bottom:1.25rem;">
                                        <div class="review-section-title">
                                            <i class="bi bi-file-earmark-text-fill"></i> Certificate
                                        </div>
                                        @if($achievement->certificate_file)
                                            <a href="{{ Storage::url($achievement->certificate_file) }}"
                                               target="_blank"
                                               class="btn-secondary-custom" style="font-size:0.82rem; padding:0.5rem 1rem;">
                                                <i class="bi bi-box-arrow-up-right"></i> View Attached Certificate
                                            </a>
                                        @else
                                            <span style="font-size:0.875rem; color:var(--text-muted);">No certificate uploaded.</span>
                                        @endif
                                    </div>

                                    {{-- Review Form --}}
                                    <div class="review-form-section">
                                        <form action="{{ route('faculty.achievements.review', $achievement) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group" style="margin-bottom:1rem;">
                                                <label class="form-label">
                                                    Review Decision <span style="color:var(--danger);">*</span>
                                                </label>
                                                <div class="review-radio-group">
                                                    <label class="review-radio-option approved-option">
                                                        <input type="radio" name="status" value="approved"
                                                            {{ $achievement->status == 'approved' ? 'checked' : '' }}>
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        Approve
                                                    </label>
                                                    <label class="review-radio-option rejected-option">
                                                        <input type="radio" name="status" value="rejected"
                                                            {{ $achievement->status == 'rejected' ? 'checked' : '' }}>
                                                        <i class="bi bi-x-circle-fill"></i>
                                                        Reject
                                                    </label>
                                                    <label class="review-radio-option pending-option">
                                                        <input type="radio" name="status" value="pending"
                                                            {{ $achievement->status == 'pending' ? 'checked' : '' }}>
                                                        <i class="bi bi-hourglass-split"></i>
                                                        Keep Pending
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom:1.25rem;">
                                                <label class="form-label">Faculty Remarks
                                                    <span style="font-weight:400; color:var(--text-muted);">(Optional)</span>
                                                </label>
                                                <textarea name="faculty_remarks"
                                                    class="form-control-custom"
                                                    rows="3"
                                                    placeholder="Provide feedback to the student...">{{ $achievement->faculty_remarks }}</textarea>
                                                <p style="font-size:0.75rem; color:var(--text-muted); margin:0.3rem 0 0;">
                                                    This remark will be visible to the student.
                                                </p>
                                            </div>

                                            <div style="display:flex; justify-content:flex-end; gap:0.6rem;">
                                                <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn-primary-custom" id="save-review-{{ $achievement->id }}">
                                                    <i class="bi bi-check2-circle"></i> Save Review
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($achievements->hasPages())
            <div class="pagination-wrapper">
                {{ $achievements->appends(request()->query())->links() }}
            </div>
        @else
            <div class="table-footer-info">
                Showing {{ $achievements->count() }} {{ Str::plural('achievement', $achievements->count()) }}
            </div>
        @endif
    @endif
</div>

@push('styles')
<style>
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }
    .filter-search-group, .filter-select-group { position: relative; flex: 1; min-width: 150px; }
    .filter-search-group .form-control-custom,
    .filter-select-group .form-control-custom { padding-left: 2.4rem; }
    .filter-select-group .form-control-custom { appearance: none; cursor: pointer; }
    .filter-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.82rem;
        pointer-events: none;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: var(--radius);
        padding: 0.6rem 1.2rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }
    .btn-primary-custom:hover {
        background: #4338ca;
        border-color: #4338ca;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    }
    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #fff;
        color: var(--text-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.6rem 1.2rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }
    .btn-secondary-custom:hover { border-color: var(--accent); color: var(--accent); }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent-dim);
        border: 1px solid var(--accent-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--accent);
        flex-shrink: 0;
    }

    .category-pill {
        display: inline-block;
        background: var(--accent-dim);
        color: var(--accent);
        border: 1px solid var(--accent-hover);
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 2px 9px;
    }

    .btn-review-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--accent-dim);
        color: var(--accent);
        border: 1px solid var(--accent-hover);
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-review-trigger:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    .empty-state-container {
        text-align: center;
        padding: 4rem 1.5rem;
    }
    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--accent-dim);
        border: 1px solid var(--accent-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 2rem;
        color: var(--accent);
    }
    .empty-state-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.5rem; }
    .empty-state-desc { font-size: 0.875rem; color: var(--text-muted); margin: 0 0 1.5rem; max-width: 400px; margin-left: auto; margin-right: auto; }

    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border);
    }
    .pagination-wrapper .pagination { margin: 0; gap: 4px; }
    .pagination-wrapper .page-link { border-radius: 8px !important; font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); border-color: var(--border); padding: 5px 11px; }
    .pagination-wrapper .page-link:hover { color: var(--accent); background: var(--accent-dim); border-color: var(--accent-hover); }
    .pagination-wrapper .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #fff; }
    .table-footer-info { padding: 1rem 1.5rem; border-top: 1px solid var(--border); font-size: 0.8rem; color: var(--text-muted); }

    /* Modal */
    .modal-custom { border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.12); overflow: hidden; }
    .modal-header-custom {
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-icon-badge {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--accent-dim);
        border: 1px solid var(--accent-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1.1rem;
    }
    .modal-title-custom { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .modal-subtitle-custom { font-size: 0.75rem; color: var(--text-muted); margin: 0; }
    .modal-close-btn {
        background: none;
        border: 1px solid var(--border);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 0.8rem;
        transition: var(--transition);
    }
    .modal-close-btn:hover { background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.2); color: #dc2626; }

    .review-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 576px) { .review-info-grid { grid-template-columns: 1fr; } }

    .review-info-section {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem;
    }
    .review-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .review-info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.3rem 0;
        border-bottom: 1px solid var(--border);
    }
    .review-info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .review-info-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; flex-shrink: 0; }
    .review-info-value { font-size: 0.82rem; color: var(--text-primary); font-weight: 600; text-align: right; }

    .remark-box {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    .review-form-section {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem;
    }

    .review-radio-group {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }
    .review-radio-option {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        cursor: pointer;
        font-size: 0.82rem;
        font-weight: 600;
        transition: var(--transition);
        background: #fff;
        color: var(--text-secondary);
        user-select: none;
    }
    .review-radio-option input { display: none; }
    .review-radio-option:has(input:checked).approved-option { background: rgba(22,163,74,0.1); border-color: rgba(22,163,74,0.3); color: #16a34a; }
    .review-radio-option:has(input:checked).rejected-option { background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.3); color: #dc2626; }
    .review-radio-option:has(input:checked).pending-option  { background: rgba(217,119,6,0.1);  border-color: rgba(217,119,6,0.3);  color: #d97706; }
    .review-radio-option:hover { border-color: var(--accent-hover); color: var(--accent); }

    .status-badge { display: inline-flex; align-items: center; gap: 4px; }
</style>
@endpush
@endsection
