<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $exam_id;

    public function __construct($exam_id)
    {
        $this->exam_id = $exam_id;
    }

    public function model(array $row)
    {
        // Guna array_change_key_case supaya dia tak kisah Header Huruf Besar/Kecil
        $row = array_change_key_case($row, CASE_LOWER);

        return new Question([
            'exam_id'        => $this->exam_id,
            'type'           => $row['type'] ?? 'mcq', 
            'question_text'  => $row['question_text'] ?? 'No Question',
            'options'        => ($row['type'] == 'mcq') ? [
                'A' => $row['option_a'] ?? '',
                'B' => $row['option_b'] ?? '',
                'C' => $row['option_c'] ?? '',
                'D' => $row['option_d'] ?? '',
            ] : null,
            'correct_answer' => $row['correct_answer'] ?? '',
        ]);
    }
}