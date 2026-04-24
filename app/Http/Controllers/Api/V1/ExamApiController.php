<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class ExamApiController extends Controller
{
    // 1. GET /api/v1/exams - list all published exams
    public function listExams()
    {
        $exams = Exam::where('is_published', 1)
                     ->select('id', 'title', 'duration_minutes', 'start_time', 'end_time')
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $exams
        ]);
    }

    // 2. POST /api/v1/results/{exam_id} - get results for an exam
    public function examResults($exam_id)
    {
        $results = Submission::where('exam_id', $exam_id)
                    ->with('user:id,name') // Ambil nama student je
                    ->select('id', 'user_id', 'score', 'created_at')
                    ->get();

        return response()->json([
            'status' => 'success',
            'exam_id' => $exam_id,
            'results' => $results
        ]);
    }

    // 3. GET /api/v1/student/{student_id}/transcript
    public function studentTranscript($student_id)
    {
        $transcript = Submission::where('user_id', $student_id)
                        ->with('exam:id,title') // Ambil nama exam je
                        ->select('id', 'exam_id', 'score', 'created_at')
                        ->get()
                        ->map(function($item) {
                            return [
                                'exam_title' => $item->exam->title,
                                'score' => $item->score,
                                'date' => $item->created_at->format('Y-m-d')
                            ];
                        });

        return response()->json([
            'status' => 'success',
            'student_id' => $student_id,
            'history' => $transcript
        ]);
    }
}
