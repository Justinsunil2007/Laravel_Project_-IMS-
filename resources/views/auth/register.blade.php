@extends('layouts.auth')

@section('title', 'Student Registration')
@section('meta_description', 'Create a new student account.')

@section('header_link')
    Already have an account? <a href="{{ route('student.login') }}" style="color:var(--text-secondary); text-decoration:none; font-weight:600;">Sign In</a>
@endsection

@section('auth_content')
    {{-- Logo & Title --}}
    <div class="auth-logo">
        <div class="auth-logo-icon">
            <i class="bi bi-person-plus-fill" style="color:var(--text-secondary);"></i>
        </div>
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Register as a student to get started</p>
    </div>

    {{-- Card --}}
    <div class="auth-card">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert-auth-error">
                <i class="bi bi-exclamation-triangle-fill mt-1" style="flex-shrink:0;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('student.register.post') }}" novalidate>
            @csrf

            {{-- Full Name --}}
            <div class="form-group">
                <label class="form-label" for="reg_name">Full Name</label>
                <div class="input-wrapper">
                    <i class="bi bi-person-fill input-icon"></i>
                    <input
                        type="text"
                        id="reg_name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        placeholder="Juan dela Cruz"
                        required
                        autocomplete="name"
                        autofocus
                    >
                </div>
                @error('name')
                    <div class="invalid-feedback-dark">{{ $message }}</div>
                @enderror
            </div>

            {{-- Student ID --}}
            <div class="form-group">
                <label class="form-label" for="reg_student_id">Student ID</label>
                <div class="input-wrapper">
                    <i class="bi bi-card-text input-icon"></i>
                    <input
                        type="text"
                        id="reg_student_id"
                        name="student_id"
                        value="{{ old('student_id') }}"
                        class="form-control-dark {{ $errors->has('student_id') ? 'is-invalid' : '' }}"
                        placeholder="e.g. 2024-00001"
                        required
                    >
                </div>
                @error('student_id')
                    <div class="invalid-feedback-dark">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="reg_email">Email Address</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input
                        type="email"
                        id="reg_email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="yourname@school.edu"
                        required
                        autocomplete="email"
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback-dark">{{ $message }}</div>
                @enderror
            </div>

            {{-- Department & Year Level (2 cols) --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div class="form-group">
                    <label class="form-label" for="reg_department">Department</label>
                    <div class="input-wrapper">
                        <i class="bi bi-building input-icon"></i>
                        <input
                            type="text"
                            id="reg_department"
                            name="department"
                            value="{{ old('department') }}"
                            class="form-control-dark {{ $errors->has('department') ? 'is-invalid' : '' }}"
                            placeholder="e.g. BSIT"
                            required
                        >
                    </div>
                    @error('department')
                        <div class="invalid-feedback-dark">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="reg_year_level">Year Level</label>
                    <div class="input-wrapper">
                        <i class="bi bi-layers input-icon"></i>
                        <select
                            id="reg_year_level"
                            name="year_level"
                            class="form-control-dark {{ $errors->has('year_level') ? 'is-invalid' : '' }}"
                            style="cursor:pointer; padding-left:2.5rem;"
                            required
                        >
                            <option value="" disabled {{ old('year_level') ? '' : 'selected' }}>Select</option>
                            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                    @error('year_level')
                        <div class="invalid-feedback-dark">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="reg_password">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input
                        type="password"
                        id="reg_password"
                        name="password"
                        class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
                    >
                </div>
                @error('password')
                    <div class="invalid-feedback-dark">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label class="form-label" for="reg_password_confirmation">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input
                        type="password"
                        id="reg_password_confirmation"
                        name="password_confirmation"
                        class="form-control-dark"
                        placeholder="Repeat your password"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth" id="register-submit-btn">
                <i class="bi bi-person-check-fill"></i>
                Create Student Account
            </button>
        </form>
    </div>

    <div class="auth-links">
        Already registered? <a href="{{ route('student.login') }}">Sign In →</a>
    </div>
@endsection
