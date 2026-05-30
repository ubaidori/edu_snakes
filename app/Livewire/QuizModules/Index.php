<?php

namespace App\Livewire\QuizModules;

use App\Models\QuizModule;
use Livewire\Component;

class Index extends Component
{
    public function delete($id)
    {
        $module = QuizModule::findOrFail($id);

        if ($module->user_id === auth()->id()) {
            $module->delete();
            session()->flash('success', 'Modul kuis berhasil dihapus.');
        } else {
            session()->flash('error', 'Anda tidak memiliki akses untuk menghapus modul kuis ini.');
        }
    }

    public function render()
    {
        $quizModules = QuizModule::where('user_id', auth()->id())
            ->withCount('questions')
            ->latest()
            ->get();

        return view('livewire.quiz-modules.index', [
            'quizModules' => $quizModules,
        ]);
    }
}