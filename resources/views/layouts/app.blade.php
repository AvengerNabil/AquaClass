<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'AquaClass — International Gamified Learning Platform' }}</title>

    <!-- Google Fonts: Outfit & Quicksand -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Responsive Bubble Background Generator */
        .bubble-container {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-gradient-to-b from-sky-50 via-cyan-50 to-blue-100 text-slate-800 antialiased selection:bg-cyan-200 selection:text-cyan-900">

    <!-- Floating Background Bubbles -->
    <div class="bubble-container" aria-hidden="true">
        <div class="bubble animate-float" style="width: 28px; height: 28px; left: 8%; top: 40%; opacity: 0.3;"></div>
        <div class="bubble animate-float" style="width: 44px; height: 44px; left: 22%; top: 75%; opacity: 0.25; animation-delay: 1.5s;"></div>
        <div class="bubble animate-float" style="width: 20px; height: 20px; left: 60%; top: 25%; opacity: 0.35; animation-delay: 2.5s;"></div>
        <div class="bubble animate-float" style="width: 36px; height: 36px; left: 85%; top: 60%; opacity: 0.3; animation-delay: 0.8s;"></div>
    </div>

    <!-- Quick Demo Switcher Banner -->
    <div class="bg-gradient-to-r from-cyan-700 via-sky-800 to-indigo-900 text-white text-xs py-2 px-4 shadow-sm z-30 relative border-b border-cyan-500/20">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-400 text-cyan-950 uppercase tracking-wide">
                    🎮 1-Click Demo
                </span>
                <span class="text-sky-200 hidden sm:inline">Switch view to experience different platform roles:</span>
            </div>
            <div class="flex flex-wrap items-center gap-1.5 font-medium">
                <a href="{{ route('demo.switch', 'teacher') }}" class="px-2.5 py-1 rounded-md bg-white/10 hover:bg-white/25 transition text-cyan-100 flex items-center gap-1">
                    👩‍🏫 <span>Teacher (Ms. Clara)</span>
                </a>
                <a href="{{ route('demo.switch', 'leo') }}" class="px-2.5 py-1 rounded-md bg-white/10 hover:bg-white/25 transition text-amber-200 flex items-center gap-1">
                    🐠 <span>Student (Leo - 280 pts)</span>
                </a>
                <a href="{{ route('demo.switch', 'maya') }}" class="px-2.5 py-1 rounded-md bg-white/10 hover:bg-white/25 transition text-emerald-200 flex items-center gap-1">
                    🐟 <span>Student (Maya - 170 pts)</span>
                </a>
                <a href="{{ route('demo.switch', 'admin') }}" class="px-2.5 py-1 rounded-md bg-white/10 hover:bg-white/25 transition text-purple-200 flex items-center gap-1">
                    ⚓ <span>Admin</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Primary Oceanic Navbar -->
    <header class="sticky top-0 z-20 ocean-glass border-b border-sky-200/60 shadow-sm transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-2xl shadow-md shadow-cyan-500/30 group-hover:scale-105 group-hover:rotate-3 transition duration-300">
                            🐠
                        </div>
                        <div>
                            <span class="text-2xl font-black bg-gradient-to-r from-cyan-600 via-sky-600 to-blue-700 bg-clip-text text-transparent tracking-tight">
                                AquaClass
                            </span>
                            <span class="block text-[11px] font-bold text-sky-600 tracking-wider uppercase -mt-1">
                                Gamified Learning Platform
                            </span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-600">
                        <a href="{{ route('activities.index') }}" class="px-3 py-2 rounded-xl hover:text-cyan-700 hover:bg-sky-100/60 transition {{ request()->routeIs('activities.*') ? 'text-cyan-700 bg-sky-100/80 font-bold' : '' }}">
                            🌟 Activities
                        </a>
                        <a href="{{ route('curriculum.index') }}" class="px-3 py-2 rounded-xl hover:text-cyan-700 hover:bg-sky-100/60 transition {{ request()->routeIs('curriculum.*') ? 'text-cyan-700 bg-sky-100/80 font-bold' : '' }}">
                            📚 Curriculum (Grades 1-3)
                        </a>
                        <a href="{{ route('classroom.join') }}" class="px-3 py-2 rounded-xl hover:text-cyan-700 hover:bg-sky-100/60 transition {{ request()->routeIs('classroom.join') ? 'text-cyan-700 bg-sky-100/80 font-bold' : '' }}">
                            🔑 Join Classroom
                        </a>
                    </nav>
                </div>

                <!-- Right Side Actions & User Status -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(Auth::user()->isStudent())
                            <!-- Student Quick Stats Bar -->
                            <div class="hidden sm:flex items-center gap-2 bg-white/90 border border-sky-200 rounded-2xl px-3 py-1.5 shadow-sm">
                                <!-- Streak -->
                                <div class="flex items-center gap-1 text-xs font-bold text-amber-600" title="Daily Streak">
                                    <span class="text-base">🔥</span>
                                    <span>{{ Auth::user()->studentProfile?->current_streak ?? 1 }}d</span>
                                </div>
                                <span class="text-slate-300">|</span>
                                <!-- Points -->
                                <div class="flex items-center gap-1 text-xs font-extrabold text-cyan-700" title="Total Growth Points">
                                    <span class="text-base">🪙</span>
                                    <span>{{ Auth::user()->studentProfile?->total_points ?? 0 }} pts</span>
                                </div>
                            </div>

                            <!-- Aquarium Shortcut Button -->
                            <a href="{{ route('student.aquarium') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-sky-600 text-white text-xs font-bold shadow-md shadow-cyan-500/20 hover:shadow-cyan-500/40 hover:-translate-y-0.5 transition">
                                <span>🌊</span>
                                <span>My Aquarium</span>
                            </a>

                            <!-- Student Dashboard Link -->
                            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-2xl bg-sky-100/70 border border-sky-200 hover:bg-sky-200/60 transition">
                                <span class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-lg shadow-inner">
                                    {{ Auth::user()->avatar ?? '🐠' }}
                                </span>
                                <div class="text-left hidden md:block">
                                    <span class="block text-xs font-bold text-slate-800 leading-tight">
                                        {{ explode(' ', Auth::user()->name)[0] }}
                                    </span>
                                    <span class="block text-[10px] text-cyan-700 font-semibold uppercase leading-tight">
                                        {{ Auth::user()->fish?->stage?->display_name ?? 'Student' }}
                                    </span>
                                </div>
                            </a>
                        @else
                            <!-- Teacher Desk Link -->
                            <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-xs font-bold shadow-md shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition">
                                <span>👩‍🏫</span>
                                <span>Teacher Desk</span>
                            </a>
                        @endif

                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition" title="Log Out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    @else
                        <!-- Guest Auth Actions -->
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-700 hover:text-cyan-700 hover:bg-sky-100/60 transition">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-extrabold text-white bg-gradient-to-r from-cyan-500 via-sky-600 to-blue-600 shadow-md shadow-cyan-500/20 hover:shadow-cyan-500/40 hover:-translate-y-0.5 transition">
                            Join Free ✨
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages Container -->
    @if(session('success') || session('error') || session('info') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 relative z-10">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🌟</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-lg">✕</button>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">ℹ️</span>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700 text-lg">✕</button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xl">📢</span>
                        <span class="font-bold">Please check the following:</span>
                    </div>
                    <ul class="list-disc pl-8 space-y-0.5 text-xs text-amber-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content Slot -->
    <main class="flex-1 relative z-10">
        {{ $slot }}
    </main>

    <!-- Oceanic Footer -->
    <footer class="mt-auto border-t border-sky-200/60 bg-white/70 backdrop-blur-md relative z-10 text-xs text-slate-500 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xl">🐠</span>
                <span class="font-bold text-slate-700">AquaClass Platform</span>
                <span>— International Gamified Learning for Grades 1, 2, and 3</span>
            </div>
            <div class="flex items-center gap-6 font-medium text-slate-600">
                <a href="{{ route('activities.index') }}" class="hover:text-cyan-700">Activities</a>
                <a href="{{ route('curriculum.index') }}" class="hover:text-cyan-700">Curriculum</a>
                <a href="{{ route('classroom.join') }}" class="hover:text-cyan-700">Join Classroom</a>
                <a href="{{ route('demo.switch', 'teacher') }}" class="text-cyan-700 font-bold hover:underline">Teacher Demo</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
