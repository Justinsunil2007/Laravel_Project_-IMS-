<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $achievements = Achievement::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total'    => Achievement::where('user_id', $user->id)->count(),
            'approved' => Achievement::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending'  => Achievement::where('user_id', $user->id)->where('status', 'pending')->count(),
            'rejected' => Achievement::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('student.dashboard', compact('user', 'achievements', 'stats'));
    }
}
