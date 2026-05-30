<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Breadcrumb -->
        <div class="mb-6 flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                    {{ $isEdit ? 'Edit Modul Kuis' : 'Buat Modul Kuis' }}
                </h1>
                <p class="text-xs text-gray-500">Buat set peraturan kuis baru untuk diujikan pada sesi permainan.</p>
            </div>
            <a
                href="{{ route('quiz-modules.index') }}"
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
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Judul Modul Kuis</label>
                    <input
                        type="text"
                        wire:model="title"
                        class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-100 transition duration-150 text-sm py-2.5 px-3.5 placeholder-gray-400"
                        placeholder="Contoh: Matematika Pecahan Kelas 5"
                    >
                    @error('title')
                        <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Textarea -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Deskripsi Pembelajaran (Opsional)</label>
                    <textarea
                        wire:model="description"
                        rows="4"
                        class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-100 transition duration-150 text-sm py-2.5 px-3.5 placeholder-gray-400"
                        placeholder="Berikan deskripsi singkat mengenai topik pembahasan modul kuis ini..."
                    ></textarea>
                    @error('description')
                        <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Secondary Parameters Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Minimum Questions Input -->
                    <div class="space-y-2">
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Syarat Minimum Soal</label>
                        <input
                            type="number"
                            wire:model="minimum_questions"
                            class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-100 transition duration-150 text-sm py-2 px-3"
                            min="1"
                        >
                        <p class="text-[10px] text-gray-400 leading-normal">
                            Batas minimal soal yang harus dimasukkan agar modul kuis ini layak dimainkan pada papan game.
                        </p>
                        @error('minimum_questions')
                            <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Toggle Switch Card -->
                    <div class="flex items-center h-full pt-2">
                        <label class="relative flex items-center p-3 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 cursor-pointer w-full transition select-none">
                            <input
                                type="checkbox"
                                wire:model="is_active"
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 h-4.5 w-4.5 cursor-pointer"
                            >
                            <div class="ml-3">
                                <span class="block text-xs font-extrabold text-gray-900 leading-tight">Aktifkan Modul Kuis</span>
                                <span class="block text-[10px] text-gray-400 mt-0.5">Siswa dapat memainkan game menggunakan modul kuis ini.</span>
                            </div>
                        </label>
                    </div>

                </div>

                <!-- Action Button Footers -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a
                        href="{{ route('quiz-modules.index') }}"
                        class="px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-extrabold rounded-xl transition duration-150 shadow-sm"
                    >
                        Batal
                    </a>
                    
                    <button
                        type="submit"
                        class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-extrabold rounded-xl transition duration-150 shadow-md"
                    >
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Modul' }}
                    </button>
                </div>

            </form>
        </div>
        
    </div>
</div>