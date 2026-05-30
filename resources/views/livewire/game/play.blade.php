<div class="space-y-6 py-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                <span>🎲</span> Edu-Snakes Game Board
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Modul Kuis: <span class="font-semibold text-blue-600">{{ $session->quizModule->title }}</span> | Status: 
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $session->status === 'finished' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($session->status) }}
                </span>
            </p>
        </div>

        <a href="{{ route('game.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition shadow-sm text-sm">
            ← Game Baru
        </a>
    </div>

    <!-- Alert Message -->
    @if ($message)
        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-xl shadow-sm text-sm font-medium animate-pulse">
            📢 {{ $message }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Game Board (Left Column) -->
        <div class="lg:col-span-3"
             x-data="gameBoard()"
             x-init="initBoard()"
             @dice-rolled.window="dice = $event.detail.dice"
             @players-updated.window="onPlayersUpdated($event.detail)">
            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <!-- Board Grid -->
                <div class="grid grid-cols-10 gap-2 bg-gray-200 p-3 rounded-2xl shadow-inner select-none relative" id="board-grid" x-ref="boardGrid">
                    @foreach ($cells as $cell)
                        @php
                            $isSnakeHead = isset($snakes[$cell]);
                            $isSnakeTail = in_array($cell, $snakes);
                            $isLadderStart = isset($ladders[$cell]);
                            $isLadderEnd = in_array($cell, $ladders);

                            $bgClass = 'bg-white hover:bg-gray-50 text-gray-700';
                            $borderClass = 'border-gray-200';
                            $specialLabel = '';

                            if ($isSnakeHead) {
                                $bgClass = 'bg-red-50 hover:bg-red-100 text-red-900';
                                $borderClass = 'border-red-300 ring-2 ring-red-100';
                                $specialLabel = '🐍 ' . $snakes[$cell];
                            } elseif ($isSnakeTail) {
                                $bgClass = 'bg-orange-50/50 hover:bg-orange-50 text-orange-900';
                                $borderClass = 'border-orange-200';
                                $specialLabel = 'tail';
                            } elseif ($isLadderStart) {
                                $bgClass = 'bg-emerald-50 hover:bg-emerald-100 text-emerald-900';
                                $borderClass = 'border-emerald-300 ring-2 ring-emerald-100';
                                $specialLabel = '🪜 ' . $ladders[$cell];
                            } elseif ($isLadderEnd) {
                                $bgClass = 'bg-teal-50/50 hover:bg-teal-50 text-teal-900';
                                $borderClass = 'border-teal-200';
                                $specialLabel = 'end';
                            } elseif ($cell === 100) {
                                $bgClass = 'bg-gradient-to-br from-amber-100 to-yellow-200 hover:from-amber-200 hover:to-yellow-300 text-amber-900';
                                $borderClass = 'border-amber-400 font-extrabold ring-4 ring-amber-200';
                                $specialLabel = '🏆 FINISH';
                            } elseif ($cell === 1) {
                                $bgClass = 'bg-gradient-to-br from-blue-100 to-indigo-200 hover:from-blue-200 hover:to-indigo-300 text-blue-900';
                                $borderClass = 'border-blue-400 font-extrabold ring-4 ring-blue-200';
                                $specialLabel = '🚀 START';
                            }
                        @endphp

                        <div id="cell-{{ $cell }}" class="relative h-20 border-2 {{ $borderClass }} {{ $bgClass }} rounded-xl flex flex-col justify-between p-2 shadow-sm transition duration-200">
                            <!-- Cell Number and Label -->
                            <div class="flex items-center justify-between w-full">
                                <span class="font-extrabold text-sm opacity-90">{{ $cell }}</span>
                                
                                @if ($specialLabel && $specialLabel !== 'tail' && $specialLabel !== 'end')
                                    <span class="text-[10px] font-extrabold tracking-wide uppercase px-1 rounded bg-black/5 text-black">
                                        {{ $specialLabel }}
                                    </span>
                                @endif
                            </div>

                            <!-- Snake/Ladder indicator hints -->
                            @if ($isSnakeTail)
                                <div class="text-[9px] text-orange-600 font-semibold text-center leading-none">
                                    Tail of 🐍
                                </div>
                            @elseif ($isLadderEnd)
                                <div class="text-[9px] text-emerald-600 font-semibold text-center leading-none">
                                    Top of 🪜
                                </div>
                            @endif

                            <!-- Player Tokens inside Cell (Handled dynamically by Alpine absolute overlay) -->
                            <div class="w-full min-h-[24px]"></div>
                        </div>
                    @endforeach

                    <!-- Dynamic Premium SVG Snakes & Ladders Overlay -->
                    <svg class="absolute inset-0 w-full h-full pointer-events-none z-10" id="board-svg-overlay" x-html="svgContent">
                    </svg>

                    <!-- Absolute Floating Player Tokens Overlay -->
                    <template x-for="(player, index) in clientPlayers" :key="index">
                        <div 
                            class="absolute w-6 h-6 rounded-full text-white flex items-center justify-center text-[10px] font-black shadow-md border border-white select-none pointer-events-none"
                            :style="(tokenStyles[index] || 'display: none;') + (jumpingPlayerIndex === index ? ' transform: scale(1.35) translateY(-18px); z-index: 40;' : ' transform: scale(1); z-index: 20;') + ' transition: top 240ms ease-out, left 240ms ease-out, transform 180ms ease-out;'"
                            x-text="index + 1"
                            :title="player.name"
                            :class="index === currentPlayerIndex && status !== 'finished' ? 'ring-2 ring-yellow-400 animate-bounce' : ''"
                        >
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Sidebar / Game Controls (Right Column) -->
        <div class="space-y-6">
            <!-- Current Turn & Roll Dice -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-gray-500 uppercase tracking-wider text-xs mb-4">Giliran Pemain</h2>

                @if ($session->status === 'finished')
                    <div class="text-center py-4">
                        <p class="text-emerald-600 font-extrabold text-lg flex items-center justify-center gap-1">
                            🎉 Game Selesai
                        </p>
                    </div>
                @else
                    @php
                        $activePlayer = $players[$current_player_index] ?? null;
                    @endphp
                    @if ($activePlayer)
                        <div class="p-4 rounded-xl border border-gray-100 flex items-center gap-3 shadow-inner bg-gray-50">
                            <span class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold"
                                  style="{{ $playerColors[$current_player_index] ?? 'background-color: #6b7280;' }}">
                                {{ $current_player_index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm text-gray-500">Pemain Aktif:</p>
                                <p class="font-bold text-gray-800 text-lg leading-tight">{{ $activePlayer['name'] }}</p>
                            </div>
                        </div>

                        <!-- Original Roll Dice Button -->
                        <button
                            wire:click="rollDice"
                            @disabled($showQuestionModal)
                            class="mt-4 w-full px-6 py-4 rounded-xl font-extrabold text-white transition shadow-md duration-200 transform hover:-translate-y-0.5 active:translate-y-0
                                {{ $showQuestionModal ? 'bg-gray-300 shadow-none cursor-not-allowed' : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 hover:shadow-lg' }}">
                            🎲 Lempar Dadu
                        </button>
                    @endif
                @endif

                <!-- Beautiful CSS-rendered Dice -->
                @if ($dice)
                    <div class="mt-6 text-center border-t border-gray-100 pt-6">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Hasil Dadu</p>
                        @php
                            $diceDots = [
                                1 => [5],
                                2 => [1, 9],
                                3 => [1, 5, 9],
                                4 => [1, 3, 7, 9],
                                5 => [1, 3, 5, 7, 9],
                                6 => [1, 3, 4, 6, 7, 9],
                            ][$dice] ?? [];
                        @endphp
                        
                        <!-- Premium Styled Dice face -->
                        <div class="w-20 h-20 bg-gradient-to-b from-white to-gray-50 border-2 border-gray-200 rounded-2xl shadow-lg mx-auto flex items-center justify-center p-3.5 transform hover:rotate-6 transition duration-300">
                            <div class="grid grid-cols-3 grid-rows-3 gap-2 w-full h-full">
                                @for ($i = 1; $i <= 9; $i++)
                                    <div class="flex items-center justify-center">
                                        @if (in_array($i, $diceDots))
                                            <div class="w-3 h-3 bg-gray-900 rounded-full shadow-inner"></div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- List of Players -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-gray-500 uppercase tracking-wider text-xs mb-4">Daftar Pemain / Tim</h2>

                <div class="space-y-3">
                    @foreach ($players as $index => $player)
                        @php
                            $isActive = ($index === $current_player_index && $session->status !== 'finished');
                        @endphp
                        <div class="p-3 rounded-xl border flex items-center justify-between transition duration-200
                            {{ $isActive ? 'bg-gradient-to-r from-blue-50 to-indigo-50/50 border-blue-200 shadow-sm' : 'bg-white border-gray-100' }}">

                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full text-white flex items-center justify-center text-xs font-bold"
                                      style="{{ $playerColors[$index] ?? 'background-color: #6b7280;' }}">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block leading-tight">{{ $player['name'] }}</span>
                                    <span class="text-[10px] text-gray-500">Posisi Saat Ini: <span class="font-semibold text-gray-700">{{ $player['position'] }}</span></span>
                                </div>
                            </div>

                            @if ($isActive)
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Game Logs -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-gray-500 uppercase tracking-wider text-xs mb-4">Aktivitas Terakhir</h2>

                @if (count($logs) === 0)
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada aktivitas permainan.</p>
                @else
                    <ul class="space-y-2.5">
                        @foreach ($logs as $log)
                            <li class="p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-xs text-gray-600 leading-normal flex items-start gap-2 shadow-sm">
                                <span class="mt-0.5 text-gray-400">⚡</span>
                                <span>{{ $log }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

        <!-- Question Modal with Livewire/Alpine Countdown Timer & Delayed Feedback -->
        @if ($showQuestionModal && $currentQuestion)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in"
                 x-data="{
                     timeLeft: {{ $currentQuestion->timer_seconds ?? 30 }},
                     totalTime: {{ $currentQuestion->timer_seconds ?? 30 }},
                     selected: null,
                     correct: '{{ $currentQuestion->correct_option }}',
                     answered: false,
                     timerInterval: null,
                     init() {
                         this.timerInterval = setInterval(() => {
                             if (!this.answered) {
                                 if (this.timeLeft > 0) {
                                     this.timeLeft--;
                                 } else {
                                     clearInterval(this.timerInterval);
                                     this.timeOut();
                                 }
                             } else {
                                 clearInterval(this.timerInterval);
                             }
                         }, 1000);
                     },
                     timeOut() {
                         this.selected = 'TIMEOUT';
                         this.answered = true;
                         setTimeout(() => {
                             $wire.answerQuestion(null);
                         }, 1500);
                     },
                     selectAnswer(opt) {
                         if (!this.answered) {
                             this.selected = opt;
                             this.answered = true;
                             clearInterval(this.timerInterval);
                             setTimeout(() => {
                                 $wire.answerQuestion(opt);
                             }, 1500);
                         }
                     }
                 }">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-100">
                    <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <span>❓</span> Pertanyaan Kuis
                        </h2>
                        
                        <div class="flex items-center gap-3">
                            <!-- Visual Timer Progress Bar -->
                            <div class="w-24 bg-white/20 h-2 rounded-full overflow-hidden hidden md:block">
                                <div class="bg-yellow-400 h-full transition-all duration-1000"
                                     :style="'width: ' + (timeLeft / totalTime * 100) + '%'"></div>
                            </div>
                            
                            <!-- Numerical countdown tag -->
                            <div class="flex items-center gap-1 bg-white/20 px-3 py-1 rounded-full text-xs font-bold transition duration-300"
                                 :class="timeLeft <= 5 ? 'text-red-100 bg-red-600 animate-pulse' : ''">
                                <span>⏰</span>
                                <span x-text="timeLeft + 's'"></span>
                            </div>

                            <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-semibold">
                                Poin: {{ $currentQuestion->points ?? 10 }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Question Image (If available) -->
                        @if ($currentQuestion->image_path)
                            <div class="flex justify-center bg-gray-50 p-3 rounded-2xl border border-gray-100 max-h-60 overflow-hidden">
                                <img src="{{ asset('storage/' . $currentQuestion->image_path) }}" class="max-h-52 w-auto rounded-lg object-contain shadow-sm">
                            </div>
                        @endif

                        <!-- Question Text -->
                        <div class="text-lg font-bold text-gray-800 leading-relaxed text-center py-2 px-4 bg-blue-50/50 rounded-xl">
                            {{ $currentQuestion->question_text }}
                        </div>

                        <!-- Timeout Warning Banner -->
                        <div x-show="answered && selected === 'TIMEOUT'" x-transition class="p-3 bg-red-100 border border-red-200 text-red-800 rounded-xl text-center font-extrabold animate-bounce text-sm">
                            ⏰ WAKTU HABIS! Bidak Anda akan mundur 1 langkah.
                        </div>

                        <!-- Answers Options -->
                        <div class="space-y-3">
                            <!-- Option A -->
                            <button
                                @click="selectAnswer('A')"
                                :class="{
                                    'bg-green-100 border-green-500 text-green-800 font-extrabold ring-2 ring-green-400': (answered && correct === 'A'),
                                    'bg-red-100 border-red-500 text-red-800 font-extrabold ring-2 ring-red-400': (answered && selected === 'A' && correct !== 'A'),
                                    'hover:bg-gray-50': !answered
                                }"
                                :disabled="answered"
                                class="w-full text-left p-4 border border-gray-200 rounded-xl transition duration-200 flex items-center justify-between text-sm md:text-base">
                                <span>A. {{ $currentQuestion->option_a }}</span>
                                <span x-show="answered && correct === 'A'" class="text-green-600 font-bold flex items-center gap-1">✓ Benar</span>
                                <span x-show="answered && selected === 'A' && correct !== 'A'" class="text-red-600 font-bold flex items-center gap-1">✗ Salah</span>
                            </button>

                            <!-- Option B -->
                            <button
                                @click="selectAnswer('B')"
                                :class="{
                                    'bg-green-100 border-green-500 text-green-800 font-extrabold ring-2 ring-green-400': (answered && correct === 'B'),
                                    'bg-red-100 border-red-500 text-red-800 font-extrabold ring-2 ring-red-400': (answered && selected === 'B' && correct !== 'B'),
                                    'hover:bg-gray-50': !answered
                                }"
                                :disabled="answered"
                                class="w-full text-left p-4 border border-gray-200 rounded-xl transition duration-200 flex items-center justify-between text-sm md:text-base">
                                <span>B. {{ $currentQuestion->option_b }}</span>
                                <span x-show="answered && correct === 'B'" class="text-green-600 font-bold flex items-center gap-1">✓ Benar</span>
                                <span x-show="answered && selected === 'B' && correct !== 'B'" class="text-red-600 font-bold flex items-center gap-1">✗ Salah</span>
                            </button>

                            <!-- Option C -->
                            <button
                                @click="selectAnswer('C')"
                                :class="{
                                    'bg-green-100 border-green-500 text-green-800 font-extrabold ring-2 ring-green-400': (answered && correct === 'C'),
                                    'bg-red-100 border-red-500 text-red-800 font-extrabold ring-2 ring-red-400': (answered && selected === 'C' && correct !== 'C'),
                                    'hover:bg-gray-50': !answered
                                }"
                                :disabled="answered"
                                class="w-full text-left p-4 border border-gray-200 rounded-xl transition duration-200 flex items-center justify-between text-sm md:text-base">
                                <span>C. {{ $currentQuestion->option_c }}</span>
                                <span x-show="answered && correct === 'C'" class="text-green-600 font-bold flex items-center gap-1">✓ Benar</span>
                                <span x-show="answered && selected === 'C' && correct !== 'C'" class="text-red-600 font-bold flex items-center gap-1">✗ Salah</span>
                            </button>

                            <!-- Option D -->
                            <button
                                @click="selectAnswer('D')"
                                :class="{
                                    'bg-green-100 border-green-500 text-green-800 font-extrabold ring-2 ring-green-400': (answered && correct === 'D'),
                                    'bg-red-100 border-red-500 text-red-800 font-extrabold ring-2 ring-red-400': (answered && selected === 'D' && correct !== 'D'),
                                    'hover:bg-gray-50': !answered
                                }"
                                :disabled="answered"
                                class="w-full text-left p-4 border border-gray-200 rounded-xl transition duration-200 flex items-center justify-between text-sm md:text-base">
                                <span>D. {{ $currentQuestion->option_d }}</span>
                                <span x-show="answered && correct === 'D'" class="text-green-600 font-bold flex items-center gap-1">✓ Benar</span>
                                <span x-show="answered && selected === 'D' && correct !== 'D'" class="text-red-600 font-bold flex items-center gap-1">✗ Salah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    <!-- Winner Modal (With Auto Confetti Trigger via Alpine x-init) -->
    @if ($showWinnerModal)
        <div x-data x-init="
            var duration = 4 * 1000;
            var end = Date.now() + duration;
            (function frame() {
                confetti({
                    particleCount: 4,
                    angle: 60,
                    spread: 60,
                    origin: { x: 0, y: 0.7 }
                });
                confetti({
                    particleCount: 4,
                    angle: 120,
                    spread: 60,
                    origin: { x: 1, y: 0.7 }
                });
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        " class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md text-center border border-gray-100 transform scale-100 transition duration-300">
                <div class="text-7xl mb-4 animate-bounce">🏆</div>
                
                <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Pemenang!</h2>
                <p class="text-sm text-gray-500 uppercase tracking-widest font-semibold mb-6">Edu-Snakes Champion</p>
                
                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 mb-8 inline-block px-8">
                    <p class="text-2xl font-black text-emerald-800 tracking-wide">{{ $winner }}</p>
                </div>

                <div class="flex gap-3 justify-center">
                    <a href="{{ route('game.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl font-bold shadow-md hover:shadow-lg transition">
                        Main Lagi
                    </a>
                    
                    <button wire:click="$set('showWinnerModal', false)" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('gameBoard', () => ({
        clientPlayers: @json($players),
        currentPlayerIndex: @json($current_player_index),
        status: '{{ $session->status }}',
        tokenStyles: [],
        jumpingPlayerIndex: null,
        isAnimating: false,
        dice: @json($dice),
        snakes: @json($snakes),
        ladders: @json($ladders),
        svgContent: '',
        
        initBoard() {
            // Observe container resize to reposition tokens
            const boardGrid = document.getElementById('board-grid');
            if (boardGrid) {
                const resizeObserver = new ResizeObserver(() => {
                    this.recalculateStyles();
                });
                resizeObserver.observe(boardGrid);
            }
            
            // Initial positioning with multiple retry timeouts to ensure page is fully rendered
            this.recalculateStyles();
            setTimeout(() => this.recalculateStyles(), 100);
            setTimeout(() => this.recalculateStyles(), 350);
            setTimeout(() => this.recalculateStyles(), 700);
        },
        
        onPlayersUpdated(detail) {
            this.currentPlayerIndex = detail.activePlayerIndex;
            if (detail.players) {
                this.animateMovements(detail.players, detail.isCorrect);
            }
        },
        
        recalculateStyles() {
            const boardGrid = document.getElementById('board-grid');
            if (!boardGrid) return;
            const boardRect = boardGrid.getBoundingClientRect();
            
            // Group players by cell position to calculate overlap offset
            const cellGroups = {};
            this.clientPlayers.forEach((p, idx) => {
                // TREAT POSITION 0 AS 1 FOR VISUAL START POSITION!
                let pos = parseInt(p.position) || 0;
                if (pos === 0) pos = 1;
                
                if (!cellGroups[pos]) cellGroups[pos] = [];
                cellGroups[pos].push(idx);
            });
            
            const colorsMapping = {
                0: '#ef4444',
                1: '#3b82f6',
                2: '#22c55e',
                3: '#eab308',
                4: '#a855f7',
                5: '#ec4899'
            };
            
            const newStyles = [];
            this.clientPlayers.forEach((p, idx) => {
                let cellId = parseInt(p.position) || 0;
                // TREAT POSITION 0 AS 1 FOR VISUAL START POSITION!
                if (cellId === 0) cellId = 1;
                
                const cellElement = document.getElementById('cell-' + cellId);
                if (cellElement) {
                    const cellRect = cellElement.getBoundingClientRect();
                    
                    // Coordinates of the center of the cell relative to board grid
                    let top = cellRect.top - boardRect.top + (cellRect.height / 2) - 12; // 12 is half of w-6 h-6 token size (24px)
                    let left = cellRect.left - boardRect.left + (cellRect.width / 2) - 12;
                    
                    // Apply offset if multiple players share the cell
                    const siblings = cellGroups[cellId] || [];
                    if (siblings.length > 1) {
                        const siblingIndex = siblings.indexOf(idx);
                        const angle = (siblingIndex * (2 * Math.PI)) / siblings.length;
                        const radius = 9; // radius in px for token distribution circle
                        top += Math.sin(angle) * radius;
                        left += Math.cos(angle) * radius;
                    }
                    
                    const bgColor = colorsMapping[idx] || '#6b7280';
                    newStyles[idx] = `top: ${top}px; left: ${left}px; background-color: ${bgColor}; display: flex;`;
                } else {
                    newStyles[idx] = `display: none;`;
                }
            });
            this.tokenStyles = newStyles;

            // Compute Snakes SVG Paths
            let snakesHtml = '';
            Object.keys(this.snakes).forEach((headCell, idx) => {
                const tailCell = this.snakes[headCell];
                const headEl = document.getElementById('cell-' + headCell);
                const tailEl = document.getElementById('cell-' + tailCell);
                
                if (headEl && tailEl) {
                    const headRect = headEl.getBoundingClientRect();
                    const tailRect = tailEl.getBoundingClientRect();
                    
                    const startX = headRect.left - boardRect.left + (headRect.width / 2);
                    const startY = headRect.top - boardRect.top + (headRect.height / 2);
                    const endX = tailRect.left - boardRect.left + (tailRect.width / 2);
                    const endY = tailRect.top - boardRect.top + (tailRect.height / 2);
                    
                    const dx = endX - startX;
                    const dy = endY - startY;
                    const D = Math.sqrt(dx * dx + dy * dy);
                    const ux = dx / D;
                    const uy = dy / D;
                    const nx = -uy;
                    const ny = ux;
                    
                    // Bezier control points for S-curve wave
                    const offset = Math.min(D * 0.22, 45); // curve amount
                    const cp1X = startX + 0.33 * dx + offset * nx;
                    const cp1Y = startY + 0.33 * dy + offset * ny;
                    const cp2X = startX + 0.66 * dx - offset * nx;
                    const cp2Y = startY + 0.66 * dy - offset * ny;
                    
                    const bodyD = `M ${startX},${startY} C ${cp1X},${cp1Y} ${cp2X},${cp2Y} ${endX},${endY}`;
                    
                    // Eyes (approx direction of head facing is out from cp1 to start)
                    const hdx = startX - cp1X;
                    const hdy = startY - cp1Y;
                    const hlen = Math.sqrt(hdx * hdx + hdy * hdy);
                    const headUx = hdx / hlen;
                    const headUy = hdy / hlen;
                    const headNx = -headUy;
                    const headNy = headUx;
                    
                    const leftEyeX = startX + 2 * headUx + 4 * headNx;
                    const leftEyeY = startY + 2 * headUy + 4 * headNy;
                    const rightEyeX = startX + 2 * headUx - 4 * headNx;
                    const rightEyeY = startY + 2 * headUy - 4 * headNy;
                    
                    // Tongue
                    const baseX = startX + 6 * headUx;
                    const baseY = startY + 6 * headUy;
                    const fork1X = baseX + 4 * headUx + 3 * headNx;
                    const fork1Y = baseY + 4 * headUy + 3 * headNy;
                    const fork2X = baseX + 4 * headUx - 3 * headNx;
                    const fork2Y = baseY + 4 * headUy - 3 * headNy;
                    
                    const tongueD = `M ${startX},${startY} L ${baseX},${baseY} M ${baseX},${baseY} L ${fork1X},${fork1Y} M ${baseX},${baseY} L ${fork2X},${fork2Y}`;
                    
                    const gradId = `snake-grad-${idx % 4}`;
                    
                    snakesHtml += `
                        <g filter="url(#svg-shadow)">
                            <path d="${bodyD}" stroke="url(#${gradId})" stroke-width="12" stroke-linecap="round" fill="none" />
                            <path d="${bodyD}" stroke="#ffffff" stroke-width="2.5" stroke-dasharray="3 7" stroke-opacity="0.6" stroke-linecap="round" fill="none" />
                            <circle cx="${leftEyeX}" cy="${leftEyeY}" r="2.8" fill="white" />
                            <circle cx="${leftEyeX}" cy="${leftEyeY}" r="1.3" fill="black" />
                            <circle cx="${rightEyeX}" cy="${rightEyeY}" r="2.8" fill="white" />
                            <circle cx="${rightEyeX}" cy="${rightEyeY}" r="1.3" fill="black" />
                            <path d="${tongueD}" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" fill="none" />
                        </g>
                    `;
                }
            });
            
            // Compute Ladders SVG Paths
            let laddersHtml = '';
            Object.keys(this.ladders).forEach((startCell) => {
                const endCell = this.ladders[startCell];
                const startEl = document.getElementById('cell-' + startCell);
                const endEl = document.getElementById('cell-' + endCell);
                
                if (startEl && endEl) {
                    const startRect = startEl.getBoundingClientRect();
                    const endRect = endEl.getBoundingClientRect();
                    
                    const startX = startRect.left - boardRect.left + (startRect.width / 2);
                    const startY = startRect.top - boardRect.top + (startRect.height / 2);
                    const endX = endRect.left - boardRect.left + (endRect.width / 2);
                    const endY = endRect.top - boardRect.top + (endRect.height / 2);
                    
                    const dx = endX - startX;
                    const dy = endY - startY;
                    const D = Math.sqrt(dx * dx + dy * dy);
                    const ux = dx / D;
                    const uy = dy / D;
                    const nx = -uy;
                    const ny = ux;
                    
                    const halfWidth = 7;
                    const leftRailStartX = startX - halfWidth * nx;
                    const leftRailStartY = startY - halfWidth * ny;
                    const leftRailEndX = endX - halfWidth * nx;
                    const leftRailEndY = endY - halfWidth * ny;
                    
                    const rightRailStartX = startX + halfWidth * nx;
                    const rightRailStartY = startY + halfWidth * ny;
                    const rightRailEndX = endX + halfWidth * nx;
                    const rightRailEndY = endY + halfWidth * ny;
                    
                    let rungsHtml = '';
                    const rungSpacing = 16;
                    const numRungs = Math.floor(D / rungSpacing);
                    for (let k = 1; k < numRungs; k++) {
                        const t = k / numRungs;
                        const rx = startX + t * dx;
                        const ry = startY + t * dy;
                        const rxStart = rx - halfWidth * nx;
                        const ryStart = ry - halfWidth * ny;
                        const rxEnd = rx + halfWidth * nx;
                        const ryEnd = ry + halfWidth * ny;
                        rungsHtml += `<line x1="${rxStart}" y1="${ryStart}" x2="${rxEnd}" y2="${ryEnd}" stroke="#64748b" stroke-width="3" stroke-linecap="round" />`;
                    }
                    
                    laddersHtml += `
                        <g filter="url(#svg-shadow)">
                            <line x1="${leftRailStartX}" y1="${leftRailStartY}" x2="${leftRailEndX}" y2="${leftRailEndY}" stroke="#475569" stroke-width="4.5" stroke-linecap="round" />
                            <line x1="${rightRailStartX}" y1="${rightRailStartY}" x2="${rightRailEndX}" y2="${rightRailEndY}" stroke="#475569" stroke-width="4.5" stroke-linecap="round" />
                            ${rungsHtml}
                        </g>
                    `;
                }
            });
            
            // Set the full SVG inner HTML content (defs must always be present)
            this.svgContent = `
                <defs>
                    <filter id="svg-shadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="1" dy="2.5" stdDeviation="2.5" flood-color="#000000" flood-opacity="0.25"/>
                    </filter>
                    <linearGradient id="snake-grad-0" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#fb923c" />
                        <stop offset="100%" stop-color="#ef4444" />
                    </linearGradient>
                    <linearGradient id="snake-grad-1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#f472b6" />
                        <stop offset="100%" stop-color="#a855f7" />
                    </linearGradient>
                    <linearGradient id="snake-grad-2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#34d399" />
                        <stop offset="100%" stop-color="#84cc16" />
                    </linearGradient>
                    <linearGradient id="snake-grad-3" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#60a5fa" />
                        <stop offset="100%" stop-color="#06b6d4" />
                    </linearGradient>
                </defs>
                ${laddersHtml}
                ${snakesHtml}
            `;
        },
        
        animateMovements(newServerPlayers, isCorrect) {
            const serverPlayers = Array.isArray(newServerPlayers) ? newServerPlayers : [];
            if (serverPlayers.length === 0) return;
            
            if (this.isAnimating) return;
            this.isAnimating = true;
            
            let promises = [];
            
            // Default to true if not provided (e.g. initial updates, etc.)
            const correct = isCorrect === undefined ? true : isCorrect;
            
            serverPlayers.forEach((serverPlayer, idx) => {
                const clientPlayer = this.clientPlayers[idx];
                if (!clientPlayer) return;
                
                // TREAT POSITION 0 AS 1 FOR PATH-TRAVERSAL START POSITION!
                let startPos = parseInt(clientPlayer.position) || 0;
                if (startPos === 0) startPos = 1;
                
                let endPos = parseInt(serverPlayer.position) || 0;
                if (endPos === 0) endPos = 1;
                
                if (startPos !== endPos) {
                    const diceVal = parseInt(this.dice) || 0;
                    let path = [];
                    
                    if (correct && diceVal > 0 && endPos !== startPos) {
                        // Calculate intermediate cell target before snake/ladder
                        let intermediateTarget = startPos + diceVal;
                        if (intermediateTarget > 100) {
                            let extra = intermediateTarget - 100;
                            intermediateTarget = 100 - extra;
                        }
                        
                        // Generate path for normal step progression
                        let current = startPos;
                        while (current !== intermediateTarget) {
                            if (intermediateTarget > current) {
                                current++;
                            } else {
                                current--;
                            }
                            path.push(current);
                        }
                        
                        // If final position differs from intermediateTarget, they hit a snake/ladder!
                        if (endPos !== intermediateTarget) {
                            path.push(endPos);
                        }
                    } else {
                        // Move backward directly step-by-step to final penalized endPos (startPos - 1)
                        let current = startPos;
                        while (current !== endPos) {
                            if (endPos > current) {
                                current++;
                            } else {
                                current--;
                            }
                            path.push(current);
                        }
                    }
                    
                    if (path.length > 0) {
                        let stepIndex = 0;
                        const p = new Promise((resolve) => {
                            const executeStep = () => {
                                if (stepIndex < path.length) {
                                    const prevPos = stepIndex > 0 ? path[stepIndex - 1] : startPos;
                                    const currentPos = path[stepIndex];
                                    
                                    this.clientPlayers[idx].position = currentPos;
                                    this.recalculateStyles();
                                    
                                    // Trigger jump hop scaling
                                    const isSnakeOrLadder = Math.abs(currentPos - prevPos) > 6;
                                    if (!isSnakeOrLadder) {
                                        this.jumpingPlayerIndex = idx;
                                        setTimeout(() => {
                                            if (this.jumpingPlayerIndex === idx) {
                                                this.jumpingPlayerIndex = null;
                                            }
                                        }, 180);
                                    }
                                    
                                    stepIndex++;
                                    setTimeout(executeStep, isSnakeOrLadder ? 750 : 280);
                                } else {
                                    resolve();
                                }
                            };
                            executeStep();
                        });
                        promises.push(p);
                    }
                }
            });
            
            Promise.all(promises).then(() => {
                this.isAnimating = false;
                // Force sync coordinates one final time to be absolutely sure
                this.recalculateStyles();
            });
        }
    }));
});
</script>
