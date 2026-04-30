<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title', 
        'instructions', 
        'duration_minutes', 
        'start_time', 
        'end_time',
        'is_published' // Ditambah supaya status terbitan boleh disimpan
    ];

    /**
     * Relasi: Satu peperiksaan mempunyai banyak soalan.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relasi: Satu peperiksaan boleh mempunyai banyak penyertaan (submissions).
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}