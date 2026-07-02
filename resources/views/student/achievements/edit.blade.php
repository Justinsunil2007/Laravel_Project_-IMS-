@extends('layouts.app')

@section('title', 'Edit Achievement')
@section('page_title', 'Edit Achievement')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        {{-- Header --}}
        <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="page-title">Edit Achievement</h1>
                <p class="page-subtitle">Update your submission details before faculty review.</p>
            </div>
            <a href="{{ route('student.achievements.index') }}" class="btn-secondary-custom" id="back-btn">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        {{-- Status Notice --}}
        @if($achievement->status === 'pending')
            <div class="info-banner info-banner-warning mb-4">
                <i class="bi bi-hourglass-split"></i>
                <div>
                    <strong>Pending Review</strong> — You can still edit this achievement until a faculty member reviews it.
                </div>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="card-custom">
            <form method="POST" action="{{ route('student.achievements.update', $achievement) }}" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                {{-- Achievement Section --}}
                <div class="form-section">
                    <div class="form-section-icon" style="background: var(--accent-dim); border-color: var(--accent-hover);">
                        <i class="bi bi-trophy-fill" style="color:var(--accent);"></i>
                    </div>
                    <div class="form-section-content">
                        <h3 class="form-section-title">Achievement Information</h3>
                        <p class="form-section-desc">Update the title, category, and level for this achievement.</p>
                    </div>
                </div>
                <div style="border-top:1px solid var(--border); padding-top:1.5rem; margin-bottom:1.5rem;">
                    <div class="row g-3">
                        <div class="col-12 form-group">
                            <label for="title" class="form-label">Achievement Title <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-award-fill input-icon"></i>
                                <input id="title" type="text"
                                    class="form-control-custom with-icon {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    name="title" value="{{ old('title', $achievement->title) }}"
                                    placeholder="e.g. First Place in Regional Science Fair"
                                    required autofocus>
                            </div>
                            @error('title') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="category" class="form-label">Category <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-tags-fill input-icon"></i>
                                <select id="category" class="form-control-custom with-icon {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category" required>
                                    <option value="">Select a category...</option>
                                    <option value="Academic"    {{ old('category', $achievement->category) == 'Academic' ? 'selected' : '' }}>📚 Academic</option>
                                    <option value="Sports"      {{ old('category', $achievement->category) == 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                                    <option value="Arts"        {{ old('category', $achievement->category) == 'Arts' ? 'selected' : '' }}>🎨 Arts / Culture</option>
                                    <option value="Technology"  {{ old('category', $achievement->category) == 'Technology' ? 'selected' : '' }}>💻 Technology / Hackathon</option>
                                    <option value="Research"    {{ old('category', $achievement->category) == 'Research' ? 'selected' : '' }}>🔬 Research / Publication</option>
                                    <option value="Other"       {{ old('category', $achievement->category) == 'Other' ? 'selected' : '' }}>📌 Other</option>
                                </select>
                            </div>
                            @error('category') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="award_level" class="form-label">Award Level <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-bar-chart-fill input-icon"></i>
                                <select id="award_level" class="form-control-custom with-icon {{ $errors->has('award_level') ? 'is-invalid' : '' }}" name="award_level" required>
                                    <option value="">Select level...</option>
                                    <option value="School"        {{ old('award_level', $achievement->award_level) == 'School' ? 'selected' : '' }}>🏫 School / College</option>
                                    <option value="Regional"      {{ old('award_level', $achievement->award_level) == 'Regional' ? 'selected' : '' }}>🏙️ Regional</option>
                                    <option value="National"      {{ old('award_level', $achievement->award_level) == 'National' ? 'selected' : '' }}>🇵🇭 National</option>
                                    <option value="International" {{ old('award_level', $achievement->award_level) == 'International' ? 'selected' : '' }}>🌏 International</option>
                                </select>
                            </div>
                            @error('award_level') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Date & File Section --}}
                <div class="form-section">
                    <div class="form-section-icon" style="background: rgba(22,163,74,0.1); border-color: rgba(22,163,74,0.2);">
                        <i class="bi bi-calendar-event-fill" style="color:#16a34a;"></i>
                    </div>
                    <div class="form-section-content">
                        <h3 class="form-section-title">Date & Certificate</h3>
                        <p class="form-section-desc">Update the date or upload a new certificate file if needed.</p>
                    </div>
                </div>
                <div style="border-top:1px solid var(--border); padding-top:1.5rem; margin-bottom:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label for="date_achieved" class="form-label">Date Achieved <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-calendar-event-fill input-icon"></i>
                                <input id="date_achieved" type="date"
                                    class="form-control-custom with-icon {{ $errors->has('date_achieved') ? 'is-invalid' : '' }}"
                                    name="date_achieved"
                                    value="{{ old('date_achieved', $achievement->date_achieved->format('Y-m-d')) }}"
                                    required max="{{ date('Y-m-d') }}">
                            </div>
                            @error('date_achieved') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="certificate_file" class="form-label">Replace Certificate
                                <span style="font-weight:400; color:var(--text-muted);">(Optional)</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="bi bi-file-earmark-arrow-up-fill input-icon"></i>
                                <input id="certificate_file" type="file"
                                    class="form-control-custom with-icon {{ $errors->has('certificate_file') ? 'is-invalid' : '' }}"
                                    name="certificate_file"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    style="padding-top:0.45rem;">
                            </div>
                            @if($achievement->certificate_file)
                                <p style="font-size:0.75rem; color:var(--text-muted); margin:0.35rem 0 0;">
                                    Current file:
                                    <a href="{{ Storage::url($achievement->certificate_file) }}" target="_blank"
                                       style="color:var(--accent); font-weight:600;">
                                        View Certificate <i class="bi bi-box-arrow-up-right" style="font-size:0.7rem;"></i>
                                    </a>
                                </p>
                            @endif
                            @error('certificate_file') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 form-group">
                            <label for="description" class="form-label">Description
                                <span style="font-weight:400; color:var(--text-muted);">(Optional)</span>
                            </label>
                            <textarea id="description"
                                class="form-control-custom {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                name="description" rows="4"
                                placeholder="Briefly describe your achievement, the event, and any notable details...">{{ old('description', $achievement->description) }}</textarea>
                            @error('description') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="form-action-row">
                    <a href="{{ route('student.achievements.index') }}" class="btn-secondary-custom">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary-custom" id="update-achievement-btn">
                        <i class="bi bi-check2-circle"></i> Update Achievement
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@push('styles')
<style>
    .required-star { color: var(--danger); }

    .info-banner {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.9rem 1.1rem;
        border-radius: var(--radius);
        font-size: 0.875rem;
        border: 1px solid;
    }
    .info-banner-warning {
        background: rgba(217,119,6,0.08);
        border-color: rgba(217,119,6,0.25);
        color: #92400e;
    }
    .info-banner i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

    .form-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .form-section-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 1px solid;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .form-section-title { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.1rem; }
    .form-section-desc { font-size: 0.8rem; color: var(--text-muted); margin: 0; }
    .form-section-content { min-width: 0; }

    .form-action-row {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border);
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: var(--radius);
        padding: 0.65rem 1.4rem;
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
        padding: 0.65rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }
    .btn-secondary-custom:hover { border-color: var(--accent); color: var(--accent); }
</style>
@endpush
@endsection
