<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/**
 * --------------------------------------------------------------------------
 * Public & Landing Routes
 * --------------------------------------------------------------------------
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * --------------------------------------------------------------------------
 * Core Authentication & Role-Based Redirect
 * --------------------------------------------------------------------------
 * Menghalakan pengguna ke dashboard yang tepat berdasarkan peranan (Role).
 */
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

/**
 * --------------------------------------------------------------------------
 * ADMIN SPACE (Administrator Only)
 * --------------------------------------------------------------------------
 * Dilindungi oleh 'auth' dan middleware custom 'CheckAdmin'.
 */
Route::middleware(['auth', \App\Http\Middleware\CheckAdmin::class])
    ->prefix('admin')
    ->group(function () {

        /**
         * Dashboard & Exam Management
         */
        Route::get('/dashboard', [ExamController::class, 'index'])->name('admin.dashboard');
        Route::get('/peperiksaan', [ExamController::class, 'peperiksaanIndex'])->name('admin.peperiksaan.index');
        Route::post('/exams', [ExamController::class, 'store'])->name('admin.exams.store');
        Route::patch('/exams/{exam}', [ExamController::class, 'update'])->name('admin.exams.update');
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('admin.exams.destroy');
        Route::post('/exams/{exam}/publish', [ExamController::class, 'publishResult'])->name('admin.exams.publish');

        /**
         * Question Management (Bank Soalan)
         * Mengendalikan logic CRUD soalan dan import data.
         */
        Route::get('/exams/{exam}/questions', [QuestionController::class, 'index'])->name('admin.questions.index');
        Route::post('/exams/{exam}/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
        Route::patch('/questions/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');
        
        // Operasi pukal (Bulk Actions)
        Route::delete('/exams/{exam}/questions/destroy-all', [QuestionController::class, 'destroyAll'])->name('admin.questions.destroyAll');
        Route::post('/exams/{exam}/questions/import', [QuestionController::class, 'import'])->name('admin.questions.import');

        /**
         * Question Cloning & Master Bank
         * Membolehkan soalan dikongsi antara peperiksaan (Reusability).
         */
        Route::get('/bank-soalan', [ExamController::class, 'bankIndex'])->name('admin.bank.index');
        Route::get('/exams/{exam}/bank', [QuestionController::class, 'bank'])->name('admin.questions.bank');
        Route::post('/exams/{exam}/copy-from-exam', [QuestionController::class, 'importFromExam'])->name('admin.questions.copy_exam');
        Route::post('/exams/{exam}/copy-selected', [QuestionController::class, 'copySelected'])->name('admin.questions.copy_selected');

        /**
         * Results, Scoring & Exports
         * Jana laporan dalam format Excel dan PDF.
         */
        Route::get('/exams/{exam}/results', [ExamController::class, 'results'])->name('admin.exams.results');
        Route::get('/exams/{id}/export-excel', [ExamController::class, 'exportExcel'])->name('admin.exams.export-excel');
        Route::get('/exams/{id}/export-pdf', [ExamController::class, 'exportPDF'])->name('admin.exams.export-pdf');
        Route::post('/submissions/{submission}/update-score', [ExamController::class, 'updateScore'])->name('admin.submissions.update-score');
});

/**
 * --------------------------------------------------------------------------
 * STUDENT SPACE (Candidate Interface)
 * --------------------------------------------------------------------------
 * Mengendalikan sesi peperiksaan dan paparan markah pelajar.
 */
Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/exams', [StudentExamController::class, 'index'])->name('student.dashboard');
    Route::get('/exams/{exam}', [StudentExamController::class, 'show'])->name('student.exams.show');
    Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit'])->name('student.exams.submit');
    Route::get('/results/{exam}', [StudentExamController::class, 'showResult'])->name('student.results.show');
    Route::get('/exam-success', [StudentExamController::class, 'success'])->name('student.exam.success');

    /**
     * Exam Integrity & Progress (AJAX Based)
     */
    Route::post('/exams/{exam}/auto-save', [StudentExamController::class, 'autoSave'])->name('student.exams.auto-save');
    Route::post('/exams/{exam}/toggle-flag', [StudentExamController::class, 'toggleFlag'])->name('student.exams.toggle-flag');
});

/**
 * --------------------------------------------------------------------------
 * User Profile Management
 * --------------------------------------------------------------------------
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * --------------------------------------------------------------------------
 * Development & Testing Tools
 * --------------------------------------------------------------------------
 */
Route::get('/gen-api-token', function() { 
    return auth()->user()->createToken('api-key')->plainTextToken; 
})->middleware('auth');

require __DIR__.'/auth.php';