<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'score', 'correct_answers', 
        'total_questions', 'answers', 'flagged_questions'
    ];

    protected $casts = [
        'answers' => 'array',
        'flagged_questions' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}