<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Exports\QuestionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamController extends Controller
{
    // Papar senarai exam di Dashboard Admin
    public function index()
    {
        $exams = Exam::all();
        return view('admin.dashboard', compact('exams'));
    }

    // Simpan exam baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'duration_minutes' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Exam::create($request->all());

        return redirect()->back()->with('success', 'Exam created successfully!');
    }

    public function results(Exam $exam)
    {
        // Ambil semua submission untuk exam ni berserta nama student
        $submissions = \App\Models\Submission::with('user')
                        ->where('exam_id', $exam->id)
                        ->get();

        return view('admin.results', compact('exam', 'submissions'));
    }

    public function updateScore(Request $request, \App\Models\Submission $submission)
    {
        $request->validate([
            'new_correct' => 'required|integer|min:0|max:' . $submission->total_questions,
        ]);

        // 1. Update jumlah jawapan betul
        $submission->correct_answers = $request->new_correct;

        // 2. Kira peratus baru secara automatik
        // Rumus: (Betul / Total) * 100
        $newPercentage = ($request->new_correct / $submission->total_questions) * 100;
        $submission->score = round($newPercentage, 2); // Simpan 2 tempat perpuluhan

        $submission->save();

        return back()->with('success', 'Markah pelajar ' . $submission->user->name . ' telah dikemaskini!');
    }

    public function bankIndex()
    {
        // Ambil semua exam berserta bilangan soalan
        $exams = Exam::withCount('questions')->orderBy('created_at', 'desc')->get();
        
        return view('admin.bank_index', compact('exams'));
    }

    // Untuk Excel
    public function exportExcel($id)
    {
        $exam = Exam::findOrFail($id);
        
        // Bersihkan nama file
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $exam->title);
        
        // Nama file akan jadi "Nama Exam Anda.xlsx"
        return Excel::download(new QuestionsExport($exam->id), $fileName . '.xlsx');
    }

    // Untuk PDF
    public function exportPDF($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        
        // Kita "bersihkan" nama file supaya tak ada simbol yang dilarang oleh Windows/Mac
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $exam->title);
        $fileName = $fileName . '_' . date('d-m-Y');
        
        $pdf = Pdf::loadView('admin.pdf', compact('exam'));
        
        // Nama file akan jadi "Nama Exam Anda.pdf"
        return $pdf->download($fileName . '.pdf');
    }
    
}
