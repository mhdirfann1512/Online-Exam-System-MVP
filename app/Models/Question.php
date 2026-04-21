<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['exam_id', 'type', 'question_text', 'options', 'correct_answer'];

    protected $casts = [
    'options' => 'array', // Supaya kita boleh simpan Array terus ke database
];
}
