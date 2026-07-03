<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Student Account
        User::updateOrCreate(
            ['email' => 'student@samp.edu'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('Password123'),
                'role' => 'student',
                'student_id' => 'STU001',
                'department' => 'Computer Engineering',
                'year_level' => 'Third Year',
                'faculty_id' => null,
            ]
        );

        // Faculty Account
        User::updateOrCreate(
            ['email' => 'faculty@samp.edu'],
            [
                'name' => 'Demo Faculty',
                'password' => Hash::make('Password123'),
                'role' => 'faculty',
                'faculty_id' => 'FAC001',
                'department' => 'Computer Engineering',
                'student_id' => null,
                'year_level' => null,
            ]
        );
    }
}