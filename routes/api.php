<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ExamApiController;


Route::post('/login', function (Request $request) {

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token
    ]);
});

/**
 * --------------------------------------------------------------------------
 * API Authenticated User
 * --------------------------------------------------------------------------
 * Mendapatkan maklumat profil pengguna yang sedang log masuk 
 * melalui token Sanctum.
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * --------------------------------------------------------------------------
 * API V1 - Examination System
 * --------------------------------------------------------------------------
 * Kumpulan endpoint untuk integrasi pihak ketiga atau aplikasi mudah alih.
 * Dilindungi oleh Middleware 'auth:sanctum' untuk kawalan akses.
 */
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    /**
     * Endpoint: Senarai Peperiksaan Terbitan
     * Mengembalikan senarai peperiksaan di mana status 'is_published' adalah benar.
     */
    Route::get('/exams', [ExamApiController::class, 'listExams']);
    
    /**
     * Endpoint: Keputusan Peperiksaan Spesifik
     * Mendapatkan senarai markah pelajar bagi satu ID peperiksaan tertentu.
     * Menggunakan method POST untuk perlindungan data parameter.
     */
    Route::post('/results/{exam_id}', [ExamApiController::class, 'examResults']);
    
    /**
     * Endpoint: Transkrip Akademik Pelajar
     * Memaparkan sejarah peperiksaan dan pencapaian bagi individu pelajar.
     */
    Route::get('/student/{student_id}/transcript', [ExamApiController::class, 'studentTranscript']);
    
});