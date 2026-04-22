<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = ['exam_id', 'type', 'question_text', 'options', 'correct_answer'];

    protected $casts = [
    'options' => 'array', // Supaya kita boleh simpan Array terus ke database
];

/**
     * Relationship: Soalan ini milik satu Exam
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
