<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MaterialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_materials_page_requires_authentication(): void
    {
        $response = $this->get('/materials');
        $response->assertRedirect('/login');
    }

    public function test_instructor_can_view_materials_list(): void
    {
        $user = User::factory()->create();
        
        Material::create([
            'user_id' => $user->id,
            'title' => 'Pengenalan Laravel',
            'content' => 'Laravel adalah framework PHP MVC.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Material::create([
            'user_id' => $user->id,
            'title' => 'Materi Ular Tangga',
            'content' => 'Cara bermain game ular tangga edukasi.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/materials');
        $response->assertStatus(200);
        $response->assertSee('Pengenalan Laravel');
        $response->assertSee('Materi Ular Tangga');
    }

    public function test_instructor_can_create_material_with_livewire(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->image('test_material.jpg');

        Livewire::actingAs($user)
            ->test(\App\Livewire\Materials\Create::class)
            ->set('title', 'Belajar Livewire v3')
            ->set('content', 'Tutorial lengkap tentang Livewire v3.')
            ->set('sort_order', 10)
            ->set('image', $imageFile)
            ->call('save')
            ->assertRedirect(route('materials.index'));

        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'title' => 'Belajar Livewire v3',
            'content' => 'Tutorial lengkap tentang Livewire v3.',
            'sort_order' => 10,
        ]);

        $material = Material::where('title', 'Belajar Livewire v3')->first();
        $this->assertNotNull($material->image_path);
        Storage::disk('public')->assertExists($material->image_path);
    }

    public function test_instructor_can_edit_material_with_livewire(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $material = Material::create([
            'user_id' => $user->id,
            'title' => 'Materi Lama',
            'content' => 'Materi ajar lama yang perlu diubah.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Materials\Create::class, ['material' => $material])
            ->assertSet('title', 'Materi Lama')
            ->set('title', 'Materi Baru Diperbarui')
            ->set('content', 'Konten ajar yang sudah diupdate.')
            ->call('save')
            ->assertRedirect(route('materials.index'));

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'title' => 'Materi Baru Diperbarui',
            'content' => 'Konten ajar yang sudah diupdate.',
        ]);
    }

    public function test_instructor_can_delete_material(): void
    {
        $user = User::factory()->create();

        $material = Material::create([
            'user_id' => $user->id,
            'title' => 'Materi untuk Dihapus',
            'content' => 'Materi ajar ini akan segera dihapus.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Materials\Index::class)
            ->call('delete', $material->id);

        $this->assertDatabaseMissing('materials', [
            'id' => $material->id,
        ]);
    }
}
