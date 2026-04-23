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
/*public function index()
{
    $now = \Carbon\Carbon::now();

    // Line ni untuk kau check jam sistem. Lepas check, delete balik k!
    //dd($now->toDateTimeString());
    
    // Ambil exam yang belum tamat (termasuk yang belum mula)
    $exams = Exam::where('end_time', '>=', $now)->get();

    return view('student.exams', compact('exams', 'now'));
}*/

/*public function index()
{
    $now = \Carbon\Carbon::now();
    // Ambil semua exam yang student terlibat
    $exams = Exam::all(); 
    
    // Ambil submission student ni (untuk check dia dah jawab ke belum)
    $userSubmissions = Submission::where('user_id', auth()->id())->pluck('exam_id')->toArray();

    return view('student.exams', compact('exams', 'now', 'userSubmissions'));
}*/

public function index()
{
    $now = \Carbon\Carbon::now();
    
    // Ambil semua exam (atau buat pagination kalau banyak)
    $exams = Exam::orderBy('start_time', 'asc')->get();

    // Ambil ID exam yang student ni dah jawab
    $userSubmissions = Submission::where('user_id', auth()->id())
                                  ->pluck('exam_id')
                                  ->toArray();

    return view('student.exams', compact('exams', 'now', 'userSubmissions'));
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
    // Pastikan answers sentiasa ada nilai (kalau tak ada, bagi array kosong)
    $finalAnswers = $request->input('answers') ?? [];

    foreach ($exam->questions as $question) {
        // Guna null coalescing (?? null) supaya tak error kalau student tak klik
        $studentAnswer = $request->input("answers.{$question->id}") ?? null;

        // JIKA KOSONG: Terus skip, biar markah tak naik (0 marks)
        if (is_null($studentAnswer) || trim($studentAnswer) === "") {
            continue; 
        }

        if ($question->type == 'mcq') {
            // Sekarang confirm takkan error sebab kita dah tapis null kat atas
            if (strtoupper($studentAnswer) == strtoupper($question->correct_answer)) {
                $correctCount++;
            }
        } else {
            // Subjective logic
            $keywords = explode(',', $question->correct_answer);
            foreach ($keywords as $keyword) {
                $trimmedKeyword = trim($keyword);
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
        'correct_answers' => $correctCount, // Simpan jumlah betul
        'total_questions' => $exam->questions->count(), // Simpan jumlah soalan
        'answers' => $finalAnswers // Sekarang dia akan simpan [] bukan null
    ]);

    return redirect()->route('student.dashboard')->with('success', "Tahniah! Jawapan peperiksaan anda telah berjaya dihantar.");
}

    public function showResult(Exam $exam)
    {
        // Pastikan dah publish
        if (!$exam->is_published) {
            return redirect()->route('student.dashboard')->with('error', 'Keputusan belum sedia.');
        }

        // Ambil submission student ni
        $submission = Submission::where('exam_id', $exam->id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();

        return view('student.result-detail', compact('exam', 'submission'));
    }
}