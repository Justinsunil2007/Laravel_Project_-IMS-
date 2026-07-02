<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Notifications\StudentActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->achievements()->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $achievements = $query->paginate(10)->withQueryString();
        return view('student.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('student.achievements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'award_level' => 'required|string|max:100',
            'date_achieved' => 'required|date',
            'description' => 'nullable|string',
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store('certificates', 'public');
            $validated['certificate_file'] = $path;
        }

        $validated['status'] = 'pending';
        $achievement = auth()->user()->achievements()->create($validated);

        auth()->user()->notify(new StudentActivityNotification(
            title: 'Achievement Submitted',
            message: "You successfully submitted '{$achievement->title}' for review.",
            status: 'submitted',
            achievementTitle: $achievement->title,
        ));

        return redirect()->route('student.achievements.index')->with('success', 'Achievement submitted successfully!');
    }

    public function edit(Achievement $achievement)
    {
        if ($achievement->user_id !== auth()->id() || $achievement->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        return view('student.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        if ($achievement->user_id !== auth()->id() || $achievement->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'award_level' => 'required|string|max:100',
            'date_achieved' => 'required|date',
            'description' => 'nullable|string',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('certificate_file')) {
            if ($achievement->certificate_file) {
                Storage::disk('public')->delete($achievement->certificate_file);
            }
            $path = $request->file('certificate_file')->store('certificates', 'public');
            $validated['certificate_file'] = $path;
        }

        $achievement->update($validated);

        auth()->user()->notify(new StudentActivityNotification(
            title: 'Achievement Updated',
            message: "You updated '{$achievement->title}' information.",
            status: 'updated',
            achievementTitle: $achievement->title,
        ));

        return redirect()->route('student.achievements.index')->with('success', 'Achievement updated successfully!');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->user_id !== auth()->id() || $achievement->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        if ($achievement->certificate_file) {
            Storage::disk('public')->delete($achievement->certificate_file);
        }

        $achievementTitle = $achievement->title;
        $achievement->delete();

        auth()->user()->notify(new StudentActivityNotification(
            title: 'Achievement Deleted',
            message: "Your achievement '{$achievementTitle}' was deleted.",
            status: 'deleted',
            achievementTitle: $achievementTitle,
        ));

        return redirect()->route('student.achievements.index')->with('success', 'Achievement deleted successfully!');
    }
}
