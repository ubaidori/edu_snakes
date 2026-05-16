<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'quiz_module_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'timer_seconds',
        'image_path',
        'points',
        'is_active',
    ];

    public function quizModule(): BelongsTo
    {
        return $this->belongsTo(QuizModule::class);
    }
}