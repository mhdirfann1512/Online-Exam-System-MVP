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
    public function index()
    {
        $now = now();

        // 1. Total exams created
        $totalExams = \App\Models\Exam::count();

        // 2. Total students (Hanya yang role student)
        $totalStudents = \App\Models\User::where('role', 'student')->count();

        // 3. Ongoing exams (Masa sekarang di tengah-tengah start & end)
        $ongoingExams = \App\Models\Exam::where('start_time', '<=', $now)
                                        ->where('end_time', '>=', $now)
                                        ->count();

        // 4. Latest submissions (Ambil 5 yang terbaru dengan data User & Exam)
        $latestSubmissions = \App\Models\Submission::with(['user', 'exam'])
                                                    ->latest()
                                                    ->take(5)
                                                    ->get();

        $totalSubmissions = \App\Models\Submission::count();
        
        // Senarai exam untuk table bawah
        $exams = \App\Models\Exam::latest()->get();

        return view('admin.dashboard', compact(
            'exams', 
            'totalExams', 
            'totalStudents', 
            'ongoingExams', 
            'latestSubmissions',
            'totalSubmissions'
        ));
    }

    public function peperiksaanIndex()
    {
        // Ambil senarai peperiksaan untuk table
        $exams = Exam::latest()->get();
        
        // Kita panggil file exam.blade.php
        return view('admin.exam', compact('exams')); 
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

        return redirect()->route('admin.peperiksaan.index')->with('success', 'Exam created successfully!');
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required',
            'duration_minutes' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Update data guna data dari form
        $exam->update($request->all());

        return redirect()->back()->with('success', 'Maklumat peperiksaan berjaya dikemaskini!');
    }

    public function destroy(Exam $exam)
    {
        // Padam exam (Laravel akan uruskan soalan & submission kalau kau set cascade on delete)
        $exam->delete();

        return redirect()->back()->with('success', 'Peperiksaan telah dipadamkan sepenuhnya!');
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
    
    public function publishResult(Exam $exam)
    {
        // Toggle status (kalau 0 jadi 1, kalau 1 jadi 0 pun boleh)
        $exam->is_published = !$exam->is_published;
        $exam->save();

        $status = $exam->is_published ? 'diterbitkan' : 'disembunyikan';
        return back()->with('success', "Keputusan peperiksaan telah $status.");
    }
}
