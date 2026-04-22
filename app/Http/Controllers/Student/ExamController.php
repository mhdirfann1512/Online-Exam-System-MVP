<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use Carbon\Carbon;

class ExamController extends Controller
{
public function index()
{
    // Cuba ambil SEMUA exam tanpa kira masa dulu untuk test UI
    $exams = \App\Models\Exam::all(); 

    return view('student.exams', compact('exams'));
}

    public function show(Exam $exam)
    {
        // Check kalau student dah pernah submit
        $alreadySubmitted = Submission::where('exam_id', $exam->id)
                                      ->where('user_id', Auth::id())
                                      ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.dashboard')->with('error', 'You have already taken this exam.');
        }

        return view('student.take-exam', compact('exam'));
    }

public function submit(Request $request, Exam $exam)
{
    $studentAnswers = $request->input('answers'); // Ambil dari form
    $totalQuestions = $exam->questions->count();
    $correctCount = 0;

    foreach ($exam->questions as $question) {
        $studentAnswer = $studentAnswers[$question->id] ?? null;

        if ($question->type == 'mcq') {
            // Check jawapan tepat (A, B, C, atau D)
            if (strtoupper($studentAnswer) == strtoupper($question->correct_answer)) {
                $correctCount++;
            }
        } else {
            // Subjective: Check keyword (pecahkan guna koma)
            $keywords = explode(',', $question->correct_answer);
            foreach ($keywords as $keyword) {
                if (str_contains(strtolower($studentAnswer), strtolower(trim($keyword)))) {
                    $correctCount++;
                    break; // Jumpa satu keyword dah kira betul untuk soalan tu
                }
            }
        }
    }

    // Kira markah (percentage)
    $score = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

    // Simpan ke database
    Submission::create([
        'user_id' => auth()->id(),
        'exam_id' => $exam->id,
        'score' => round($score),
        'answers' => $studentAnswers
    ]);

    return redirect()->route('student.dashboard')->with('success', "Exam submitted! Your score: " . round($score) . "%");
}
}