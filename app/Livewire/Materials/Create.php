<?php

namespace App\Livewire\Materials;

use App\Models\Material;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    public ?Material $material = null;
    public bool $isEdit = false;

    public string $title = '';
    public string $content = '';
    public $image = null; // Temporary uploaded image
    public ?string $existingImage = null; // Existing image path
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules()
    {
        return [
            'title' => 'required|string|min:3|max:150',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function mount(?Material $material = null)
    {
        if ($material && $material->exists) {
            $this->material = $material;
            $this->isEdit = true;
            $this->title = $material->title;
            $this->content = $material->content;
            $this->existingImage = $material->image_path;
            $this->sort_order = $material->sort_order;
            $this->is_active = (bool) $material->is_active;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        $imagePath = $this->existingImage;

        if ($this->image) {
            // Delete existing image if it exists
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('materials', 'public');
        }

        $data = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
            'image_path' => $imagePath,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            $this->material->update($data);
            session()->flash('success', 'Bahan ajar berhasil diperbarui.');
        } else {
            Material::create($data);
            session()->flash('success', 'Bahan ajar berhasil ditambahkan.');
        }

        return redirect()->route('materials.index');
    }

    public function render()
    {
        return view('livewire.materials.create');
    }
}
