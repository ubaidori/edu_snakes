<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Premium Header Area -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-10 -translate-y-6 opacity-10 text-9xl font-black select-none">✍️</div>
            <div class="relative z-10">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kelola Bank Soal</h1>
                <p class="text-blue-100 text-xs md:text-sm max-w-xl mt-1.5">
                    Tambahkan pertanyaan pilihan ganda, sertakan ilustrasi pendukung, dan hubungkan dengan modul kuis agar siswa dapat belajar sambil meluncur di papan ular tangga.
                </p>
            </div>
        </div>

        <!-- System Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl shadow-sm flex items-start gap-3">
                <span class="text-xl">✅</span>
                <div>
                    <h4 class="font-bold text-sm">Berhasil!</h4>
                    <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Split Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Panel: Form Input Soal (span 1) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-6">
                    <div class="mb-5 pb-3 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="text-base font-extrabold text-gray-900 tracking-tight flex items-center gap-1.5">
                            <span>📝</span> {{ $isEdit ? 'Edit Pertanyaan' : 'Tambah Soal Baru' }}
                        </h2>
                        @if ($isEdit)
                            <button 
                                type="button" 
                                wire:click="resetForm" 
                                class="text-[10px] text-gray-400 font-extrabold hover:text-gray-600 transition"
                            >
                                Reset Form
                            </button>
                        @endif
                    </div>

                    <form wire:submit="save" class="space-y-4">
                        
                        <!-- Select Quiz Module -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-extrabold text-gray-700 uppercase tracking-wider">Modul Kuis Terkait</label>
                            <select 
                                wire:model="quiz_module_id" 
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-xs py-2 px-3 bg-white"
                            >
                                <option value="">Pilih Modul Kuis</option>
                                @foreach ($quizModules as $module)
                                    <option value="{{ $module->id }}">{{ $module->title }}</option>
                                @endforeach
                            </select>
                            @error('quiz_module_id') 
                                <p class="text-rose-500 text-[10px] font-bold mt-0.5">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Question Text -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-extrabold text-gray-700 uppercase tracking-wider">Teks Pertanyaan</label>
                            <textarea 
                                wire:model="question_text" 
                                rows="3"
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-xs py-2 px-3 placeholder-gray-400"
                                placeholder="Tuliskan pertanyaan kuis di sini..."
                            ></textarea>
                            @error('question_text') 
                                <p class="text-rose-500 text-[10px] font-bold mt-0.5">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Mini Image Uploader & Preview -->
                        <div class="space-y-1.5 border border-gray-50 rounded-xl p-3 bg-gray-50/50">
                            <label class="block text-[10px] font-extrabold text-gray-700 uppercase tracking-wider">Gambar Soal (Opsional)</label>
                            
                            <div class="flex items-center gap-3">
                                <label class="flex flex-col items-center justify-center w-24 h-16 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer bg-white hover:bg-blue-50/20 hover:border-blue-300 transition duration-150 shrink-0">
                                    <span class="text-lg">📤</span>
                                    <span class="text-[9px] font-bold text-gray-400">Pilih</span>
                                    <input type="file" wire:model="image" class="hidden" accept="image/*" />
                                </label>

                                <div class="flex-1 w-full flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden h-16 bg-white shadow-sm relative select-none">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <span class="absolute bottom-1 right-1 text-[8px] bg-blue-600 text-white font-bold px-1.5 rounded-full uppercase">Baru</span>
                                    @elseif ($existingImage)
                                        <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                                        <span class="absolute bottom-1 right-1 text-[8px] bg-slate-700 text-white font-bold px-1.5 rounded-full uppercase">Sedia</span>
                                    @else
                                        <span class="text-[9px] text-gray-300 uppercase tracking-wider font-bold">No Image</span>
                                    @endif
                                </div>
                            </div>
                            @error('image') 
                                <p class="text-rose-500 text-[10px] font-bold mt-0.5">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Multiple Choice Options -->
                        <div class="space-y-3 pt-2">
                            <label class="block text-[10px] font-extrabold text-gray-700 uppercase tracking-wider">Opsi Jawaban & Pilihan Ganda</label>
                            
                            @foreach (['a', 'b', 'c', 'd'] as $opt)
                                <div class="relative flex items-center">
                                    <!-- Option Letter Label -->
                                    <span class="absolute left-3 font-extrabold text-xs text-blue-600 uppercase w-4 text-center">
                                        {{ $opt }}
                                    </span>
                                    <input 
                                        type="text" 
                                        wire:model="option_{{ $opt }}" 
                                        class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-xs py-2 pl-9 pr-3 placeholder-gray-400"
                                        placeholder="Pilihan {{ strtoupper($opt) }}"
                                    >
                                </div>
                                @error('option_'.$opt) 
                                    <p class="text-rose-500 text-[10px] font-bold -mt-1 pl-9">{{ $message }}</p> 
                                @enderror
                            @endforeach
                        </div>

                        <!-- Correct Answer Select -->
                        <div class="space-y-1.5 pt-1">
                            <label class="block text-[10px] font-extrabold text-gray-700 uppercase tracking-wider">Jawaban Benar</label>
                            <select 
                                wire:model="correct_option" 
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition duration-150 text-xs py-2 px-3 bg-white font-extrabold text-blue-700"
                            >
                                <option value="A">Opsi A</option>
                                <option value="B">Opsi B</option>
                                <option value="C">Opsi C</option>
                                <option value="D">Opsi D</option>
                            </select>
                            @error('correct_option') 
                                <p class="text-rose-500 text-[10px] font-bold mt-0.5">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-50">
                            @if ($isEdit)
                                <button 
                                    type="button" 
                                    wire:click="resetForm" 
                                    class="flex-1 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-extrabold rounded-xl transition duration-150 shadow-sm text-center"
                                >
                                    Batal
                                </button>
                            @endif
                            <button 
                                type="submit" 
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition duration-150 shadow-md text-center"
                            >
                                {{ $isEdit ? 'Simpan' : 'Tambah Soal' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Right Panel: Database Soal & Filters (span 2) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Filter bar & Status Card -->
                <div class="bg-white rounded-3xl border border-gray-100 p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-0.5">
                        <h3 class="text-sm font-extrabold text-gray-900 leading-tight">Daftar Pertanyaan</h3>
                        <p class="text-[10px] text-gray-400 leading-normal">
                            Total: <span class="font-extrabold text-gray-700">{{ $questions->count() }} soal terdaftar</span>
                        </p>
                    </div>

                    <!-- Module Filter Dropdown Selector -->
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider whitespace-nowrap">Filter Modul:</span>
                        <select 
                            wire:model.live="filter_module_id" 
                            class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition text-[11px] font-bold py-1.5 px-3.5 bg-gray-50"
                        >
                            <option value="">Semua Modul Kuis</option>
                            @foreach ($quizModules as $module)
                                <option value="{{ $module->id }}">{{ $module->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Questions List Table/Cards -->
                @if ($questions->isEmpty())
                    <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
                        <span class="text-5xl block mb-4 filter drop-shadow-sm select-none">✍️</span>
                        <h3 class="text-base font-bold text-gray-800">Tidak ada soal yang ditemukan</h3>
                        <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto leading-relaxed">
                            Belum ada pertanyaan terdaftar. Silakan pilih modul kuis di formulir sebelah kiri dan buat soal pertama Anda.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($questions as $question)
                            <div class="group bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition duration-200 flex flex-col md:flex-row md:items-start justify-between gap-4 relative overflow-hidden">
                                
                                <!-- Core Content Info -->
                                <div class="flex items-start gap-4 flex-1">
                                    
                                    <!-- Thumbnail Area -->
                                    <div class="h-14 w-14 rounded-xl border border-gray-100 bg-gray-50 flex items-center justify-center shrink-0 overflow-hidden relative shadow-inner">
                                        @if ($question->image_path)
                                            <img src="{{ asset('storage/' . $question->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl select-none opacity-40">❓</span>
                                        @endif
                                    </div>

                                    <!-- Question Text details -->
                                    <div class="space-y-2 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-[9px] bg-indigo-50 text-indigo-700 font-extrabold px-2 py-0.5 rounded-full uppercase">
                                                {{ $question->quizModule->title }}
                                            </span>
                                        </div>
                                        <p class="text-xs font-bold text-gray-900 leading-relaxed pr-2">
                                            {{ $question->question_text }}
                                        </p>

                                        <!-- Options pills grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1.5 text-[10px]">
                                            @foreach (['A', 'B', 'C', 'D'] as $opt)
                                                @php
                                                    $optKey = strtolower($opt);
                                                    $isCorrect = $question->correct_option === $opt;
                                                @endphp
                                                <div class="flex items-center p-2 rounded-lg border {{ $isCorrect ? 'bg-emerald-50 border-emerald-200 text-emerald-800 font-extrabold shadow-sm' : 'bg-gray-50/50 border-gray-100 text-gray-500' }} leading-normal">
                                                    <span class="w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-black mr-2 uppercase {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                                        {{ $opt }}
                                                    </span>
                                                    <span class="line-clamp-1">{{ $question->{"option_" . $optKey} }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>

                                <!-- Action Buttons Control -->
                                <div class="flex md:flex-col items-center justify-end gap-3.5 border-t md:border-t-0 border-gray-50 pt-3 md:pt-0 shrink-0 select-none">
                                    <button 
                                        wire:click="edit({{ $question->id }})" 
                                        class="text-xs font-extrabold text-blue-600 hover:text-blue-800 transition"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="delete({{ $question->id }})" 
                                        onclick="confirm('Hapus soal ini?') || event.stopImmediatePropagation()"
                                        class="text-xs font-extrabold text-rose-600 hover:text-rose-800 transition"
                                    >
                                        Hapus
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
                
            </div>

        </div>
        
    </div>
</div>