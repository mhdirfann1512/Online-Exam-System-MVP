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

        // Buat atau ambil submission sedia ada sebagai draft
        $submission = Submission::firstOrCreate(
            ['user_id' => auth()->id(), 'exam_id' => $exam->id],
            [
                'score' => 0,
                'correct_answers' => 0,
                'total_questions' => $exam->questions->count(),
                'answers' => [],
                'flagged_questions' => []
            ]
        );

        return view('student.take-exam', compact('exam', 'submission'));
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
        $submission = Submission::where('user_id', auth()->id())
                                    ->where('exam_id', $exam->id)
                                    ->first();

            if ($submission) {
                $submission->update([
                    'score' => round($score),
                    'correct_answers' => $correctCount,
                    'total_questions' => $totalQuestions,
                    // 'answers' tak payah update pun takpe sebab auto-save dah buat, 
                    // tapi kalau nak double confirm, boleh letak:
                    'answers' => $request->input('answers') ?? $submission->answers
                ]);
            }

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

    public function autoSave(Request $request, Exam $exam) 
    {
        $submission = Submission::where('user_id', auth()->id())->where('exam_id', $exam->id)->first();
        $currentAnswers = $submission->answers ?? [];
        
        // Merge jawapan baru
        $newAnswers = array_replace($currentAnswers, $request->answers);
        $submission->update(['answers' => $newAnswers]);
        
        return response()->json(['status' => 'saved']);
    }

    public function toggleFlag(Request $request, Exam $exam) 
    {
        $submission = Submission::where('user_id', auth()->id())->where('exam_id', $exam->id)->first();
        $flagged = $submission->flagged_questions ?? [];
        $questionId = $request->question_id;

        if (in_array($questionId, $flagged)) {
            $flagged = array_values(array_diff($flagged, [$questionId]));
        } else {
            $flagged[] = $questionId;
        }

        $submission->update(['flagged_questions' => $flagged]);
        return response()->json(['flagged' => $flagged]);
    }
}