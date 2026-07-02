<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Achievement::with('user')->orderBy('created_at', 'desc');

        // Search by student name or roll number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $achievements = $query->paginate(15)->withQueryString();

        return view('faculty.achievements.index', compact('achievements'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'faculty_remarks' => 'nullable|string',
        ]);

        $achievement->update($validated);

        return back()->with('success', 'Achievement status updated successfully.');
    }
}
