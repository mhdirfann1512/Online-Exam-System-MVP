<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    public function collection()
    {
        // Ambil soalan milik exam ini sahaja
        return Question::where('exam_id', $this->examId)->get();
    }

    public function headings(): array
    {
        return [
            'Jenis Soalan',
            'Teks Soalan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Jawapan Betul'
        ];
    }

    public function map($question): array
    {
        // Pastikan $options adalah array
        $opts = is_array($question->options) ? $question->options : json_decode($question->options, true);

        return [
            $question->type == 'mcq' ? 'Objektif' : 'Subjektif',
            $question->question_text,
            $opts['A'] ?? '', // Ambil dari key "A" dalam JSON
            $opts['B'] ?? '', // Ambil dari key "B" dalam JSON
            $opts['C'] ?? '', // Ambil dari key "C" dalam JSON
            $opts['D'] ?? '', // Ambil dari key "D" dalam JSON
            $question->correct_answer,
        ];
    }
}