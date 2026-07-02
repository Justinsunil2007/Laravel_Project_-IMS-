<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Notifications\StudentActivityNotification;
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

        $status = $validated['status'];
        $title = 'Achievement Under Review';
        $message = "Your achievement '{$achievement->title}' is currently under review.";
        $notificationStatus = 'pending';

        if ($status === 'approved') {
            $title = 'Achievement Approved';
            $message = "Your achievement '{$achievement->title}' has been approved by the Faculty.";
            $notificationStatus = 'approved';
        } elseif ($status === 'rejected') {
            $title = 'Achievement Rejected';
            $message = "Your achievement '{$achievement->title}' has been rejected.";
            $notificationStatus = 'rejected';
        }

        if (!empty($validated['faculty_remarks'])) {
            $message .= ' Faculty remarks: ' . $validated['faculty_remarks'];
        }

        $achievement->user->notify(new StudentActivityNotification(
            title: $title,
            message: $message,
            status: $notificationStatus,
            achievementTitle: $achievement->title,
            remarks: $validated['faculty_remarks'] ?? null,
        ));

        return back()->with('success', 'Achievement status updated successfully.');
    }
}
