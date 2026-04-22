<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'score', 'answers'];

    protected $casts = [
        'answers' => 'array',
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


