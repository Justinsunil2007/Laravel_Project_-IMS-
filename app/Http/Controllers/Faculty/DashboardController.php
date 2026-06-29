<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the faculty dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_students'      => User::where('role', 'student')->count(),
            'total_achievements'  => Achievement::count(),
            'pending_review'      => Achievement::where('status', 'pending')->count(),
            'approved'            => Achievement::where('status', 'approved')->count(),
        ];

        $recentAchievements = Achievement::with('user')
            ->latest()
            ->take(10)
            ->get();

        $students = User::where('role', 'student')
            ->withCount('achievements')
            ->latest()
            ->take(5)
            ->get();

        return view('faculty.dashboard', compact('user', 'stats', 'recentAchievements', 'students'));
    }
}
