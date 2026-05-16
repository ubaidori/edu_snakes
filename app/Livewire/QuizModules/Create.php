<?php

namespace App\Livewire\QuizModules;

use App\Models\QuizModule;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';

    public string $description = '';

    public int $minimum_questions = 10;

    public bool $is_active = true;

    public function save()
    {
        $this->validate([
            'title' => ['required', 'min:3'],
            'minimum_questions' => ['required', 'integer', 'min:1'],
        ]);

        QuizModule::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'minimum_questions' => $this->minimum_questions,
            'is_active' => $this->is_active,
        ]);

        return redirect()->route('quiz-modules.index');
    }

    public function render()
    {
        return view('livewire.quiz-modules.create');
    }
}