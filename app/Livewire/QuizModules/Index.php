<?php

namespace App\Livewire\QuizModules;

use App\Models\QuizModule;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $quizModules = QuizModule::latest()->get();

        return view('livewire.quiz-modules.index', [
            'quizModules' => $quizModules,
        ]);
    }
}