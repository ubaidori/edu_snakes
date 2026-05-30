<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Breadcrumb -->
        <div class="mb-6 flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                    {{ $isEdit ? 'Edit Bahan Ajar' : 'Tambah Bahan Ajar' }}
                </h1>
                <p class="text-xs text-gray-500">Kelola dan publikasikan materi ajar interaktif untuk siswa.</p>
            </div>
            <a
                href="{{ route('materials.index') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg transition duration-150 shadow-sm"
            >
                &larr; Kembali
            </a>
        </div>

        <!-- Form Card Container -->
        <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-sm">
            <form wire:submit="save" class="space-y-6">
                
                <!-- Title Input -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Judul Materi</label>
                    <input
                        type="text"
                        wire:model="title"
                        class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-sm py-2.5 px-3.5 placeholder-gray-400"
                        placeholder="Contoh: Pengenalan Aljabar Dasar & Himpunan"
                    >
                    @error('title')
                        <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content Textarea -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Isi & Konten Pembelajaran</label>
                    <textarea
                        wire:model="content"
                        rows="8"
                        class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-sm py-2.5 px-3.5 placeholder-gray-400"
                        placeholder="Tulis materi pembelajaran secara terperinci dan menyenangkan di sini..."
                    ></textarea>
                    @error('content')
                        <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Visual File Uploader -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Gambar Pendukung (Opsional)</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- Drag and Drop Dropzone -->
                        <div class="md:col-span-2">
                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-blue-50/20 hover:border-blue-300 transition duration-150">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <span class="text-3xl mb-2.5 filter drop-shadow-sm">📤</span>
                                    <p class="mb-1 text-xs text-gray-700 font-extrabold">Klik untuk unggah</p>
                                    <p class="text-[10px] text-gray-400 leading-normal max-w-[200px]">
                                        Seret file gambar ke sini. Mendukung PNG, JPG, JPEG (Maks. 2MB).
                                    </p>
                                </div>
                                <input type="file" wire:model="image" class="hidden" accept="image/*" />
                            </label>
                            @error('image')
                                <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Preview Area -->
                        <div class="md:col-span-1 border border-gray-100 rounded-2xl p-3 bg-gray-50/50 flex flex-col items-center justify-center min-h-[160px]">
                            @if ($image)
                                <span class="text-[9px] font-extrabold text-blue-600 uppercase tracking-widest mb-1.5">Gambar Baru</span>
                                <div class="relative w-full aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>
                            @elseif ($existingImage)
                                <span class="text-[9px] font-extrabold text-gray-500 uppercase tracking-widest mb-1.5">Gambar Saat Ini</span>
                                <div class="relative w-full aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="text-center text-gray-300 py-6 select-none">
                                    <span class="text-4xl block mb-1">🖼️</span>
                                    <span class="text-[10px] uppercase font-bold tracking-wider">No Image</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Secondary Parameters Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    
                    <!-- Sort Order Input -->
                    <div class="space-y-2">
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Urutan Tampil (Sort Order)</label>
                        <input
                            type="number"
                            wire:model="sort_order"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-sm py-2 px-3"
                            min="0"
                        >
                        @error('sort_order')
                            <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Toggle Switch Card -->
                    <div class="flex items-center h-full pt-2 md:pt-6">
                        <label class="relative flex items-center p-3 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 cursor-pointer w-full transition select-none">
                            <input
                                type="checkbox"
                                wire:model="is_active"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4.5 w-4.5 cursor-pointer"
                            >
                            <div class="ml-3">
                                <span class="block text-xs font-extrabold text-gray-900 leading-tight">Aktifkan Materi</span>
                                <span class="block text-[10px] text-gray-400 mt-0.5">Siswa dapat membaca materi ini di halaman belajar.</span>
                            </div>
                        </label>
                    </div>

                </div>

                <!-- Action Button Footers -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a
                        href="{{ route('materials.index') }}"
                        class="px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-extrabold rounded-xl transition duration-150 shadow-sm"
                    >
                        Batal
                    </a>
                    
                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition duration-150 shadow-md"
                    >
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Materi' }}
                    </button>
                </div>

            </form>
        </div>
        
    </div>
</div>
