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
    $now = \Carbon\Carbon::now();

    // Line ni untuk kau check jam sistem. Lepas check, delete balik k!
    //dd($now->toDateTimeString());
    
    // Ambil exam yang belum tamat (termasuk yang belum mula)
    $exams = Exam::where('end_time', '>=', $now)->get();

    return view('student.exams', compact('exams', 'now'));
}

public function show(Exam $exam)
{
    $now = \Carbon\Carbon::now();

    // 1. Check kalau exam belum mula atau dah tamat
    if ($now->lt($exam->start_time) || $now->gt($exam->end_time)) {
        return redirect()->route('student.dashboard')->with('error', 'Maaf, peperiksaan ini tidak tersedia atau telah tamat.');
    }

    // 2. Check kalau student dah pernah hantar (Avoid double submit)
    $alreadySubmitted = Submission::where('exam_id', $exam->id)
                                  ->where('user_id', auth()->id())
                                  ->exists();

    if ($alreadySubmitted) {
        return redirect()->route('student.dashboard')->with('error', 'Anda telah pun menduduki peperiksaan ini.');
    }

    return view('student.take-exam', compact('exam'));
}

public function submit(Request $request, Exam $exam)
{
    $studentAnswers = $request->input('answers'); // Ambil dari form
    $totalQuestions = $exam->questions->count();
    $correctCount = 0;

    foreach ($exam->questions as $question) {
        $studentAnswer = trim($request->input("answers.{$question->id}"));

        // Kalau student tak jawab langsung, terus skip ke soalan seterusnya
        if (empty($studentAnswer)) {
            continue; 
        }

        if ($question->type == 'mcq') {
            if (strtoupper($studentAnswer) == strtoupper($question->correct_answer)) {
                $correctCount++;
            }
        } else {
            // Subjective: Check keyword
            $keywords = explode(',', $question->correct_answer);
            foreach ($keywords as $keyword) {
                $trimmedKeyword = trim($keyword);
                // Pastikan keyword tu tak kosong sebelum check
                if (!empty($trimmedKeyword) && str_contains(strtolower($studentAnswer), strtolower($trimmedKeyword))) {
                    $correctCount++;
                    break; 
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