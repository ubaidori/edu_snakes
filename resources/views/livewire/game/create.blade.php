<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Step-by-Step Navigation Indicator -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-5">
            <div class="space-y-1">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span>🎲</span> Mulai Petualangan Baru
                </h1>
                <p class="text-xs text-gray-500">Pilih topik kuis pembelajaran yang ingin Anda ujikan di papan permainan.</p>
            </div>
            
            <!-- Custom Pill Step Steps -->
            <div class="flex items-center gap-3 text-xs select-none">
                <div class="flex items-center gap-2 bg-blue-600 text-white font-extrabold px-3.5 py-1.5 rounded-full shadow-sm">
                    <span class="w-4.5 h-4.5 rounded-full bg-white text-blue-700 flex items-center justify-center font-black text-[10px]">1</span>
                    <span>Pilih Modul Kuis</span>
                </div>
                <span class="text-gray-300">&rarr;</span>
                <div class="flex items-center gap-2 bg-white text-gray-400 font-extrabold px-3.5 py-1.5 rounded-full border border-gray-100">
                    <span class="w-4.5 h-4.5 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center font-black text-[10px]">2</span>
                    <span>Konfigurasi Tim</span>
                </div>
            </div>
        </div>

        <!-- Validation & Warning Message -->
        @error('quiz_module_id')
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-2xl shadow-sm flex items-start gap-3">
                <span class="text-xl">🚨</span>
                <div>
                    <h4 class="font-bold text-sm">Gagal Memulai Sesi!</h4>
                    <p class="text-xs text-rose-700 mt-0.5">{{ $message }}</p>
                </div>
            </div>
        @enderror

        <!-- Grid Module Selector Lobby -->
        @if ($modules->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <span class="text-6xl block mb-4 filter drop-shadow-sm select-none">🧩</span>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada modul kuis aktif</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto leading-relaxed">
                    Anda harus memiliki minimal satu modul kuis dengan jumlah soal yang memenuhi batas minimum sebelum dapat memulai permainan.
                </p>
                <a 
                    href="{{ route('quiz-modules.create') }}" 
                    class="mt-5 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm"
                >
                    Buat Modul Kuis Baru
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($modules as $module)
                    @php
                        $count = $module->questions_count ?? 0;
                        $min = $module->minimum_questions;
                        $isEligible = $count >= $min;
                    @endphp
                    
                    @if ($isEligible)
                        <!-- Card: Eligible & Clickable Modul -->
                        <button 
                            type="button"
                            wire:click="selectModule({{ $module->id }})"
                            class="text-left w-full group bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-200 flex flex-col justify-between h-56 relative overflow-hidden"
                        >
                            <!-- Visual Glow Highlight border -->
                            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                            <div class="space-y-2">
                                <span class="text-[9px] bg-blue-50 text-blue-700 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                    {{ $count }} Soal Terdaftar
                                </span>
                                <h3 class="font-extrabold text-gray-900 text-lg leading-tight group-hover:text-blue-600 transition pt-1">
                                    {{ $module->title }}
                                </h3>
                                <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ $module->description ?: 'Tidak ada deskripsi modul.' }}
                                </p>
                            </div>

                            <div class="border-t border-gray-50 pt-4 flex items-center justify-between text-[11px] font-bold text-blue-600 w-full mt-4">
                                <span class="text-gray-400 font-normal">Min. {{ $min }} soal</span>
                                <span class="group-hover:translate-x-1.5 transition duration-200">Pilih Modul &rarr;</span>
                            </div>
                        </button>
                    @else
                        <!-- Card: Locked & Disabled Modul (Fewer questions than minimum) -->
                        <div class="text-left w-full bg-gray-100/50 rounded-2xl border border-dashed border-gray-200 p-6 flex flex-col justify-between h-56 relative overflow-hidden select-none opacity-70">
                            
                            <!-- Visual Lock Overlay Indicator -->
                            <div class="absolute right-4 top-4 text-gray-300 font-extrabold text-xl">🔒</div>

                            <div class="space-y-2">
                                <span class="text-[9px] bg-amber-100 text-amber-800 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                    {{ $count }} / {{ $min }} Soal
                                </span>
                                <h3 class="font-extrabold text-gray-500 text-lg leading-tight pt-1">
                                    {{ $module->title }}
                                </h3>
                                <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ $module->description ?: 'Tidak ada deskripsi modul.' }}
                                </p>
                            </div>

                            <!-- Alert status -->
                            <div class="border-t border-gray-200/60 pt-4 text-[10px] leading-tight text-amber-600 font-bold w-full mt-4 flex items-center justify-between">
                                <span>Butuh minimal {{ $min }} soal</span>
                                <a 
                                    href="{{ route('questions.index') }}" 
                                    class="text-[10px] bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 px-2 py-0.5 rounded font-extrabold transition"
                                >
                                    Tambah Soal
                                </a>
                            </div>
                        </div>
                    @endif

                @endforeach
            </div>
        @endif
        
    </div>
</div>