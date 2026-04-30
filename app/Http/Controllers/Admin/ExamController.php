<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Exports\QuestionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * ExamController
 * * Mengendalikan pengurusan utama peperiksaan, statistik dashboard, 
 * pengiraan markah, dan penjanaan laporan (PDF/Excel).
 */
class ExamController extends Controller
{
    /**
     * Paparan Utama Dashboard Admin
     * Menjana statistik ringkas untuk pemantauan masa-nyata.
     */
    public function index()
    {
        $now = now();

        $data = [
            'totalExams'        => Exam::count(),
            'totalStudents'     => User::where('role', 'student')->count(),
            'totalSubmissions'  => Submission::count(),
            'ongoingExams'      => Exam::where('start_time', '<=', $now)
                                        ->where('end_time', '>=', $now)
                                        ->count(),
            'latestSubmissions' => Submission::with(['user', 'exam'])
                                        ->latest()
                                        ->take(5)
                                        ->get(),
            'exams'             => Exam::latest()->get(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Paparan Senarai Peperiksaan (CRUD Index)
     */
    public function peperiksaanIndex()
    {
        $exams = Exam::latest()->get();
        return view('admin.exam', compact('exams')); 
    }

    /**
     * Menyimpan maklumat peperiksaan baharu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
        ]);

        Exam::create($validated);

        return redirect()->route('admin.peperiksaan.index')
            ->with('success', 'Peperiksaan berjaya didaftarkan!');
    }

    /**
     * Mengemaskini maklumat peperiksaan sedia ada.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
        ]);

        $exam->update($validated);

        return redirect()->back()->with('success', 'Maklumat peperiksaan berjaya dikemaskini!');
    }

    /**
     * Menghapuskan rekod peperiksaan.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->back()->with('success', 'Peperiksaan telah dipadamkan sepenuhnya!');
    }

    /**
     * Memaparkan keputusan keseluruhan bagi peperiksaan tertentu.
     */
    public function results(Exam $exam)
    {
        $submissions = Submission::with('user')
                        ->where('exam_id', $exam->id)
                        ->get();

        return view('admin.results', compact('exam', 'submissions'));
    }

    /**
     * Mengemaskini markah pelajar secara manual.
     * Secara automatik mengira semula peratusan berdasarkan jawapan betul.
     */
    public function updateScore(Request $request, Submission $submission)
    {
        $request->validate([
            'new_correct' => 'required|integer|min:0|max:' . $submission->total_questions,
        ]);

        $submission->correct_answers = $request->new_correct;

        // Logik Pengiraan Semula: (Betul / Jumlah Soalan) * 100
        $newPercentage = ($request->new_correct / $submission->total_questions) * 100;
        $submission->score = round($newPercentage, 2); 

        $submission->save();

        return back()->with('success', 'Markah pelajar ' . $submission->user->name . ' telah dikemaskini!');
    }

    /**
     * Paparan Bank Soalan Utama.
     */
    public function bankIndex()
    {
        $exams = Exam::withCount('questions')->orderBy('created_at', 'desc')->get();
        return view('admin.bank_index', compact('exams'));
    }

    /**
     * Eksport senarai soalan ke format Excel.
     */
    public function exportExcel($id)
    {
        $exam = Exam::findOrFail($id);
        $fileName = $this->sanitizeFileName($exam->title) . '.xlsx';
        
        return Excel::download(new QuestionsExport($exam->id), $fileName);
    }

    /**
     * Eksport bank soalan ke format PDF yang kemas.
     */
    public function exportPDF($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        $fileName = $this->sanitizeFileName($exam->title) . '_' . date('d-m-Y') . '.pdf';
        
        $pdf = Pdf::loadView('admin.pdf', compact('exam'));
        return $pdf->download($fileName);
    }
    
    /**
     * Mengawal status penerbitan keputusan peperiksaan (Public/Private).
     */
    public function publishResult(Exam $exam)
    {
        $exam->is_published = !$exam->is_published;
        $exam->save();

        $status = $exam->is_published ? 'diterbitkan' : 'disembunyikan';
        return back()->with('success', "Keputusan peperiksaan telah $status.");
    }

    /**
     * Fungsi Pembantu: Membersihkan nama fail daripada simbol dilarang.
     */
    private function sanitizeFileName($title)
    {
        return str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $title);
    }
}