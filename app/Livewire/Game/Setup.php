<?php

namespace App\Livewire\Game;

use App\Models\GameSession;
use App\Models\QuizModule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Setup extends Component
{
    public QuizModule $module;

    public $player_count = 2;

    public $players = [
        ['name' => 'Tim 1'],
        ['name' => 'Tim 2'],
    ];

    public function mount(QuizModule $module)
    {
        $this->module = $module->loadCount('questions');

        if ($this->module->questions_count < $this->module->minimum_questions) {
            return redirect()->route('game.create');
        }
    }

    public function setPlayerCount($count)
    {
        $this->player_count = $count;
        $this->updatedPlayerCount();
    }

    public function updatedPlayerCount()
    {
        $this->players = [];

        for ($i = 1; $i <= $this->player_count; $i++) {
            $this->players[] = [
                'name' => 'Tim ' . $i,
            ];
        }
    }

    public function start()
    {
        $this->validate([
            'player_count' => 'required|integer|min:2|max:6',
            'players.*.name' => 'required|string|max:50',
        ]);

        $players = collect($this->players)->map(function ($player, $index) {
            return [
                'id' => $index + 1,
                'name' => $player['name'],
                'position' => 0,
                'score' => 0,
                'answered_questions' => [],
            ];
        })->toArray();

        $session = GameSession::create([
            'user_id' => Auth::id(),
            'quiz_module_id' => $this->module->id,
            'players' => $players,
            'active_player_index' => 0,
            'used_question_ids' => [],
            'status' => 'playing',
        ]);

        return redirect()->route('game.play', $session);
    }

    public function render()
    {
        return view('livewire.game.setup');
    }
}