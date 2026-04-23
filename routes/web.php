<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
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
Route::middleware(['auth', \App\Http\Middleware\CheckAdmin::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ExamController::class, 'index'])->name('admin.dashboard');
    Route::post('/exams', [ExamController::class, 'store'])->name('admin.exams.store');

    // Pengurusan Soalan (Dalam folder admin/)
    Route::get('/exams/{exam}/questions', [QuestionController::class, 'index'])->name('admin.questions.index');
    Route::post('/exams/{exam}/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::post('/exams/{exam}/questions/import', [QuestionController::class, 'import'])->name('admin.questions.import');

    // Bank Soalan & Copy Logic
    // Kita panggil Bank Soalan dari dalam context Exam tertentu
    Route::get('/exams/{exam}/bank', [QuestionController::class, 'bank'])->name('admin.questions.bank');
    Route::post('/exams/{exam}/copy-from-exam', [QuestionController::class, 'importFromExam'])->name('admin.questions.copy_exam');
    Route::post('/exams/{exam}/copy-selected', [QuestionController::class, 'copySelected'])->name('admin.questions.copy_selected');

    // Ini route baru untuk page Bank Soalan Utama
    Route::get('/bank-soalan', [ExamController::class, 'bankIndex'])->name('admin.bank.index');

    // Results & Export
    Route::get('/exams/{exam}/results', [ExamController::class, 'results'])->name('admin.exams.results');
    Route::get('/exams/{id}/export-excel', [ExamController::class, 'exportExcel'])->name('admin.exams.export-excel');
    Route::get('/exams/{id}/export-pdf', [ExamController::class, 'exportPDF'])->name('admin.exams.export-pdf');
    Route::post('/admin/submissions/{submission}/update-score', [ExamController::class, 'updateScore'])->name('admin.submissions.update-score');

    // Publish
    Route::post('/admin/exams/{exam}/publish', [App\Http\Controllers\Admin\ExamController::class, 'publishResult'])->name('admin.exams.publish');
});

// Group untuk STUDENT
Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/exams', [StudentExamController::class, 'index'])->name('student.dashboard');
    Route::get('/exams/{exam}', [StudentExamController::class, 'show'])->name('student.exams.show');
    Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit'])->name('student.exams.submit');
    Route::get('/results/{exam}', [StudentExamController::class, 'showResult'])->name('student.results.show');
    Route::post('/student/exams/{exam}/auto-save', [StudentExamController::class, 'autoSave'])->name('student.exams.auto-save');
    Route::post('/student/exams/{exam}/toggle-flag', [StudentExamController::class, 'toggleFlag'])->name('student.exams.toggle-flag');
    Route::get('/exam-success', [StudentExamController::class, 'success'])->name('student.exam.success');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';