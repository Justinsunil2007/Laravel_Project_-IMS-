@extends('layouts.app')

@section('title', 'My Profile')
@section('page_title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        {{-- Header --}}
        <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="page-title">My Profile</h1>
                <p class="page-subtitle">View and update your personal information and academic details.</p>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="card-custom" style="margin-bottom:1.25rem;">
            <div class="profile-header-section">
                <div class="profile-avatar-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ auth()->user()->name }}</h2>
                    <p class="profile-email">{{ auth()->user()->email }}</p>
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.5rem;">
                        <span class="role-badge student">Student</span>
                        @if(auth()->user()->department)
                            <span class="info-chip">
                                <i class="bi bi-building"></i>
                                {{ auth()->user()->department }}
                            </span>
                        @endif
                        @if(auth()->user()->year_level)
                            <span class="info-chip">
                                <i class="bi bi-layers"></i>
                                {{ auth()->user()->year_level }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="card-custom">
            <form method="POST" action="{{ route('student.profile.update') }}" novalidate>
                @csrf
                @method('PUT')

                {{-- Personal Info Section --}}
                <div class="form-section">
                    <div class="form-section-icon" style="background: var(--accent-dim); border-color: var(--accent-hover);">
                        <i class="bi bi-person-fill" style="color:var(--accent);"></i>
                    </div>
                    <div>
                        <h3 class="form-section-title">Personal Information</h3>
                        <p class="form-section-desc">Update your name, email, and contact details.</p>
                    </div>
                </div>
                <div style="border-top:1px solid var(--border); padding-top:1.5rem; margin-bottom:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label for="name" class="form-label">Full Name <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-person-fill input-icon"></i>
                                <input id="name" type="text"
                                    class="form-control-custom with-icon {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    name="name" value="{{ old('name', auth()->user()->name) }}"
                                    required autocomplete="name">
                            </div>
                            @error('name') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email" class="form-label">Email Address <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-envelope-fill input-icon"></i>
                                <input id="email" type="email"
                                    class="form-control-custom with-icon {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    name="email" value="{{ old('email', auth()->user()->email) }}"
                                    required autocomplete="email">
                            </div>
                            @error('email') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="student_id" class="form-label">Student ID / Roll No. <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-card-text input-icon"></i>
                                <input id="student_id" type="text"
                                    class="form-control-custom with-icon {{ $errors->has('student_id') ? 'is-invalid' : '' }}"
                                    name="student_id" value="{{ old('student_id', auth()->user()->student_id) }}"
                                    required>
                            </div>
                            @error('student_id') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="mobile_number" class="form-label">Mobile Number
                                <span style="font-weight:400; color:var(--text-muted);">(Optional)</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="bi bi-telephone-fill input-icon"></i>
                                <input id="mobile_number" type="text"
                                    class="form-control-custom with-icon {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}"
                                    name="mobile_number" value="{{ old('mobile_number', auth()->user()->mobile_number) }}"
                                    placeholder="e.g. 09XXXXXXXXX">
                            </div>
                            @error('mobile_number') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Academic Info Section --}}
                <div class="form-section">
                    <div class="form-section-icon" style="background: rgba(37,99,235,0.1); border-color: rgba(37,99,235,0.2);">
                        <i class="bi bi-mortarboard-fill" style="color:#2563eb;"></i>
                    </div>
                    <div>
                        <h3 class="form-section-title">Academic Details</h3>
                        <p class="form-section-desc">Select your department and current year level.</p>
                    </div>
                </div>
                <div style="border-top:1px solid var(--border); padding-top:1.5rem; margin-bottom:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label for="department" class="form-label">Department <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-building input-icon"></i>
                                <select id="department"
                                    class="form-control-custom with-icon {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                    name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Computer Science"       {{ old('department', auth()->user()->department) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="Information Technology" {{ old('department', auth()->user()->department) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                    <option value="Electronics"            {{ old('department', auth()->user()->department) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="Mechanical"             {{ old('department', auth()->user()->department) == 'Mechanical' ? 'selected' : '' }}>Mechanical</option>
                                </select>
                            </div>
                            @error('department') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="year_level" class="form-label">Year Level <span class="required-star">*</span></label>
                            <div class="input-wrapper">
                                <i class="bi bi-layers input-icon"></i>
                                <select id="year_level"
                                    class="form-control-custom with-icon {{ $errors->has('year_level') ? 'is-invalid' : '' }}"
                                    name="year_level" required>
                                    <option value="">Select Year</option>
                                    <option value="1st Year" {{ old('year_level', auth()->user()->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level', auth()->user()->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level', auth()->user()->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level', auth()->user()->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                            </div>
                            @error('year_level') <div class="invalid-feedback-custom">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="form-action-row">
                    <button type="submit" class="btn-primary-custom" id="save-profile-btn">
                        <i class="bi bi-check2-circle"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@push('styles')
<style>
    .required-star { color: var(--danger); }

    .profile-header-section {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }
    .profile-avatar-lg {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--accent-dim);
        border: 2px solid var(--accent-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.4rem;
        color: var(--accent);
        flex-shrink: 0;
    }
    .profile-name { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.2rem; }
    .profile-email { font-size: 0.875rem; color: var(--text-muted); margin: 0; }
    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-secondary);
        padding: 2px 9px;
    }

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
        padding: 0.65rem 1.5rem;
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
</style>
@endpush
@endsection
