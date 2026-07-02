<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('student.profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'student_id' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'department' => ['required', 'string', 'max:100'],
            'year_level' => ['required', 'string', 'max:50'],
            'mobile_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('student.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
