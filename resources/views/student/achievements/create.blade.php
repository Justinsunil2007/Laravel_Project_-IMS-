@extends('layouts.app')

@section('title', 'Submit Achievement')
@section('page_title', 'Submit Achievement')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        {{-- Header --}}
        <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="page-title">Submit Achievement</h1>
                <p class="page-subtitle">Fill in the details and upload your certificate for faculty review.</p>
            </div>
            <a href="{{ route('student.achievements.index') }}" class="btn-secondary-custom" id="back-btn">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        {{-- Form Card --}}
        <div class="card-custom">
            <form method="POST" action="{{ route('student.achievements.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Achievement Title --}}
                <div class="form-section">
                    <div class="form-section-icon" style="background: var(--accent-dim); border-color: var(--accent-hover);">
                        <i class="bi bi-trophy-fill" style="color:var(--accent);"></i>
                    </div>
                    <div class="form-section-content">
                        <h3 class="form-section-title">Achievement Information</h3>
                        <p class="form-section-desc">Enter the title and select the category and competition level.</p>
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
                                    name="title" value="{{ old('title') }}"
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
                                    <option value="Academic"    {{ old('category') == 'Academic' ? 'selected' : '' }}>📚 Academic</option>
                                    <option value="Sports"      {{ old('category') == 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                                    <option value="Arts"        {{ old('category') == 'Arts' ? 'selected' : '' }}>🎨 Arts / Culture</option>
                                    <option value="Technology"  {{ old('category') == 'Technology' ? 'selected' : '' }}>💻 Technology / Hackathon</option>
                                    <option value="Research"    {{ old('category') == 'Research' ? 'selected' : '' }}>🔬 Research / Publication</option>
                                    <option value="Other"       {{ old('category') == 'Other' ? 'selected' : '' }}>📌 Other</option>
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
                                    <option value="School"        {{ old('award_level') == 'School' ? 'selected' : '' }}>🏫 School / College</option>
                                    <option value="Regional"      {{ old('award_level') == 'Regional' ? 'selected' : '' }}>🏙️ Regional</option>
                                    <option value="National"      {{ old('award_level') == 'National' ? 'selected' : '' }}>🇵🇭 National</option>
                                    <option value="International" {{ old('award_level') == 'International' ? 'selected' : '' }}>🌏 International</option>
                                </select>
                            </div>
                            @error('award_level') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Date & File --}}
                <div class="form-section">
                    <div class="form-section-icon" style="background: rgba(22,163,74,0.1); border-color: rgba(22,163,74,0.2);">
                        <i class="bi bi-calendar-event-fill" style="color:#16a34a;"></i>
                    </div>
                    <div class="form-section-content">
                        <h3 class="form-section-title">Date & Certificate</h3>
                        <p class="form-section-desc">When was this achievement earned? Upload your supporting certificate.</p>
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
                                    name="date_achieved" value="{{ old('date_achieved') }}"
                                    required max="{{ date('Y-m-d') }}">
                            </div>
                            @error('date_achieved') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="certificate_file" class="form-label">Certificate File <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-file-earmark-arrow-up-fill input-icon"></i>
                                <input id="certificate_file" type="file"
                                    class="form-control-custom with-icon {{ $errors->has('certificate_file') ? 'is-invalid' : '' }}"
                                    name="certificate_file"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    required style="padding-top:0.45rem;">
                            </div>
                            <p style="font-size:0.75rem; color:var(--text-muted); margin:0.3rem 0 0;">
                                PDF, JPG, or PNG — maximum 2MB
                            </p>
                            @error('certificate_file') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 form-group">
                            <label for="description" class="form-label">Description
                                <span style="font-weight:400; color:var(--text-muted);">(Optional)</span>
                            </label>
                            <textarea id="description"
                                class="form-control-custom {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                name="description" rows="4"
                                placeholder="Briefly describe your achievement, the event, and any notable details...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="form-action-row">
                    <a href="{{ route('student.achievements.index') }}" class="btn-secondary-custom">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary-custom" id="submit-achievement-btn">
                        <i class="bi bi-send-fill"></i> Submit for Review
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@push('styles')
<style>
    .required-star { color: var(--danger); }

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

    .form-control-custom textarea { resize: vertical; }
</style>
@endpush
@endsection
