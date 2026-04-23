<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'score', 'correct_answers', 'total_questions', 'answers', 'flagged_questions'];

    protected $casts = [
        'answers' => 'array',
        'flagged_questions' => 'array'
    ];

    public function user()
    {
        // Ini bermaksud satu submission dimiliki oleh seorang user
        return $this->belongsTo(\App\Models\User::class);
    }

    public function exam()
    {
        // Ini pula bermaksud submission ini milik satu exam
        return $this->belongsTo(\App\Models\Exam::class);
    }
}


