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
        return view('admin.questions', compact('exam', 'questions'));
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
    public function bank()
    {
        $questions = Question::with('exam')->latest()->get();
        $allExams = Exam::orderBy('title')->get(); // Untuk dropdown pilihan exam
        return view('admin.questions.bank', compact('questions', 'allExams'));
    }

    // Proses copy soalan ke exam lain
    public function copyToExam(Request $request, Question $question)
    {
        $request->validate([
            'target_exam_id' => 'required|exists:exams,id'
        ]);

        // Clone soalan
        $newQuestion = $question->replicate();
        $newQuestion->exam_id = $request->target_exam_id;
        $newQuestion->save();

        return back()->with('success', 'Soalan berjaya diklon ke dalam exam pilihan!');
    }
}