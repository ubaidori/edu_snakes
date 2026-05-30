<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <span>🏫</span> {{ __('Dashboard Instruktur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
                <div class="absolute right-0 top-0 translate-x-12 -translate-y-6 opacity-10 text-9xl font-black">🎲</div>
                <div class="relative z-10 space-y-2">
                    <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-blue-100 max-w-xl text-sm md:text-base leading-relaxed">
                        Kelola materi pembelajaran, buat kuis menarik, dan pantau jalannya permainan ular tangga edukatif Anda dengan mudah dari sini.
                    </p>
                </div>
            </div>

            <!-- Quick Action Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Action 1: Start Game -->
                <a href="{{ route('game.create') }}" class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between h-40">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl bg-green-50 p-2.5 rounded-xl group-hover:scale-105 transition">🎮</span>
                        <span class="text-xs text-green-700 font-semibold px-2 py-0.5 rounded-full bg-green-50">Main</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-base leading-tight">Mulai Game Baru</h4>
                        <p class="text-xs text-gray-500 mt-1">Luncurkan papan Ular Tangga baru bersama murid Anda.</p>
                    </div>
                </a>

                <!-- Action 2: Materials -->
                <a href="{{ route('materials.index') }}" class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between h-40">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl bg-blue-50 p-2.5 rounded-xl group-hover:scale-105 transition">📚</span>
                        <span class="text-xs text-blue-700 font-semibold px-2 py-0.5 rounded-full bg-blue-50">Belajar</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-base leading-tight">Kelola Bahan Ajar</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat & edit modul materi ajar interaktif.</p>
                    </div>
                </a>

                <!-- Action 3: Quiz Modules -->
                <a href="{{ route('quiz-modules.index') }}" class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between h-40">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl bg-purple-50 p-2.5 rounded-xl group-hover:scale-105 transition">🧩</span>
                        <span class="text-xs text-purple-700 font-semibold px-2 py-0.5 rounded-full bg-purple-50">Kuis</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-base leading-tight">Kelola Modul Kuis</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat paket kuis untuk topik pembelajaran tertentu.</p>
                    </div>
                </a>

                <!-- Action 4: Questions -->
                <a href="{{ route('questions.index') }}" class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between h-40">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl bg-amber-50 p-2.5 rounded-xl group-hover:scale-105 transition">✍️</span>
                        <span class="text-xs text-amber-700 font-semibold px-2 py-0.5 rounded-full bg-amber-50">Soal</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-base leading-tight">Kelola Bank Soal</h4>
                        <p class="text-xs text-gray-500 mt-1">Kelola daftar pertanyaan pilihan ganda Anda.</p>
                    </div>
                </a>
            </div>

            <!-- Main Grid Section: Left (span 3) and Right (span 1) -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Left: Active Sesis & Game History (span 3) -->
                <div class="lg:col-span-3 space-y-8">
                    
                    <!-- Active Sessions Card -->
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Sesi Game yang Sedang Aktif</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Lanjutkan sesi permainan ular tangga yang belum diselesaikan.</p>
                            </div>
                            <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-bold">
                                {{ $activeSessions->count() }} Game Aktif
                            </span>
                        </div>

                        @if ($activeSessions->isEmpty())
                            <!-- Empty State -->
                            <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-2xl">
                                <span class="text-4xl block mb-2">🎲</span>
                                <h4 class="font-bold text-gray-700 text-sm">Tidak ada sesi permainan aktif</h4>
                                <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto">
                                    Semua permainan telah selesai atau Anda belum memulai game apa pun.
                                </p>
                                <a href="{{ route('game.create') }}" class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                    Mulai Game Baru
                                </a>
                            </div>
                        @else
                            <!-- Active Sessions List -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($activeSessions as $session)
                                    @php
                                        $playersCount = is_array($session->players) ? count($session->players) : 0;
                                        $activePlayerIndex = $session->active_player_index ?? 0;
                                        $activePlayerName = is_array($session->players) && isset($session->players[$activePlayerIndex]) 
                                            ? $session->players[$activePlayerIndex]['name'] 
                                            : '-';
                                    @endphp
                                    <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-gray-100/50 hover:shadow-sm transition duration-200 flex flex-col justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-extrabold text-gray-900 text-base leading-tight">
                                                    {{ $session->quizModule->title }}
                                                </h4>
                                                <span class="text-[10px] bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                                    Playing
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 leading-normal">
                                                {{ Str::limit($session->quizModule->description, 80) }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-200/60 py-3 text-xs">
                                            <div>
                                                <span class="text-gray-400 block mb-0.5">Jumlah Pemain</span>
                                                <span class="font-bold text-gray-800">{{ $playersCount }} Pemain/Tim</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 block mb-0.5">Giliran Aktif</span>
                                                <span class="font-bold text-gray-800">{{ $activePlayerName }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between gap-4">
                                            <span class="text-[10px] text-gray-400">
                                                Dimulai: {{ $session->created_at->diffForHumans() }}
                                            </span>
                                            <a href="{{ route('game.play', $session->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                Lanjutkan &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Game History Card -->
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Riwayat Permainan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Daftar sesi permainan yang telah diselesaikan.</p>
                            </div>
                            <span class="bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-full font-bold">
                                {{ $finishedSessions->count() }} Selesai
                            </span>
                        </div>

                        @if ($finishedSessions->isEmpty())
                            <div class="text-center py-10 border-2 border-dashed border-gray-50 rounded-2xl text-gray-400 text-xs">
                                📜 Belum ada riwayat permainan yang diselesaikan.
                            </div>
                        @else
                            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto pr-2">
                                @foreach ($finishedSessions as $session)
                                    @php
                                        $players = $session->players;
                                        $winnerName = null;
                                        if ($session->winner_player_index !== null && is_array($players)) {
                                            $winnerName = $players[$session->winner_player_index]['name'] ?? null;
                                        }
                                        if (!$winnerName && is_array($players)) {
                                            foreach ($players as $p) {
                                                if (($p['position'] ?? 0) >= 100) {
                                                    $winnerName = $p['name'];
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                        <div class="space-y-0.5">
                                            <h4 class="font-extrabold text-gray-800 text-sm leading-snug">
                                                {{ $session->quizModule->title }}
                                            </h4>
                                            <p class="text-[11px] text-gray-500 leading-normal">
                                                Pemenang: <span class="font-extrabold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $winnerName ?? 'Tidak diketahui' }}</span>
                                                <span class="mx-1.5 opacity-60">|</span>
                                                Selesai: {{ $session->finished_at ? $session->finished_at->format('d M Y, H:i') : $session->updated_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] bg-emerald-50 text-emerald-700 font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm border border-emerald-100">
                                            Success
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right: Leaderboard (span 1) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-6">
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-1.5">
                                <span>🏆</span> Papan Peringkat
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Top 5 tim berdasarkan total kemenangan.</p>
                        </div>

                        @if (empty($leaderboard))
                            <div class="text-center py-10 border-2 border-dashed border-gray-50 rounded-2xl text-gray-400 text-xs">
                                👑 Belum ada skor tercatat.
                            </div>
                        @else
                            <div class="space-y-3.5">
                                @foreach ($leaderboard as $rank => $entry)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition duration-150 shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <!-- Medal badge colors -->
                                            <span class="w-6 h-6 rounded-full flex items-center justify-center font-extrabold text-xs shadow-sm
                                                {{ $rank == 0 ? 'bg-amber-100 text-amber-800 border border-amber-300 ring-2 ring-amber-100' : '' }}
                                                {{ $rank == 1 ? 'bg-slate-200 text-slate-800 border border-slate-300 ring-2 ring-slate-100' : '' }}
                                                {{ $rank == 2 ? 'bg-orange-100 text-orange-800 border border-orange-200 ring-2 ring-orange-100' : '' }}
                                                {{ $rank > 2 ? 'bg-white text-gray-600 border border-gray-200' : '' }}">
                                                {{ $rank + 1 }}
                                            </span>
                                            <div>
                                                <span class="font-extrabold text-sm text-gray-800 block leading-tight">{{ $entry['name'] }}</span>
                                                <span class="text-[9px] text-gray-400 leading-none">Rasio: <span class="font-bold text-gray-600">{{ $entry['wins'] }}/{{ $entry['games_played'] }}</span></span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-black text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-lg shadow-sm">
                                                {{ $entry['wins'] }} Win
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
