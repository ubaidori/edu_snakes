<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edu-Snakes by digitalsolutions.co.id</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #090a0f;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(244, 63, 94, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(234, 179, 8, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.02) 1px, transparent 0);
            background-size: 100% 100%, 100% 100%, 100% 100%, 24px 24px;
        }

        h1, h2, h3, .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .glass-panel-hover:hover {
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(17, 24, 39, 0.65);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            transform: translateY(-4px);
        }

        /* Glowing Text Effects */
        .glow-title {
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }

        .glow-purple {
            box-shadow: 0 0 50px -10px rgba(99, 102, 241, 0.35);
        }

        .glow-button {
            position: relative;
            overflow: hidden;
        }

        .glow-button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transform: rotate(45deg);
            transition: 0.5s;
            opacity: 0;
        }

        .glow-button:hover::after {
            opacity: 1;
            left: 120%;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1.5deg); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Continuous Pulse Spin for Dice */
        @keyframes spin3D {
            0% { transform: rotateY(0deg) rotateX(10deg); }
            50% { transform: rotateY(180deg) rotateX(-10deg); }
            100% { transform: rotateY(360deg) rotateX(10deg); }
        }

        .animate-spin-3d {
            animation: spin3D 20s linear infinite;
        }
    </style>
</head>
<body class="text-slate-100 antialiased min-h-screen flex flex-col justify-between p-4 md:p-8 selection:bg-indigo-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="w-full max-w-7xl mx-auto flex items-center justify-between py-4 px-6 rounded-2xl glass-panel shadow-lg z-20">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-md shadow-orange-500/20 transform group-hover:scale-105 transition">
                <span class="text-xl">🎲</span>
            </div>
            <div>
                <span class="font-extrabold text-lg text-white tracking-tight uppercase group-hover:text-amber-400 transition">Edu-Snakes</span>
                <span class="text-[9px] text-amber-500 font-black tracking-widest block -mt-1 uppercase">Quiz Board Game</span>
            </div>
        </a>

        <!-- Auth Navigation Links -->
        <nav class="flex items-center gap-2">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 shadow-md hover:shadow-orange-500/20 transition transform hover:-translate-y-0.5 glow-button">
                        Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-300 hover:text-white hover:bg-white/5 transition">
                        Masuk (Login)
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-indigo-600/90 hover:bg-indigo-600 border border-indigo-500/30 transition transform hover:-translate-y-0.5 shadow-md shadow-indigo-500/10">
                            Daftar Baru
                        </a>
                    @endif
                @endauth
            @endif
        </nav>
    </header>

    <!-- Main Hero Landing Section -->
    <main class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center py-12 lg:py-20 z-10">
        
        <!-- Left Hero Content (Lg col-span-7) -->
        <div class="lg:col-span-7 space-y-6 text-center lg:text-left px-2">
            <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full px-4 py-1.5 text-xs text-indigo-400 font-extrabold tracking-wider uppercase mb-2">
                <span>⚡</span> Revitalisasi Pembelajaran Digital
            </div>
            
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight leading-[1.08] text-white">
                Bermain & Menguasai <br class="hidden md:block"/>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-amber-400 via-orange-500 to-rose-500 glow-title">
                    Materi Pendidikan
                </span>
            </h1>
            
            <p class="text-base sm:text-lg text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed font-light">
                Selamat datang di <strong class="text-white font-semibold">Edu-Snakes</strong>, platform inovatif pembelajaran berbasis game ular tangga interaktif. Integrasikan bank soal kuis pilihan ganda secara kustom dengan papan permainan digital yang dinamis!
            </p>

            <!-- CTA Actions Button -->
            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-black text-slate-900 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 shadow-xl shadow-amber-500/25 transition transform hover:-translate-y-1 text-center select-none glow-button">
                        🎮 Masuk ke Sesi Game
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-black text-slate-900 bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-350 hover:to-orange-500 shadow-xl shadow-orange-500/20 transition transform hover:-translate-y-1 text-center select-none glow-button">
                        🚀 Mulai Bermain Sekarang
                    </a>
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-bold text-white border border-slate-700 hover:border-slate-500 bg-white/5 hover:bg-white/10 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                        Daftar Guru / Sekolah
                    </a>
                @endauth
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-800/80 max-w-md mx-auto lg:mx-0">
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-white">100%</p>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Interaktif & Real-time</p>
                </div>
                <div class="border-l border-slate-800 pl-4">
                    <p class="text-2xl sm:text-3xl font-black text-indigo-400">SVG</p>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Dynamic Geometry</p>
                </div>
                <div class="border-l border-slate-800 pl-4">
                    <p class="text-2xl sm:text-3xl font-black text-emerald-400">Kuis</p>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Kustom Sesuai Materi</p>
                </div>
            </div>
        </div>

        <!-- Right Hero Interactive Graphic (Lg col-span-5) -->
        <div class="lg:col-span-5 flex justify-center items-center relative py-6">
            <!-- Background Glow behind the SVG -->
            <div class="absolute w-72 h-72 rounded-full bg-indigo-500/20 filter blur-[80px] -z-10 animate-pulse"></div>
            <div class="absolute w-48 h-48 rounded-full bg-rose-500/10 filter blur-[60px] -z-10 bottom-10 right-10"></div>
            
            <!-- Beautiful Custom 3D SVG Board Illustration -->
            <div class="w-full max-w-[420px] animate-float relative z-10">
                <svg viewBox="0 0 500 500" class="w-full drop-shadow-2xl overflow-visible" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Isometric Board Plate -->
                    <path d="M250 80 L450 180 L250 280 L50 180 Z" fill="#1e1b4b" fill-opacity="0.8" stroke="#312e81" stroke-width="6" />
                    <!-- Board grid segments -->
                    <path d="M150 130 L350 230" stroke="#4338ca" stroke-width="2" stroke-opacity="0.5" />
                    <path d="M250 180 L250 280" stroke="#4338ca" stroke-width="2" stroke-opacity="0.5" />
                    <path d="M150 230 L350 130" stroke="#4338ca" stroke-width="2" stroke-opacity="0.5" />
                    
                    <!-- Isometric Board Shadow base -->
                    <path d="M250 95 L435 187 L250 270 L65 187 Z" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-opacity="0.3" />

                    <!-- Neon Ladders (Climbing up) -->
                    <g stroke="#10b981" stroke-width="4" stroke-linecap="round">
                        <!-- Left rail -->
                        <line x1="120" y1="210" x2="210" y2="120" />
                        <!-- Right rail -->
                        <line x1="135" y1="225" x2="225" y2="135" />
                        <!-- Rungs -->
                        <line x1="133" y1="213" x2="143" y2="223" stroke-width="3" />
                        <line x1="151" y1="195" x2="161" y2="205" stroke-width="3" />
                        <line x1="169" y1="177" x2="179" y2="187" stroke-width="3" />
                        <line x1="187" y1="159" x2="197" y2="169" stroke-width="3" />
                        <line x1="205" y1="141" x2="215" y2="151" stroke-width="3" />
                    </g>
                    
                    <!-- Neon Curved Snake (Meliuk ke bawah) -->
                    <g filter="drop-shadow(0px 8px 16px rgba(239, 68, 68, 0.4))">
                        <!-- Wavy Body -->
                        <path d="M380 150 C 350 120, 310 240, 270 200 C 230 160, 210 280, 180 250" stroke="url(#snake-grad-landing)" stroke-width="10" stroke-linecap="round" fill="none" />
                        <!-- Head Eyes -->
                        <circle cx="378" cy="148" r="2.5" fill="white" />
                        <circle cx="384" cy="154" r="2.5" fill="white" />
                        <path d="M386 142 L392 136 M386 142 L395 145" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                    </g>

                    <!-- Glowing Giant 3D-like Dice floating above -->
                    <g transform="translate(250, 200)" class="animate-pulse">
                        <!-- Back box shape -->
                        <rect x="-35" y="-35" width="70" height="70" rx="16" fill="url(#dice-grad)" stroke="#f59e0b" stroke-width="3" />
                        <!-- Dot grid of Amber dice -->
                        <circle cx="-16" cy="-16" r="4.5" fill="#090a0f" />
                        <circle cx="16" cy="-16" r="4.5" fill="#090a0f" />
                        <circle cx="-16" cy="16" r="4.5" fill="#090a0f" />
                        <circle cx="16" cy="16" r="4.5" fill="#090a0f" />
                        <circle cx="0" cy="0" r="5" fill="#f59e0b" />
                    </g>

                    <!-- Definisi Gradients -->
                    <defs>
                        <linearGradient id="snake-grad-landing" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#f43f5e" />
                            <stop offset="100%" stop-color="#e11d48" />
                        </linearGradient>
                        <linearGradient id="dice-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#ffffff" />
                            <stop offset="100%" stop-color="#f8fafc" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="w-full max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between py-6 px-6 border-t border-slate-800/80 text-xs text-slate-500 gap-4 mt-8">
        <div>
            &copy; {{ date('Y') }} digitalsolutions.co.id. Seluruh hak cipta dilindungi.
        </div>
        <div class="flex items-center gap-6">
            <span>Versi Laravel: v{{ app()->version() }}</span>
            <span>PHP: v{{ PHP_VERSION }}</span>
            <a href="https://github.com" target="_blank" class="hover:text-slate-300 transition">GitHub</a>
        </div>
    </footer>

</body>
</html>
