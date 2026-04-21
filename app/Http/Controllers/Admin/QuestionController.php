<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
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
}