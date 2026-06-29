<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Checks if the authenticated user has the required role.
     * Redirects unauthorized users to the appropriate login page.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('student.login')
                ->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Redirect to the correct dashboard based on actual role
            if ($user->isFaculty()) {
                return redirect()->route('faculty.dashboard')
                    ->with('error', 'You do not have access to that page.');
            }

            return redirect()->route('student.dashboard')
                ->with('error', 'You do not have access to that page.');
        }

        return $next($request);
    }
}
