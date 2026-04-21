<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Cipta Admin
        User::create([
            'name' => 'Admin Lecturer',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Cipta Student
        User::create([
            'name' => 'Student A',
            'email' => 'student@test.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);
    }
}
