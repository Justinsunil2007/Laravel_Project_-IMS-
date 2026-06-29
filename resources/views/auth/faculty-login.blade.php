@extends('layouts.auth')

@section('title', 'Faculty Login')
@section('meta_description', 'Sign in to your faculty account.')

@section('header_link')
    <a href="{{ route('student.login') }}" style="color:var(--text-secondary); text-decoration:none; font-weight:500; font-size:0.8rem;">
        <i class="bi bi-arrow-left me-1"></i>Student Login
    </a>
@endsection

@section('auth_content')
    {{-- Logo & Title --}}
    <div class="auth-logo">
        <div class="auth-logo-icon" style="background: rgba(168,85,247,0.1); border-color: rgba(168,85,247,0.25);">
            <i class="bi bi-briefcase-fill" style="color:#c084fc;"></i>
        </div>
        <h1 class="auth-title">Faculty Login</h1>
        <p class="auth-subtitle">Sign in to manage student achievements</p>
    </div>

    {{-- Card --}}
    <div class="auth-card">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert-auth-error">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Faculty Info Box --}}
        <div style="background:rgba(168,85,247,0.06); border:1px solid rgba(168,85,247,0.2); border-radius:var(--radius); padding:0.85rem 1rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.65rem;">
            <i class="bi bi-info-circle-fill" style="color:#c084fc; flex-shrink:0;"></i>
            <span style="font-size:0.8rem; color:var(--text-secondary);">
                This portal is restricted to authorized faculty members only.
            </span>
        </div>

        <form method="POST" action="{{ route('faculty.login.post') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="faculty_email">Faculty Email</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input
                        type="email"
                        id="faculty_email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="faculty@school.edu"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="faculty_password">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input
                        type="password"
                        id="faculty_password"
                        name="password"
                        class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            {{-- Remember Me --}}
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
                <input type="checkbox" id="faculty_remember" name="remember"
                    style="width:15px; height:15px; accent-color:#c084fc; cursor:pointer;">
                <label for="faculty_remember" style="font-size:0.8rem; color:var(--text-secondary); cursor:pointer; margin:0;">
                    Remember me
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth" id="faculty-login-btn"
                style="background:#c084fc; color:#0a0a0a;">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In as Faculty
            </button>
        </form>
    </div>

    <div class="auth-links">
        Are you a student? <a href="{{ route('student.login') }}">Student Login →</a>
    </div>
@endsection
