<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_module_id',
        'players',
        'active_player_index',
        'current_question_id',
        'used_question_ids',
        'settings',
        'status',
        'winner_player_index',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'players' => 'array',
            'used_question_ids' => 'array',
            'settings' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quizModule(): BelongsTo
    {
        return $this->belongsTo(QuizModule::class);
    }

    public function currentQuestion(): BelongsTo
    {
        return $this->belongsTo(
            Question::class,
            'current_question_id'
        );
    }
}