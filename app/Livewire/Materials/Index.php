<?php

namespace App\Livewire\Materials;

use App\Models\Material;
use Livewire\Component;

class Index extends Component
{
    public function delete($id)
    {
        $material = Material::findOrFail($id);
        
        // Ensure user owns it or has permission
        if ($material->user_id === auth()->id()) {
            $material->delete();
            session()->flash('success', 'Bahan ajar berhasil dihapus.');
        } else {
            session()->flash('error', 'Anda tidak memiliki akses untuk menghapus bahan ajar ini.');
        }
    }

    public function render()
    {
        $materials = Material::where('user_id', auth()->id())
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.materials.index', [
            'materials' => $materials,
        ]);
    }
}
