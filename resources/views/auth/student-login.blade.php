@extends('layouts.auth')

@section('title', 'Student Login')
@section('meta_description', 'Sign in to your student account.')

@section('header_link')
    <a href="{{ route('faculty.login') }}" style="color:var(--text-secondary); text-decoration:none; font-weight:500; font-size:0.8rem;">
        Faculty Login <i class="bi bi-arrow-right ms-1"></i>
    </a>
@endsection

@section('auth_content')
    {{-- Logo & Title --}}
    <div class="auth-logo">
        <div class="auth-logo-icon">
            <i class="bi bi-person-circle" style="color:var(--text-secondary);"></i>
        </div>
        <h1 class="auth-title">Student Login</h1>
        <p class="auth-subtitle">Sign in to track your achievements</p>
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

        <form method="POST" action="{{ route('student.login.post') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="student_email">Email Address</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input
                        type="email"
                        id="student_email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="yourname@school.edu"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="student_password">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input
                        type="password"
                        id="student_password"
                        name="password"
                        class="form-control-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            {{-- Remember Me --}}
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
                <input type="checkbox" id="student_remember" name="remember"
                    style="width:15px; height:15px; accent-color:#fff; cursor:pointer;">
                <label for="student_remember" style="font-size:0.8rem; color:var(--text-secondary); cursor:pointer; margin:0;">
                    Remember me
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth" id="student-login-btn">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In as Student
            </button>
        </form>

        <div class="auth-divider">
            <span>Don't have an account?</span>
        </div>

        <a href="{{ route('student.register') }}" style="display:block; width:100%; background:transparent; border:1px solid var(--border); color:var(--text-secondary); border-radius:var(--radius); padding:0.7rem 1rem; font-size:0.875rem; font-weight:600; font-family:'Inter',sans-serif; text-decoration:none; text-align:center; transition:var(--transition);"
            onmouseover="this.style.borderColor='var(--border-light)'; this.style.color='var(--text-primary)';"
            onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)';">
            <i class="bi bi-person-plus me-1"></i>
            Create Student Account
        </a>
    </div>

    <div class="auth-links">
        Are you faculty? <a href="{{ route('faculty.login') }}">Faculty Login →</a>
    </div>
@endsection
