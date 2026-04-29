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
        // 1. JIKA MODE SINGLE
        if ($request->entry_mode === 'single') {
            // Pindahkan validation ke sini
            $request->validate([
                'type' => 'required',
                'question_text' => 'required',
                'correct_answer' => 'required',
            ]);

            $question = new Question();
            $question->exam_id = $exam->id;
            $question->type = $request->type;
            $question->question_text = $request->question_text;
            
            if ($request->type == 'mcq') {
                $question->options = [
                    'A' => $request->option_a,
                    'B' => $request->option_b,
                    'C' => $request->option_c,
                    'D' => $request->option_d,
                ];
                $question->correct_answer = $request->correct_answer;
            } else {
                $question->correct_answer = $request->correct_answer;
            }
            $question->save();

            return redirect()->back()->with('success', '1 Question added!');
        }

        // 2. JIKA MODE BULK
        if ($request->entry_mode === 'bulk') {
            // Validate bulk_text pula, bukan question_text
            $request->validate([
                'bulk_text' => 'required',
                'type' => 'required',
            ]);

            $text = $request->bulk_text;
            $type = $request->type;
            $questionsCount = 0;

            // Pecahkan teks berdasarkan nombor soalan
            $blocks = preg_split('/(?=\d+\.)/', $text, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($blocks as $block) {
                if ($type === 'mcq') {
                    // Regex yang lebih "longgar" sedikit supaya senang tangkap data
                    preg_match('/\d+\.\s*(.*?)\s*A\.\s*(.*?)\s*B\.\s*(.*?)\s*C\.\s*(.*?)\s*D\.\s*(.*?)\s*ANSWER:\s*([A-D])/s', $block, $match);

                    if ($match) {
                        Question::create([
                            'exam_id' => $exam->id,
                            'type' => 'mcq',
                            'question_text' => trim($match[1]),
                            'options' => [
                                'A' => trim($match[2]),
                                'B' => trim($match[3]),
                                'C' => trim($match[4]),
                                'D' => trim($match[5]),
                            ],
                            'correct_answer' => trim($match[6]),
                        ]);
                        $questionsCount++;
                    }
                } else {
                    preg_match('/\d+\.\s*(.*?)\s*ANSWER:\s*(.*)/s', $block, $match);
                    if ($match) {
                        Question::create([
                            'exam_id' => $exam->id,
                            'type' => 'subjective',
                            'question_text' => trim($match[1]),
                            'options' => null,
                            'correct_answer' => trim($match[2]),
                        ]);
                        $questionsCount++;
                    }
                }
            }

            if ($questionsCount === 0) {
                return redirect()->back()->withErrors(['bulk_text' => 'Format salah. Sila pastikan anda ikut format (1. Soalan ... A. Pilihan ... ANSWER: A)']);
            }

            return redirect()->back()->with('success', "$questionsCount questions added successfully!");
        }
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required',
            'correct_answer' => 'required',
        ]);

        $question->question_text = $request->question_text;
        $question->type = $request->type;

        if ($request->type == 'mcq') {
            $question->options = [
                'A' => $request->option_a,
                'B' => $request->option_b,
                'C' => $request->option_c,
                'D' => $request->option_d,
            ];
        } else {
            $question->options = null;
        }

        $question->correct_answer = $request->correct_answer;
        $question->save();

        return redirect()->back()->with('success', 'Soalan berjaya dikemaskini!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Soalan telah dipadam!');
    }

    public function destroyAll(Exam $exam)
    {
        // Kira berapa soalan sebelum padam (untuk mesej success nanti)
        $count = $exam->questions()->count();

        if ($count === 0) {
            return redirect()->back()->with('error', 'Tiada soalan untuk dipadam.');
        }

        // Padam semua soalan yang ada kaitan dengan exam_id ini
        $exam->questions()->delete();

        return redirect()->back()->with('success', "Semua $count soalan telah berjaya dipadamkan!");
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