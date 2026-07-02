<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // -------------------------------------------------------------------------
    // STUDENT AUTH
    // -------------------------------------------------------------------------

    /**
     * Show the student login form.
     */
    public function showStudentLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.student-login');
    }

    /**
     * Handle a student login request.
     */
    public function studentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'student']), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or you are not registered as a student.',
        ])->onlyInput('email');
    }

    // -------------------------------------------------------------------------
    // FACULTY AUTH
    // -------------------------------------------------------------------------

    /**
     * Show the faculty login form.
     */
    public function showFacultyLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.faculty-login');
    }

    /**
     * Handle a faculty login request.
     */
    public function facultyLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'faculty']), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('faculty.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or you are not registered as faculty.',
        ])->onlyInput('email');
    }

    // -------------------------------------------------------------------------
    // REGISTRATION (Students Only)
    // -------------------------------------------------------------------------

    /**
     * Show the student registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    /**
     * Handle a student registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'student_id'  => ['required', 'string', 'max:50', 'unique:users,student_id'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'department'  => ['required', 'string', 'max:255'],
            'year_level'  => ['required', 'string'],
            'password'    => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'       => $request->name,
            'student_id' => $request->student_id,
            'email'      => $request->email,
            'department' => $request->department,
            'year_level' => $request->year_level,
            'role'       => 'student',
            'password'   => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('student.dashboard')
            ->with('success', 'Registration successful! Welcome to the portal.');
    }

    // -------------------------------------------------------------------------
    // REGISTRATION (Faculty)
    // -------------------------------------------------------------------------

    /**
     * Show the faculty registration form.
     */
    public function showFacultyRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.faculty-register');
    }

    /**
     * Handle a faculty registration request.
     */
    public function facultyRegister(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'faculty_id'  => ['required', 'string', 'max:50', 'unique:users,faculty_id'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'department'  => ['required', 'string', 'max:255'],
            'password'    => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name'       => $request->name,
            'faculty_id' => $request->faculty_id,
            'email'      => $request->email,
            'department' => $request->department,
            'role'       => 'faculty',
            'password'   => Hash::make($request->password),
        ]);

        return redirect()->route('faculty.login')
            ->with('success', 'Registration successful! Please log in.');
    }

    // -------------------------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------------------------

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // -------------------------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------------------------

    private function redirectByRole(User $user)
    {
        return $user->isStudent()
            ? redirect()->route('student.dashboard')
            : redirect()->route('faculty.dashboard');
    }
}
