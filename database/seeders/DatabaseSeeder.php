<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Faculty User
        $faculty = User::create([
            'name' => 'Dr. Sarah Jenkins',
            'email' => 'faculty@samp.edu',
            'password' => \Illuminate\Support\Facades\Hash::make('Password123'),
            'role' => 'faculty',
            'faculty_id' => 'FAC-2026-001',
        ]);

        // 2. Create Student User
        $student = User::create([
            'name' => 'John Doe',
            'email' => 'student@samp.edu',
            'password' => \Illuminate\Support\Facades\Hash::make('Password123'),
            'role' => 'student',
            'student_id' => 'STU-2026-001',
            'department' => 'Computer Science',
            'year_level' => '3rd Year',
        ]);

        // 3. Create Sample Achievements for the Student
        \App\Models\Achievement::create([
            'user_id' => $student->id,
            'title' => 'First Place in Hackathon 2026',
            'description' => 'Won first place in the annual university-wide hackathon with an AI-powered smart campus solution.',
            'category' => 'Technology',
            'award_level' => 'Regional',
            'date_achieved' => '2026-03-15',
            'status' => 'approved',
        ]);

        \App\Models\Achievement::create([
            'user_id' => $student->id,
            'title' => 'Dean\'s List - Fall Semester',
            'description' => 'Achieved a GPA of 3.92, earning a place on the Dean\'s List for academic excellence.',
            'category' => 'Academic',
            'award_level' => 'School',
            'date_achieved' => '2026-01-20',
            'status' => 'approved',
        ]);

        \App\Models\Achievement::create([
            'user_id' => $student->id,
            'title' => 'Outstanding Research Paper Award',
            'description' => 'Received recognition for the paper "Optimization of Neural Networks in IoT Systems" at the national conference.',
            'category' => 'Research',
            'award_level' => 'National',
            'date_achieved' => '2026-05-10',
            'status' => 'pending',
        ]);
    }
}
