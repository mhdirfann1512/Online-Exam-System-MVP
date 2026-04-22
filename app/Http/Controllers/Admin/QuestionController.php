<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelInstance;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        // Ambil semua soalan untuk exam ni
        $questions = $exam->questions;
        // AMBIL SEMUA EXAM (untuk dropdown import)
        // Kita gunakan withCount supaya boleh tunjuk berapa soalan ada dalam setiap exam
        $allExams = Exam::withCount('questions')->get();
        return view('admin.questions', compact('exam', 'questions', 'allExams'));
    }

    public function store(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'type' => 'required',
            'question_text' => 'required',
            'correct_answer' => 'required',
        ]);

        $question = new Question();
        $question->exam_id = $exam->id;
        $question->type = $request->type;
        $question->question_text = $request->question_text;
        
        // Kalau MCQ, simpan options A, B, C, D
        if ($request->type == 'mcq') {
            $question->options = [
                'A' => $request->option_a,
                'B' => $request->option_b,
                'C' => $request->option_c,
                'D' => $request->option_d,
            ];
            $question->correct_answer = $request->correct_answer; // Contoh: 'A'
        } else {
            // Kalau Subjective, correct_answer adalah keywords
            $question->correct_answer = $request->correct_answer; // Contoh: 'merdeka,1957'
        }

        $question->save();

        return redirect()->back()->with('success', 'Question added!');
    }

    public function import(Request $request, \App\Models\Exam $exam)
    {
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), "r");
        $header = fgetcsv($handle, 1000, ","); // Skip header row

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            \App\Models\Question::create([
                'exam_id' => $exam->id,
                'type' => $data[0], // type
                'question_text' => $data[1], // question_text
                'options' => $data[0] == 'mcq' ? [
                    'A' => $data[2], 'B' => $data[3], 'C' => $data[4], 'D' => $data[5]
                ] : null,
                'correct_answer' => $data[6], // correct_answer
            ]);
        }
        fclose($handle);

        return redirect()->back()->with('success', 'Bulk questions uploaded via Native CSV!');
    }

        // Paparkan semua soalan yang ada dalam sistem
    public function bank(Exam $exam) // Kita terima model Exam kat sini
    {
        // Ambil semua exam lain yang ada soalan untuk dijadikan sumber
        $allExams = Exam::withCount('questions')
                        ->where('id', '!=', $exam->id) // Jangan tunjuk exam sendiri dalam list sumber
                        ->orderBy('title')
                        ->get();

        // Kita namakan $targetExamId supaya Blade kau tak error lagi
        $targetExamId = $exam->id; 

        return view('admin.bank', compact('allExams', 'targetExamId', 'exam'));
    }

    public function importFromExam(Request $request, $targetExamId)
    {
        $request->validate([
            'source_exam_id' => 'required|exists:exams,id',
        ]);

        $sourceQuestions = Question::where('exam_id', $request->source_exam_id)->get();

        if ($sourceQuestions->isEmpty()) {
            return back()->with('error', 'Exam sumber tidak mempunyai soalan.');
        }

        foreach ($sourceQuestions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->exam_id = $targetExamId;
            $newQuestion->save();
        }

        return redirect()->route('admin.questions.index', $targetExamId)
                     ->with('success', count($sourceQuestions) . ' soalan berjaya disalin!');

    }

        // Fungsi baru untuk copy banyak soalan yang dipilih secara manual
    public function copySelected(Request $request, $targetExamId)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        foreach ($request->question_ids as $id) {
            $question = Question::find($id);
            $newQuestion = $question->replicate();
            $newQuestion->exam_id = $targetExamId;
            $newQuestion->save();
        }

        return redirect()->route('admin.questions.index', $targetExamId)
                        ->with('success', count($request->question_ids) . ' soalan dipilih berjaya ditambah!');
    }
}