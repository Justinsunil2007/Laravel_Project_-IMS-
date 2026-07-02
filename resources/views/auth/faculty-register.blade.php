@extends('layouts.auth')

@section('title', 'Faculty Registration')
@section('meta_description', 'Create a new faculty account.')

@section('header_link')
    Already have an account? <a href="{{ route('faculty.login') }}" style="color:var(--text-secondary); text-decoration:none; font-weight:600;">Sign In</a>
@endsection

@section('auth_content')
    {{-- Logo & Title --}}
    <div class="auth-logo">
        <div class="auth-logo-icon">
            <i class="bi bi-person-badge-fill" style="color:var(--text-secondary);"></i>
        </div>
        <h1 class="auth-title">Faculty Registration</h1>
        <p class="auth-subtitle">Create your faculty account</p>
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

        <form method="POST" action="{{ route('faculty.register.post') }}" novalidate>
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
                        class="form-control-custom {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        placeholder="Dr. Juan dela Cruz"
                        required
                        autocomplete="name"
                        autofocus
                    >
                </div>
                @error('name')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

            {{-- Faculty ID --}}
            <div class="form-group">
                <label class="form-label" for="reg_faculty_id">Employee / Faculty ID</label>
                <div class="input-wrapper">
                    <i class="bi bi-card-heading input-icon"></i>
                    <input
                        type="text"
                        id="reg_faculty_id"
                        name="faculty_id"
                        value="{{ old('faculty_id') }}"
                        class="form-control-custom {{ $errors->has('faculty_id') ? 'is-invalid' : '' }}"
                        placeholder="e.g. EMP-2024-001"
                        required
                    >
                </div>
                @error('faculty_id')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
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
                        class="form-control-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="faculty@school.edu"
                        required
                        autocomplete="email"
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

            {{-- Department --}}
            <div class="form-group">
                <label class="form-label" for="reg_department">Department</label>
                <div class="input-wrapper">
                    <i class="bi bi-building input-icon"></i>
                    <input
                        type="text"
                        id="reg_department"
                        name="department"
                        value="{{ old('department') }}"
                        class="form-control-custom {{ $errors->has('department') ? 'is-invalid' : '' }}"
                        placeholder="e.g. Computer Science"
                        required
                    >
                </div>
                @error('department')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
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
                        class="form-control-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
                    >
                </div>
                @error('password')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
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
                        class="form-control-custom"
                        placeholder="Repeat your password"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth" id="faculty-register-submit-btn">
                <i class="bi bi-person-check-fill"></i>
                Create Faculty Account
            </button>
        </form>
    </div>

    <div class="auth-links">
        Are you a student? <a href="{{ route('student.register') }}">Student Registration →</a>
    </div>
@endsection
