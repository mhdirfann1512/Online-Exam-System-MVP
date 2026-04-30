<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

/**
 * QuestionController
 * * Menguruskan bank soalan termasuk kemasukan data secara tunggal (Single),
 * pukal (Bulk Text/CSV), serta fungsi penyalinan soalan (Cloning) antara peperiksaan.
 */
class QuestionController extends Controller
{
    /**
     * Memaparkan senarai soalan bagi sesebuah peperiksaan.
     */
    public function index(Exam $exam)
    {
        $questions = $exam->questions;
        $allExams = Exam::withCount('questions')->get();
        
        return view('admin.questions', compact('exam', 'questions', 'allExams'));
    }

    /**
     * Menyimpan soalan melalui dua mod: Single Entry atau Bulk Text Parsing.
     */
    public function store(Request $request, Exam $exam)
    {
        // MOD 1: Single Question Entry
        if ($request->entry_mode === 'single') {
            $validated = $request->validate([
                'type'           => 'required|in:mcq,subjective',
                'question_text'  => 'required|string',
                'correct_answer' => 'required',
            ]);

            $question = new Question();
            $question->exam_id       = $exam->id;
            $question->type          = $request->type;
            $question->question_text = $request->question_text;
            
            if ($request->type == 'mcq') {
                $question->options = [
                    'A' => $request->option_a,
                    'B' => $request->option_b,
                    'C' => $request->option_c,
                    'D' => $request->option_d,
                ];
            }
            
            $question->correct_answer = $request->correct_answer;
            $question->save();

            return redirect()->back()->with('success', 'Satu soalan berjaya ditambah!');
        }

        // MOD 2: Bulk Text Parsing (Regex Intelligence)
        if ($request->entry_mode === 'bulk') {
            $request->validate([
                'bulk_text' => 'required|string',
                'type'      => 'required|in:mcq,subjective',
            ]);

            $questionsCount = 0;
            // Pecahkan blok teks berdasarkan corak nombor (cth: 1. , 2. )
            $blocks = preg_split('/(?=\d+\.)/', $request->bulk_text, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($blocks as $block) {
                if ($request->type === 'mcq') {
                    // Regex untuk menangkap Soalan, Pilihan A-D, dan Jawapan
                    preg_match('/\d+\.\s*(.*?)\s*A\.\s*(.*?)\s*B\.\s*(.*?)\s*C\.\s*(.*?)\s*D\.\s*(.*?)\s*ANSWER:\s*([A-D])/s', $block, $match);

                    if ($match) {
                        Question::create([
                            'exam_id'        => $exam->id,
                            'type'           => 'mcq',
                            'question_text'  => trim($match[1]),
                            'options'        => [
                                'A' => trim($match[2]), 'B' => trim($match[3]),
                                'C' => trim($match[4]), 'D' => trim($match[5]),
                            ],
                            'correct_answer' => trim($match[6]),
                        ]);
                        $questionsCount++;
                    }
                } else {
                    // Regex untuk soalan subjektif
                    preg_match('/\d+\.\s*(.*?)\s*ANSWER:\s*(.*)/s', $block, $match);
                    if ($match) {
                        Question::create([
                            'exam_id'        => $exam->id,
                            'type'           => 'subjective',
                            'question_text'  => trim($match[1]),
                            'correct_answer' => trim($match[2]),
                        ]);
                        $questionsCount++;
                    }
                }
            }

            if ($questionsCount === 0) {
                return redirect()->back()->withErrors(['bulk_text' => 'Format tidak dikesan. Sila pastikan format penomboran dan ANSWER: adalah betul.']);
            }

            return redirect()->back()->with('success', "$questionsCount soalan berjaya diproses!");
        }
    }

    /**
     * Mengemaskini butiran soalan spesifik.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text'  => 'required',
            'correct_answer' => 'required',
        ]);

        $question->question_text = $request->question_text;
        $question->type = $request->type;

        if ($request->type == 'mcq') {
            $question->options = [
                'A' => $request->option_a, 'B' => $request->option_b,
                'C' => $request->option_c, 'D' => $request->option_d,
            ];
        } else {
            $question->options = null;
        }

        $question->correct_answer = $request->correct_answer;
        $question->save();

        return redirect()->back()->with('success', 'Soalan berjaya dikemaskini!');
    }

    /**
     * Menghapuskan satu soalan.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Soalan telah dipadam!');
    }

    /**
     * Mengosongkan semua soalan bagi sesebuah peperiksaan.
     */
    public function destroyAll(Exam $exam)
    {
        $count = $exam->questions()->count();
        if ($count === 0) {
            return redirect()->back()->with('error', 'Tiada soalan untuk dipadam.');
        }

        $exam->questions()->delete();
        return redirect()->back()->with('success', "Kesemua $count soalan telah dibersihkan!");
    }

    /**
     * Import soalan melalui fail CSV (Native PHP Stream).
     */
    public function import(Request $request, Exam $exam)
    {
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), "r");
        fgetcsv($handle, 1000, ","); // Langkau baris tajuk (Header)

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            Question::create([
                'exam_id'        => $exam->id,
                'type'           => $data[0],
                'question_text'  => $data[1],
                'options'        => $data[0] == 'mcq' ? [
                    'A' => $data[2], 'B' => $data[3], 'C' => $data[4], 'D' => $data[5]
                ] : null,
                'correct_answer' => $data[6],
            ]);
        }
        fclose($handle);

        return redirect()->back()->with('success', 'Data CSV berjaya diimport!');
    }

    /**
     * Paparan Bank Soalan bagi tujuan penyalinan (Cloning).
     */
    public function bank(Exam $exam)
    {
        $allExams = Exam::withCount('questions')
                        ->where('id', '!=', $exam->id)
                        ->orderBy('title')
                        ->get();

        $targetExamId = $exam->id; 
        return view('admin.bank', compact('allExams', 'targetExamId', 'exam'));
    }

    /**
     * Menyalin (Clone) keseluruhan soalan dari peperiksaan sumber.
     */
    public function importFromExam(Request $request, $targetExamId)
    {
        $request->validate(['source_exam_id' => 'required|exists:exams,id']);

        $sourceQuestions = Question::where('exam_id', $request->source_exam_id)->get();

        if ($sourceQuestions->isEmpty()) {
            return back()->with('error', 'Peperiksaan sumber tidak mempunyai soalan.');
        }

        foreach ($sourceQuestions as $question) {
            $newQuestion = $question->replicate(); // Mencipta salinan rekod tanpa ID asal
            $newQuestion->exam_id = $targetExamId;
            $newQuestion->save();
        }

        return redirect()->route('admin.questions.index', $targetExamId)
                         ->with('success', count($sourceQuestions) . ' soalan berjaya disalin!');
    }

    /**
     * Menyalin soalan terpilih secara manual.
     */
    public function copySelected(Request $request, $targetExamId)
    {
        $request->validate([
            'question_ids'   => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        foreach ($request->question_ids as $id) {
            $question = Question::find($id);
            $newQuestion = $question->replicate();
            $newQuestion->exam_id = $targetExamId;
            $newQuestion->save();
        }

        return redirect()->route('admin.questions.index', $targetExamId)
                        ->with('success', count($request->question_ids) . ' soalan pilihan berjaya disalin!');
    }
}