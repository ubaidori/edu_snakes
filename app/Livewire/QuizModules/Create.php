<?php

namespace App\Livewire\QuizModules;

use App\Models\QuizModule;
use Livewire\Component;

class Create extends Component
{
    public ?QuizModule $quizModule = null;
    public bool $isEdit = false;

    public string $title = '';
    public string $description = '';
    public int $minimum_questions = 10;
    public bool $is_active = true;

    public function mount(?QuizModule $quizModule = null)
    {
        if ($quizModule && $quizModule->exists) {
            if ($quizModule->user_id !== auth()->id()) {
                abort(403, 'Anda tidak memiliki akses ke modul kuis ini.');
            }

            $this->quizModule = $quizModule;
            $this->isEdit = true;
            $this->title = $quizModule->title;
            $this->description = $quizModule->description ?? '';
            $this->minimum_questions = $quizModule->minimum_questions;
            $this->is_active = (bool) $quizModule->is_active;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => ['required', 'min:3', 'max:150'],
            'minimum_questions' => ['required', 'integer', 'min:1'],
        ]);

        $data = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'minimum_questions' => $this->minimum_questions,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            $this->quizModule->update($data);
            session()->flash('success', 'Modul kuis berhasil diperbarui.');
        } else {
            QuizModule::create($data);
            session()->flash('success', 'Modul kuis berhasil ditambahkan.');
        }

        return redirect()->route('quiz-modules.index');
    }

    public function render()
    {
        return view('livewire.quiz-modules.create');
    }
}