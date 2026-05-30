<div class="py-8 bg-gray-50/50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Step-by-Step Navigation Indicator -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-5">
            <div class="space-y-1">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span>⚙️</span> Pengaturan Permainan
                </h1>
                <p class="text-xs text-gray-500">Tentukan jumlah tim dan daftarkan nama-nama kelompok pemain Anda.</p>
            </div>
            
            <!-- Custom Pill Step Steps -->
            <div class="flex items-center gap-3 text-xs select-none">
                <a 
                    href="{{ route('game.create') }}"
                    class="flex items-center gap-2 bg-white text-gray-400 font-extrabold px-3.5 py-1.5 rounded-full border border-gray-100 hover:bg-gray-50 transition"
                >
                    <span class="w-4.5 h-4.5 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center font-black text-[10px]">&larr;</span>
                    <span>Modul Kuis</span>
                </a>
                <span class="text-gray-300">&rarr;</span>
                <div class="flex items-center gap-2 bg-green-600 text-white font-extrabold px-3.5 py-1.5 rounded-full shadow-sm">
                    <span class="w-4.5 h-4.5 rounded-full bg-white text-green-700 flex items-center justify-center font-black text-[10px]">2</span>
                    <span>Konfigurasi Tim</span>
                </div>
            </div>
        </div>

        <!-- Layout Grid: Context on Left, Setup Form on Right -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Left: Module Context Card (span 1) -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-green-500"></div>
                    <div class="space-y-4">
                        <span class="text-[9px] bg-green-50 text-green-700 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Modul Terpilih
                        </span>
                        
                        <div class="space-y-1">
                            <h3 class="font-extrabold text-gray-900 text-lg leading-tight">
                                {{ $module->title }}
                            </h3>
                            <p class="text-[11px] text-gray-400">
                                Total: {{ $module->questions_count }} Soal Terdaftar
                            </p>
                        </div>

                        <p class="text-xs text-gray-500 leading-relaxed border-t border-gray-50 pt-3">
                            {{ $module->description ?: 'Tidak ada deskripsi modul kuis terpilih.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right: Setup configuration form (span 2) -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-sm">
                    <form wire:submit="start" class="space-y-6">
                        
                        <!-- Segmented Pill Player Count Selector -->
                        <div class="space-y-3">
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider">Jumlah Pemain / Kelompok Tim</label>
                            
                            <div class="grid grid-cols-5 gap-2 bg-gray-50 p-1.5 rounded-2xl border border-gray-100 select-none">
                                @for ($i = 2; $i <= 6; $i++)
                                    <button
                                        type="button"
                                        wire:click="setPlayerCount({{ $i }})"
                                        class="py-2.5 rounded-xl text-center text-xs font-extrabold transition duration-150
                                            {{ $player_count == $i 
                                                ? 'bg-green-600 text-white shadow-sm' 
                                                : 'text-gray-500 hover:bg-gray-100/50 hover:text-gray-700' }}"
                                    >
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                            @error('player_count')
                                <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dynamic Player Input Cards with designated colors -->
                        @php
                            $colors = [
                                0 => ['bg' => 'bg-red-500', 'border' => 'border-red-200/80 shadow-red-50', 'text' => 'text-red-700', 'label' => 'Tim Merah'],
                                1 => ['bg' => 'bg-blue-500', 'border' => 'border-blue-200/80 shadow-blue-50', 'text' => 'text-blue-700', 'label' => 'Tim Biru'],
                                2 => ['bg' => 'bg-green-500', 'border' => 'border-green-200/80 shadow-green-50', 'text' => 'text-green-700', 'label' => 'Tim Hijau'],
                                3 => ['bg' => 'bg-yellow-500', 'border' => 'border-yellow-300/80 shadow-yellow-50', 'text' => 'text-yellow-700', 'label' => 'Tim Kuning'],
                                4 => ['bg' => 'bg-purple-500', 'border' => 'border-purple-200/80 shadow-purple-50', 'text' => 'text-purple-700', 'label' => 'Tim Ungu'],
                                5 => ['bg' => 'bg-pink-500', 'border' => 'border-pink-200/80 shadow-pink-50', 'text' => 'text-pink-700', 'label' => 'Tim Merah Muda'],
                            ];
                        @endphp

                        <div class="space-y-4 pt-2 border-t border-gray-50">
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Nama Kelompok / Tim Pemain</label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($players as $index => $player)
                                    @php
                                        $c = $colors[$index] ?? ['bg' => 'bg-slate-500', 'border' => 'border-slate-200', 'text' => 'text-slate-700', 'label' => 'Tim'];
                                    @endphp
                                    <div class="p-4 rounded-2xl border bg-white shadow-sm flex items-center gap-3.5 {{ $c['border'] }}">
                                        <!-- Color Token indicator icon -->
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 shadow-inner select-none {{ $c['bg'] }}">
                                            <span class="text-white text-sm">♟️</span>
                                        </div>
                                        
                                        <div class="flex-1 space-y-1">
                                            <label class="block text-[10px] font-extrabold uppercase tracking-wider {{ $c['text'] }}">
                                                {{ $c['label'] }}
                                            </label>
                                            <input
                                                type="text"
                                                wire:model="players.{{ $index }}.name"
                                                class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring focus:ring-green-100 transition duration-150 text-xs py-1.5 px-3 placeholder-gray-400"
                                                placeholder="Contoh: {{ $c['label'] }}"
                                            >
                                            @error('players.' . $index . '.name')
                                                <p class="text-rose-500 text-[9px] font-bold mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Start Adventure Massive Glowing Button -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-50 mt-4 select-none">
                            <a
                                href="{{ route('game.create') }}"
                                class="px-4 py-2.5 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-extrabold rounded-xl transition duration-150 shadow-sm"
                            >
                                &larr; Ganti Kuis
                            </a>
                            
                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 hover:shadow-lg text-white text-xs font-black rounded-xl transition duration-150 shadow-md transform hover:-translate-y-0.5 flex items-center gap-1.5"
                            >
                                Mulai Petualangan <span>🚀</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
        
    </div>
</div>