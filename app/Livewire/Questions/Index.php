<?php

namespace App\Livewire\Questions;

use App\Models\Question;
use App\Models\QuizModule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    public $quizModules;

    public $questionId;
    public $quiz_module_id;
    public $question_text;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $correct_option = 'A';
    public $image = null; // Temp uploaded image
    public ?string $existingImage = null; // Current image path

    public $isEdit = false;
    public $filter_module_id = '';

    public function mount()
    {
        $this->quizModules = QuizModule::where('user_id', auth()->id())->orderBy('title')->get();
    }

    protected function rules()
    {
        return [
            'quiz_module_id' => 'required|exists:quiz_modules,id',
            'question_text' => 'required|string|min:5',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function save()
    {
        $data = $this->validate();

        // Enforce tenancy: Verify the quiz module belongs to the active instructor
        $module = QuizModule::where('user_id', auth()->id())->findOrFail($this->quiz_module_id);

        $imagePath = $this->existingImage;

        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('questions', 'public');
        }

        unset($data['image']);
        $data['image_path'] = $imagePath;

        Question::updateOrCreate(
            ['id' => $this->questionId],
            $data
        );

        $this->resetForm();

        session()->flash('success', $this->isEdit ? 'Soal berhasil diperbarui.' : 'Soal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);

        $this->questionId = $question->id;
        $this->quiz_module_id = $question->quiz_module_id;
        $this->question_text = $question->question_text;
        $this->option_a = $question->option_a;
        $this->option_b = $question->option_b;
        $this->option_c = $question->option_c;
        $this->option_d = $question->option_d;
        $this->correct_option = $question->correct_option;
        $this->existingImage = $question->image_path;

        $this->isEdit = true;
    }

    public function delete($id)
    {
        $question = Question::findOrFail($id);
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }
        $question->delete();

        session()->flash('success', 'Soal berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->reset([
            'questionId',
            'quiz_module_id',
            'question_text',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_option',
            'image',
            'existingImage',
            'isEdit',
        ]);

        $this->correct_option = 'A';
    }

    public function render()
    {
        $query = Question::whereHas('quizModule', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('quizModule')->latest();

        if ($this->filter_module_id) {
            $query->where('quiz_module_id', $this->filter_module_id);
        }

        return view('livewire.questions.index', [
            'questions' => $query->get(),
        ]);
    }
}