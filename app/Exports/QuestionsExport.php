<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * QuestionsExport
 * * Menguruskan logik penukaran data soalan dari pangkalan data ke format Excel.
 * Menggunakan interface WithMapping untuk memastikan format JSON dipaparkan dengan betul.
 */
class QuestionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    /**
     * Mengambil koleksi soalan berdasarkan ID peperiksaan.
     */
    public function collection()
    {
        return Question::where('exam_id', $this->examId)->get();
    }

    /**
     * Menetapkan baris tajuk (Header) bagi fail Excel.
     */
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

    /**
     * Memetakan (Map) setiap rekod soalan ke baris Excel yang sesuai.
     * Mengendalikan logic pertukaran JSON options ke column individu.
     */
    public function map($question): array
    {
        // Safety check: Memastikan options diproses sebagai array
        $opts = is_array($question->options) 
                ? $question->options 
                : json_decode($question->options, true);

        return [
            $question->type == 'mcq' ? 'Objektif' : 'Subjektif',
            $question->question_text,
            $opts['A'] ?? '-', 
            $opts['B'] ?? '-', 
            $opts['C'] ?? '-', 
            $opts['D'] ?? '-', 
            $question->correct_answer,
        ];
    }

    /**
     * Memberikan gaya (Styling) pada helaian Excel.
     * Membuatkan baris pertama (Header) menjadi huruf tebal (Bold).
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}