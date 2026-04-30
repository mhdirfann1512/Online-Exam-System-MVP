<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * QuestionsImport
 * * Mengendalikan proses memuat naik soalan secara pukal daripada fail Excel.
 * Menggunakan HeadingRow untuk memetakan kolum Excel terus ke atribut model.
 */
class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $exam_id;

    public function __construct($exam_id)
    {
        $this->exam_id = $exam_id;
    }

    /**
     * Memproses setiap baris fail Excel menjadi rekod Model Question.
     */
    public function model(array $row)
    {
        // 1. Standarisasi key: Menukarkan semua header kepada huruf kecil (lowercase).
        // Ini mengelakkan ralat jika user menukar 'TYPE' kepada 'type'.
        $row = array_change_key_case($row, CASE_LOWER);

        // 2. Guard: Langkau baris jika teks soalan kosong (elakkan rekod sampah).
        if (!isset($row['question_text']) || empty(trim($row['question_text']))) {
            return null;
        }

        // 3. Logic Mapping: Menukar baris Excel kepada format Database.
        return new Question([
            'exam_id'        => $this->exam_id,
            'type'           => $row['type'] ?? 'mcq', 
            'question_text'  => trim($row['question_text']),
            'options'        => ($row['type'] == 'mcq') ? [
                'A' => $row['option_a'] ?? '',
                'B' => $row['option_b'] ?? '',
                'C' => $row['option_c'] ?? '',
                'D' => $row['option_d'] ?? '',
            ] : null,
            'correct_answer' => trim($row['correct_answer'] ?? ''),
        ]);
    }
}