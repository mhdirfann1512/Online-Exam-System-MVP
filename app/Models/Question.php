<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = ['exam_id', 'type', 'question_text', 'options', 'correct_answer'];

    protected $casts = [
        'options' => 'array', // Automatik tukar JSON ke Array PHP dan sebaliknya
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}