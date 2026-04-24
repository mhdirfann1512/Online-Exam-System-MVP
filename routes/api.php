<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ExamApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // List all published exams
    Route::get('/exams', [ExamApiController::class, 'listExams']);
    
    // Get results for an exam
    Route::post('/results/{exam_id}', [ExamApiController::class, 'examResults']);
    
    // Get student's exam history
    Route::get('/student/{student_id}/transcript', [ExamApiController::class, 'studentTranscript']);
    
});