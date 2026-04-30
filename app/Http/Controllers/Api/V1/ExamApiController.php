<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ExamApiController (Version 1)
 * * Menyediakan endpoint RESTful untuk integrasi luaran.
 * Menggunakan format JSON sebagai standard pertukaran data.
 */
class ExamApiController extends Controller
{
    /**
     * Endpoint: Senarai Peperiksaan Terbitan
     * GET /api/v1/exams
     * * Mengembalikan maklumat asas semua peperiksaan yang berstatus 'is_published'.
     */
    public function listExams()
    {
        $exams = Exam::where('is_published', 1)
                     ->select('id', 'title', 'duration_minutes', 'start_time', 'end_time')
                     ->get();

        return response()->json([
            'status' => 'success',
            'count'  => $exams->count(),
            'data'   => $exams
        ], 200);
    }

    /**
     * Endpoint: Keputusan Peperiksaan Penuh
     * POST /api/v1/results/{exam_id}
     * * Memaparkan senarai calon dan markah bagi peperiksaan tertentu.
     */
    public function examResults($exam_id)
    {
        // Check if exam exists
        if (!Exam::where('id', $exam_id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Exam not found'], 404);
        }

        $results = Submission::where('exam_id', $exam_id)
                    ->with('user:id,name') // Eager load hanya kolom yang diperlukan
                    ->select('id', 'user_id', 'score', 'created_at')
                    ->get();

        return response()->json([
            'status'  => 'success',
            'exam_id' => (int)$exam_id,
            'results' => $results
        ], 200);
    }

    /**
     * Endpoint: Transkrip Digital Pelajar
     * GET /api/v1/student/{student_id}/transcript
     * * Mengumpulkan sejarah peperiksaan bagi individu pelajar (Student History).
     */
    public function studentTranscript($student_id)
    {
        // Check if student exists
        if (!User::where('id', $student_id)->where('role', 'student')->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Student not found'], 404);
        }

        $transcript = Submission::where('user_id', $student_id)
                        ->with('exam:id,title')
                        ->select('id', 'exam_id', 'score', 'created_at')
                        ->get()
                        ->map(function($item) {
                            return [
                                'exam_title' => $item->exam->title ?? 'N/A',
                                'score'      => (float)$item->score,
                                'date'       => $item->created_at->format('Y-m-d')
                            ];
                        });

        return response()->json([
            'status'     => 'success',
            'student_id' => (int)$student_id,
            'history'    => $transcript
        ], 200);
    }
}