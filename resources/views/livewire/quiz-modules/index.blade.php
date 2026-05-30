<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Premium Header Area -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-10 -translate-y-6 opacity-10 text-9xl font-black select-none">🧩</div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1.5">
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Modul Kuis Permainan</h1>
                    <p class="text-purple-100 text-xs md:text-sm max-w-xl">
                        Buat paket modul kuis, kelola pertanyaan pilihan ganda, dan pantau kelayakan soal agar siap digunakan oleh siswa Anda di papan permainan.
                    </p>
                </div>
                <a
                    href="{{ route('quiz-modules.create') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-white hover:bg-purple-50 text-purple-700 text-xs md:text-sm font-extrabold rounded-xl transition duration-150 shadow-md group whitespace-nowrap self-start md:self-center"
                >
                    <span class="group-hover:scale-110 transition duration-150 mr-1.5">&#43;</span> Buat Modul Kuis
                </a>
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

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-2xl shadow-sm flex items-start gap-3">
                <span class="text-xl">🚨</span>
                <div>
                    <h4 class="font-bold text-sm">Terjadi Kesalahan!</h4>
                    <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Card Grid Listing -->
        @if ($quizModules->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <span class="text-6xl block mb-4 filter drop-shadow-sm select-none">🧩</span>
                <h3 class="text-lg font-bold text-gray-800">Belum ada modul kuis yang dibuat</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto leading-relaxed">
                    Anda belum memiliki modul kuis aktif. Mulailah dengan membuat kuis pembelajaran pertama untuk menghubungkan soal dengan game ular tangga.
                </p>
                <a 
                    href="{{ route('quiz-modules.create') }}" 
                    class="mt-5 inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition shadow-sm"
                >
                    Buat Kuis Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($quizModules as $module)
                    @php
                        $count = $module->questions_count ?? 0;
                        $min = $module->minimum_questions;
                        $percent = $min > 0 ? min(100, round(($count / $min) * 100)) : 0;
                        $isReady = $count >= $min;
                    @endphp
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between overflow-hidden relative">
                        
                        <!-- Top Accent Bar -->
                        <div class="h-1.5 w-full {{ $isReady ? 'bg-emerald-500' : 'bg-amber-400' }}"></div>

                        <div class="p-6 flex-1 flex flex-col justify-between gap-5">
                            
                            <!-- Card Info Details -->
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-extrabold text-gray-900 text-base leading-tight group-hover:text-purple-600 transition">
                                        {{ $module->title }}
                                    </h3>
                                    <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm border {{ $module->is_active ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-gray-100 text-gray-500 border-gray-200' }} shrink-0">
                                        {{ $module->is_active ? 'Aktif' : 'Draft' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed">
                                    {{ $module->description ?: 'Tidak ada deskripsi modul kuis ini.' }}
                                </p>
                            </div>

                            <!-- Questions Coverage Progress Indicator -->
                            <div class="space-y-2 border-t border-b border-gray-50 py-4">
                                <div class="flex items-center justify-between text-[11px] font-bold">
                                    <span class="text-gray-400 uppercase tracking-wider">Cakupan Soal</span>
                                    <span class="{{ $isReady ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $count }} / {{ $min }} Soal
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div 
                                        class="h-full rounded-full transition-all duration-500 {{ $isReady ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : 'bg-gradient-to-r from-amber-400 to-amber-500' }}"
                                        style="width: {{ $percent }}%"
                                    ></div>
                                </div>
                                <div class="flex items-center justify-between text-[10px] leading-tight">
                                    @if ($isReady)
                                        <span class="text-emerald-600 flex items-center gap-1 font-bold">
                                            <span>✔️</span> Layap Main (Ready to Play)
                                        </span>
                                    @else
                                        <span class="text-amber-600 flex items-center gap-1 font-bold">
                                            <span>⚠️</span> Butuh {{ $min - $count }} soal lagi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Action Buttons -->
                            <div class="flex flex-col gap-2.5">
                                <a
                                    href="{{ route('questions.index') }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-purple-100 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-extrabold rounded-xl transition duration-150 shadow-sm"
                                >
                                    ✍️ Kelola Bank Soal
                                </a>
                                
                                <div class="flex items-center justify-between text-[11px] text-gray-400 border-t border-gray-50 pt-3">
                                    <span>Tipe: Edu-Snakes</span>
                                    <div class="flex items-center gap-3">
                                        <a
                                            href="{{ route('quiz-modules.edit', $module->id) }}"
                                            class="font-extrabold text-blue-600 hover:text-blue-800 transition"
                                        >
                                            Edit
                                        </a>
                                        <button
                                            wire:click="delete({{ $module->id }})"
                                            onclick="confirm('Apakah Anda yakin ingin menghapus modul kuis ini beserta semua soal yang berkaitan?') || event.stopImmediatePropagation()"
                                            class="font-extrabold text-rose-600 hover:text-rose-800 transition"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        @endif
        
    </div>
</div>