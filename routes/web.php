<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\AchievementController;
use App\Http\Controllers\Faculty\ReviewController;
// ─────────────────────────────────────────────────────────────────────────────
// HOME — Landing page for the portal
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
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

    // Faculty Registration
    Route::get('/faculty/register', [AuthController::class, 'showFacultyRegister'])->name('faculty.register');
    Route::post('/faculty/register', [AuthController::class, 'facultyRegister'])->name('faculty.register.post');
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
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Achievements
    Route::resource('achievements', AchievementController::class);
});

// ─────────────────────────────────────────────────────────────────────────────
// FACULTY ROUTES — Requires auth + faculty role
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyDashboardController::class, 'index'])->name('dashboard');

    // Reviews
    Route::get('/achievements', [ReviewController::class, 'index'])->name('achievements.index');
    Route::put('/achievements/{achievement}/review', [ReviewController::class, 'update'])->name('achievements.review');
});
