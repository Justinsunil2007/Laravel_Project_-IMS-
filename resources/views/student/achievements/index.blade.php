@extends('layouts.app')

@section('title', 'My Achievements')
@section('page_title', 'My Achievements')

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">My Achievements</h1>
        <p class="page-subtitle">View and manage all your submitted achievements.</p>
    </div>
    <a href="{{ route('student.achievements.create') }}" class="btn-primary-custom" id="submit-new-achievement-btn">
        <i class="bi bi-plus-circle-fill"></i>
        Submit New Achievement
    </a>
</div>

{{-- Filters & Search --}}
<div class="card-custom mb-4">
    <form method="GET" action="{{ route('student.achievements.index') }}" class="filter-row">
        <div class="filter-search-group">
            <i class="bi bi-search filter-search-icon"></i>
            <input type="text" name="search" class="form-control-custom with-icon" placeholder="Search achievements..." value="{{ request('search') }}">
        </div>
        <div class="filter-select-group">
            <i class="bi bi-tags-fill filter-select-icon"></i>
            <select name="category" class="form-control-custom with-icon">
                <option value="">All Categories</option>
                <option value="Academic"     {{ request('category') == 'Academic' ? 'selected' : '' }}>Academic</option>
                <option value="Sports"       {{ request('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                <option value="Arts"         {{ request('category') == 'Arts' ? 'selected' : '' }}>Arts / Culture</option>
                <option value="Technology"   {{ request('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                <option value="Research"     {{ request('category') == 'Research' ? 'selected' : '' }}>Research</option>
                <option value="Other"        {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="filter-select-group">
            <i class="bi bi-funnel-fill filter-select-icon"></i>
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
            <a href="{{ route('student.achievements.index') }}" class="btn-secondary-custom" id="clear-filter-btn">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        @endif
    </form>
</div>

{{-- Results Card --}}
<div class="card-custom">

    @if($achievements->isEmpty())
        {{-- Empty State --}}
        <div class="empty-state-container">
            <div class="empty-state-icon">
                <i class="bi bi-trophy"></i>
            </div>
            <h3 class="empty-state-title">
                @if(request('search') || request('category') || request('status'))
                    No Results Found
                @else
                    No Achievements Yet
                @endif
            </h3>
            <p class="empty-state-desc">
                @if(request('search') || request('category') || request('status'))
                    No achievements match your current filters. Try adjusting your search criteria.
                @else
                    You haven't submitted any achievements yet. Start by submitting your first one!
                @endif
            </p>
            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('student.achievements.index') }}" class="btn-secondary-custom">
                    <i class="bi bi-x-circle"></i> Clear Filters
                </a>
            @else
                <a href="{{ route('student.achievements.create') }}" class="btn-primary-custom">
                    <i class="bi bi-plus-circle-fill"></i> Submit First Achievement
                </a>
            @endif
        </div>

    @else
        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width:35%;">Achievement</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($achievements as $achievement)
                    <tr>
                        <td>
                            <div style="font-weight:600; font-size:0.875rem; color:var(--text-primary);">
                                {{ $achievement->title }}
                            </div>
                        </td>
                        <td>
                            <span class="category-pill">{{ $achievement->category ?? '—' }}</span>
                        </td>
                        <td style="font-size:0.82rem; color:var(--text-secondary);">
                            {{ $achievement->award_level ?? '—' }}
                        </td>
                        <td style="font-size:0.82rem; color:var(--text-secondary); white-space:nowrap;">
                            {{ $achievement->date_achieved->format('M d, Y') }}
                        </td>
                        <td>
                            <span class="status-badge {{ $achievement->status }}">
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
                        <td>
                            @if($achievement->faculty_remarks)
                                <button class="btn-icon-action remarks-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#remarkModal{{ $achievement->id }}"
                                    title="View Remarks">
                                    <i class="bi bi-chat-left-text-fill"></i>
                                </button>

                                {{-- Remark Modal --}}
                                <div class="modal fade" id="remarkModal{{ $achievement->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content modal-custom">
                                            <div class="modal-header modal-header-custom">
                                                <div style="display:flex; align-items:center; gap:0.6rem;">
                                                    <div class="modal-icon-badge">
                                                        <i class="bi bi-chat-left-text-fill"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="modal-title-custom">Faculty Remarks</h5>
                                                        <p class="modal-subtitle-custom">Feedback from your faculty reviewer</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="padding:1.5rem;">
                                                <div class="remark-box">
                                                    {{ $achievement->faculty_remarks }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span style="color:var(--text-muted); font-size:0.8rem;">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div style="display:flex; justify-content:flex-end; gap:0.4rem; flex-wrap:nowrap;">
                                @if($achievement->certificate_file)
                                    <a href="{{ Storage::url($achievement->certificate_file) }}"
                                       target="_blank"
                                       class="btn-icon-action"
                                       title="View Certificate">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </a>
                                @endif

                                @if($achievement->status === 'pending')
                                    <a href="{{ route('student.achievements.edit', $achievement) }}"
                                       class="btn-icon-action edit-btn"
                                       title="Edit Achievement">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('student.achievements.destroy', $achievement) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this achievement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-action delete-btn" title="Delete Achievement">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
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
    .filter-search-group {
        position: relative;
        flex: 2;
        min-width: 200px;
    }
    .filter-search-group .form-control-custom { padding-left: 2.4rem; }
    .filter-search-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.85rem;
        pointer-events: none;
    }
    .filter-select-group {
        position: relative;
        flex: 1;
        min-width: 150px;
    }
    .filter-select-group .form-control-custom { padding-left: 2.4rem; appearance: none; cursor: pointer; }
    .filter-select-icon {
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
    .btn-secondary-custom:hover {
        border-color: var(--accent);
        color: var(--accent);
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
        letter-spacing: 0.03em;
    }

    .btn-icon-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        font-size: 0.8rem;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
    }
    .btn-icon-action:hover { background: var(--accent-dim); border-color: var(--accent-hover); color: var(--accent); }
    .btn-icon-action.edit-btn:hover { background: rgba(37,99,235,0.08); border-color: rgba(37,99,235,0.2); color: #2563eb; }
    .btn-icon-action.delete-btn:hover { background: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.2); color: #dc2626; }
    .btn-icon-action.remarks-btn:hover { background: rgba(217,119,6,0.08); border-color: rgba(217,119,6,0.2); color: #d97706; }

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
    .empty-state-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem;
    }
    .empty-state-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin: 0 0 1.5rem;
        max-width: 380px;
        margin-left: auto;
        margin-right: auto;
    }

    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border);
    }
    .pagination-wrapper .pagination {
        margin: 0;
        gap: 4px;
    }
    .pagination-wrapper .page-link {
        border-radius: 8px !important;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
        border-color: var(--border);
        padding: 5px 11px;
    }
    .pagination-wrapper .page-link:hover { color: var(--accent); background: var(--accent-dim); border-color: var(--accent-hover); }
    .pagination-wrapper .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #fff; }

    .table-footer-info {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Modal Styles */
    .modal-custom {
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        overflow: hidden;
    }
    .modal-header-custom {
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--accent-dim);
        border: 1px solid var(--accent-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1rem;
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
    .remark-box {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    .status-badge { gap: 4px; display: inline-flex; align-items: center; }
</style>
@endpush
@endsection
