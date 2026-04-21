<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

// Route utama selepas login
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// Group untuk ADMIN
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ExamController::class, 'index'])->name('admin.dashboard');
    Route::post('/exams', [ExamController::class, 'store'])->name('admin.exams.store');
});

// Group untuk STUDENT
Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/exams', function () {
        return view('student.exams');
    })->name('student.dashboard');
    
    // Nanti kita tambah route Take Exam kat sini
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';