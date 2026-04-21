<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

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
}
