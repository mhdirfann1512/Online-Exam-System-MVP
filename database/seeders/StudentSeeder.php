<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 student dummy
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\User::create([
                'name' => "Student Test $i",
                'email' => "student$i@test.com",
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'student', // Pastikan column role kau memang guna nama ni
            ]);
        }
    }
}
