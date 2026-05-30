<?php

namespace App\Livewire\Game;

use App\Models\QuizModule;
use Livewire\Component;

class Create extends Component
{
    public $quiz_module_id;
    public $player_count = 2;

    public function selectModule($id)
    {
        $this->quiz_module_id = $id;
        $this->startGame();
    }

    public function startGame()
    {
        $module = QuizModule::withCount('questions')
            ->findOrFail($this->quiz_module_id);

        if ($module->questions_count < $module->minimum_questions) {
            $this->addError(
                'quiz_module_id',
                'Jumlah soal belum cukup. Minimal ' . $module->minimum_questions . ' soal.'
            );

            return;
        }

        return redirect()->route('game.setup', $module->id);
    }

    public function render()
    {
        return view('livewire.game.create', [
            'modules' => QuizModule::where('user_id', auth()->id())->withCount('questions')->get(),
        ]);
    }
}