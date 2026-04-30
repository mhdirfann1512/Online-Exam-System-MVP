<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Submission;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Student ExamController
 * * Menguruskan kitaran hidup peperiksaan bagi pelajar:
 * - Paparan senarai & statistik pelajar.
 * - Kawalan akses (masa & status penerbitan).
 * - Logic penggredan automatik (MCQ & Keyword-based Subjective).
 * - Integrasi Auto-save & Question Flagging (AJAX).
 */
class ExamController extends Controller
{
    /**
     * Dashboard Pelajar
     * Memaparkan statistik peribadi dan senarai peperiksaan yang tersedia.
     */
    public function index()
    {
        $user = auth()->user();
        $now = now();

        $data = [
            'upcomingExams'   => Exam::where('start_time', '>', $now)->count(),
            'completedExams'  => Submission::where('user_id', $user->id)->count(),
            'averageScore'    => Submission::where('user_id', $user->id)->avg('score') ?? 0,
            'exams'           => Exam::latest()->get(),
            'userSubmissions' => Submission::where('user_id', $user->id)->pluck('exam_id')->toArray(),
            'now'             => $now,
        ];

        return view('student.exams', $data);
    }

    /**
     * Memulakan Sesi Peperiksaan
     * Mengandungi kawalan keselamatan yang ketat untuk mengelakkan pencerobohan sesi.
     */
    public function show(Exam $exam)
    {
        $now = Carbon::now();

        // 1. Guard: Check kalau exam tak ada soalan lagi
        if ($exam->questions->count() == 0) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Soalan dalam peperiksaan ini belum tersedia. Sila hubungi admin.');
        }

        // 2. Guard: Check status penerbitan
        if ($exam->is_published) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Kemasukan ditutup. Keputusan peperiksaan ini telah diterbitkan.');
        }

        // 3. Guard: Validasi tetingkap masa (Start/End Time)
        if ($now->lt(Carbon::parse($exam->start_time)) || $now->gt(Carbon::parse($exam->end_time))) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Maaf, peperiksaan ini tidak tersedia atau telah tamat.');
        }

        // 4. Guard: Anti-double submission
        $submission = Submission::where('exam_id', $exam->id)
                                ->where('user_id', auth()->id())
                                ->first();

        if ($submission && $submission->score > 0) {
            return redirect()->route('student.dashboard')->with('error', 'Anda telah pun menduduki peperiksaan ini.');
        }

        /**
         * Logic Draft: Gunakan firstOrCreate untuk menyokong ciri Auto-save.
         * Ini membolehkan progres pelajar disimpan walaupun browser tertutup.
         */
        $submission = Submission::firstOrCreate(
            ['user_id' => auth()->id(), 'exam_id' => $exam->id],
            [
                'score'             => 0,
                'correct_answers'   => 0,
                'total_questions'   => $exam->questions->count(),
                'answers'           => [],
                'flagged_questions' => []
            ]
        );

        return view('student.take-exam', compact('exam', 'submission'));
    }

    /**
     * Proses Penghantaran (Final Submission)
     * Mengandungi enjin penggredan automatik untuk MCQ dan Subjektif.
     */
    public function submit(Request $request, Exam $exam)
    {
        $totalQuestions = $exam->questions->count();

        // Validasi Integriti: Memastikan tiada soalan yang tertinggal.
        $request->validate([
            'answers'   => [
                'required', 'array',
                function ($attribute, $value, $fail) use ($totalQuestions) {
                    if (count($value) < $totalQuestions) {
                        $fail('Sila pastikan SEMUA soalan telah dijawab sebelum menghantar.');
                    }
                },
            ],
            'answers.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null($value) || trim($value) === "") {
                        $fail('Terdapat ruangan jawapan yang belum diisi.');
                    }
                }
            ],
        ]);

        $correctCount = 0;

        /**
         * Engine Penggredan:
         * 1. MCQ: Padanan tepat (Case-insensitive).
         * 2. Subjective: Padanan kata kunci (Keyword matching).
         */
        foreach ($exam->questions as $question) {
            $studentAnswer = $request->input("answers.{$question->id}") ?? null;

            if (is_null($studentAnswer)) continue;

            if ($question->type == 'mcq') {
                if (strtoupper($studentAnswer) == strtoupper($question->correct_answer)) {
                    $correctCount++;
                }
            } else {
                $keywords = explode(',', $question->correct_answer);
                foreach ($keywords as $keyword) {
                    if (str_contains(strtolower($studentAnswer), strtolower(trim($keyword)))) {
                        $correctCount++;
                        break; 
                    }
                }
            }
        }

        $score = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

        Submission::where('user_id', auth()->id())
                  ->where('exam_id', $exam->id)
                  ->update([
                      'score'           => round($score),
                      'correct_answers' => $correctCount,
                      'total_questions' => $totalQuestions,
                      'answers'         => $request->input('answers')
                  ]);

        return redirect()->route('student.exam.success')->with('success_message', 'Jawapan anda telah selamat diterima!');
    }

    /**
     * Paparan keputusan bagi peperiksaan yang telah tamat dan diterbitkan.
     */
    public function showResult(Exam $exam)
    {
        if (!$exam->is_published) {
            return redirect()->route('student.dashboard')->with('error', 'Keputusan belum sedia untuk dipaparkan.');
        }

        $submission = Submission::where('exam_id', $exam->id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();

        return view('student.result-detail', compact('exam', 'submission'));
    }

    /**
     * Endpoint Auto-save (AJAX)
     * Menyimpan progres jawapan setiap kali pelajar menukar pilihan jawapan.
     */
    public function autoSave(Request $request, Exam $exam) 
    {
        $submission = Submission::where('user_id', auth()->id())->where('exam_id', $exam->id)->first();
        if (!$submission) return response()->json(['status' => 'error'], 404);

        $newAnswers = array_replace($submission->answers ?? [], $request->answers);
        $submission->update(['answers' => $newAnswers]);
        
        return response()->json(['status' => 'saved', 'data' => $newAnswers]);
    }

    /**
     * Endpoint Flagging (AJAX)
     * Membolehkan pelajar menandakan soalan yang ingin disemak semula.
     */
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

    public function success() { return view('student.exam-success'); }
}