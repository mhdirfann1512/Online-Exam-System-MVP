<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title', 
        'instructions', 
        'duration_minutes', 
        'start_time', 
        'end_time'
    ];

    // Relationship: Satu exam ada banyak soalan
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}