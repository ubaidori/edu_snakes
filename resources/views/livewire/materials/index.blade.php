<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Premium Header Area -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-10 -translate-y-6 opacity-10 text-9xl font-black select-none">📚</div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1.5">
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Bahan Ajar & Materi</h1>
                    <p class="text-blue-100 text-xs md:text-sm max-w-xl">
                        Kelola modul materi belajar interaktif dengan ilustrasi menarik sebelum siswa Anda mulai berpetualang di papan Ular Tangga.
                    </p>
                </div>
                <a
                    href="{{ route('materials.create') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-white hover:bg-blue-50 text-blue-700 text-xs md:text-sm font-extrabold rounded-xl transition duration-150 shadow-md group whitespace-nowrap self-start md:self-center"
                >
                    <span class="group-hover:scale-110 transition duration-150 mr-1.5">&#43;</span> Tambah Materi Baru
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
        @if ($materials->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <span class="text-6xl block mb-4 filter drop-shadow-sm select-none">📖</span>
                <h3 class="text-lg font-bold text-gray-800">Belum ada bahan ajar yang dibuat</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto leading-relaxed">
                    Daftar materi Anda masih kosong. Silakan tambahkan materi baru untuk melengkapi referensi belajar siswa.
                </p>
                <a 
                    href="{{ route('materials.create') }}" 
                    class="mt-5 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm"
                >
                    Buat Materi Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materials as $material)
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between overflow-hidden">
                        
                        <!-- Card Media Area -->
                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                            @if ($material->image_path)
                                <img 
                                    src="{{ asset('storage/' . $material->image_path) }}" 
                                    alt="{{ $material->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center relative select-none">
                                    <div class="absolute inset-0 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px] opacity-10"></div>
                                    <span class="text-5xl">📚</span>
                                </div>
                            @endif

                            <!-- Badges Overlay -->
                            <div class="absolute top-3 left-3 flex gap-2">
                                <span class="text-[10px] bg-slate-900/80 text-white font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                    Urutan: {{ $material->sort_order }}
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-sm border {{ $material->is_active ? 'bg-emerald-500/90 text-white border-emerald-300' : 'bg-slate-500/90 text-white border-slate-300' }} backdrop-blur-sm">
                                    {{ $material->is_active ? 'Aktif' : 'Draft' }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                            <div class="space-y-1.5">
                                <h3 class="font-extrabold text-gray-900 text-base leading-tight group-hover:text-blue-600 transition">
                                    {{ $material->title }}
                                </h3>
                                <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed">
                                    {{ strip_tags($material->content) }}
                                </p>
                            </div>

                            <div class="border-t border-gray-100 pt-4 flex items-center justify-between text-[11px] text-gray-400">
                                <span>Dibuat: {{ $material->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-3">
                                    <a
                                        href="{{ route('materials.edit', $material->id) }}"
                                        class="font-extrabold text-blue-600 hover:text-blue-800 transition"
                                    >
                                        Edit
                                    </a>
                                    <button
                                        wire:click="delete({{ $material->id }})"
                                        onclick="confirm('Apakah Anda yakin ingin menghapus materi ini?') || event.stopImmediatePropagation()"
                                        class="font-extrabold text-rose-600 hover:text-rose-800 transition"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
        
    </div>
</div>
