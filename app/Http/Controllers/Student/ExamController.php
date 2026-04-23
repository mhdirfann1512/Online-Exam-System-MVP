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
        $exams = Exam::all(); 
        $userSubmissions = Submission::where('user_id', auth()->id())->pluck('exam_id')->toArray();

        return view('student.exams', compact('exams', 'now', 'userSubmissions'));
    }

public function show(Exam $exam)
{
    $now = \Carbon\Carbon::now();

    // 1. SECURITY: Check kalau exam dah diterbitkan (is_published == 1)
    // Walaupun masa ada lagi, kalau dah publish, student baru takleh masuk.
    if ($exam->is_published) {
        return redirect()->route('student.dashboard')
            ->with('error', 'Kemasukan ditutup kerana keputusan bagi peperiksaan ini telah diterbitkan.');
    }

    // 2. Check kalau exam belum mula atau dah tamat masa
    if ($now->lt($exam->start_time) || $now->gt($exam->end_time)) {
        return redirect()->route('student.dashboard')
            ->with('error', 'Maaf, peperiksaan ini tidak tersedia atau telah tamat.');
    }

    // 3. Check kalau student dah pernah hantar (Avoid double submission)
    $alreadySubmitted = Submission::where('exam_id', $exam->id)
                                  ->where('user_id', auth()->id())
                                  ->exists();

    // Kita check juga kalau score dah pernah diupdate (bukan 0 lagi)
    $submissionCheck = Submission::where('exam_id', $exam->id)
                                 ->where('user_id', auth()->id())
                                 ->first();

    if ($alreadySubmitted && $submissionCheck && $submissionCheck->score > 0) {
        return redirect()->route('student.dashboard')->with('error', 'Anda telah pun menduduki peperiksaan ini.');
    }

    // 4. Buat atau ambil submission sedia ada sebagai draft (Logic Auto-save kau)
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

        //return redirect()->route('student.dashboard')->with('success', "Tahniah! Jawapan peperiksaan anda telah berjaya dihantar.");
        return redirect()->route('student.exam.success')->with('success_message', 'Jawapan anda telah selamat diterima!');
    }

    public function success()
    {
        // Dia cuma buat satu kerja je: panggil view
        return view('student.exam-success');
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