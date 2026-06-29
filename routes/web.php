<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboardController;

// ─────────────────────────────────────────────────────────────────────────────
// HOME — Redirect to student login by default
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('student.login');
})->name('home');

// ─────────────────────────────────────────────────────────────────────────────
// GUEST ROUTES (Unauthenticated only)
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {

    // Fallback login route for auth middleware redirects
    Route::get('/login', [AuthController::class, 'showStudentLogin'])->name('login');

    // Student Auth
    Route::get('/student/login', [AuthController::class, 'showStudentLogin'])->name('student.login');
    Route::post('/student/login', [AuthController::class, 'studentLogin'])->name('student.login.post');

    // Student Registration
    Route::get('/student/register', [AuthController::class, 'showRegister'])->name('student.register');
    Route::post('/student/register', [AuthController::class, 'register'])->name('student.register.post');

    // Faculty Auth
    Route::get('/faculty/login', [AuthController::class, 'showFacultyLogin'])->name('faculty.login');
    Route::post('/faculty/login', [AuthController::class, 'facultyLogin'])->name('faculty.login.post');
});

// ─────────────────────────────────────────────────────────────────────────────
// AUTHENTICATED ROUTES — Logout (any authenticated user)
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ─────────────────────────────────────────────────────────────────────────────
// STUDENT ROUTES — Requires auth + student role
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

// ─────────────────────────────────────────────────────────────────────────────
// FACULTY ROUTES — Requires auth + faculty role
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyDashboardController::class, 'index'])->name('dashboard');
});
