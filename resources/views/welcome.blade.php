<x-layouts.app title="AquaClass — International Gamified Learning Platform">

    <!-- Hero Section -->
    <section class="relative pt-12 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Hero Left: Copy & CTAs -->
                <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-100 text-cyan-900 text-xs font-black shadow-sm">
                        <span>🐟 The Virtual Classroom Fish Tank Reimagined</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        Learn More. <br>
                        Earn Points. <br>
                        <span class="bg-gradient-to-r from-cyan-600 via-sky-600 to-blue-700 bg-clip-text text-transparent">Grow Together.</span>
                    </h1>

                    <p class="text-base sm:text-lg font-medium text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        AquaClass turns primary education into an underwater adventure. Children in <strong>International Grades 1, 2, and 3</strong> solve curriculum-aligned challenges, submit homework, and watch their virtual fish evolve.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('demo.switch', 'teacher') }}" class="px-7 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 text-white font-black text-sm shadow-xl shadow-blue-500/25 hover:scale-105 active:scale-95 transition flex items-center gap-2">
                            <span>👩‍🏫 Enter as Teacher</span>
                            <span>→</span>
                        </a>

                        <a href="{{ route('demo.switch', 'leo') }}" class="px-7 py-4 rounded-2xl bg-white hover:bg-slate-50 border-2 border-sky-300 text-slate-800 font-black text-sm shadow-md hover:scale-105 active:scale-95 transition flex items-center gap-2">
                            <span>🐠 Play as Student (Leo)</span>
                        </a>
                    </div>

                    <div class="flex items-center justify-center lg:justify-start gap-6 pt-4 text-xs font-extrabold text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <span class="text-emerald-500">✓</span> No credit card needed
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="text-emerald-500">✓</span> Cambridge & IB Aligned
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="text-emerald-500">✓</span> Instant WebGL Tank
                        </span>
                    </div>
                </div>

                <!-- Hero Right: Live Interactive Aquarium Preview -->
                <div class="lg:col-span-6">
                    <div class="relative">
                        <!-- Decorative glow -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-3xl opacity-30 blur-2xl"></div>

                        <div class="relative ocean-glass rounded-3xl p-4 shadow-2xl border-2 border-white/60">
                            <x-aquarium-canvas height="380px" tankTitle="AquaClass Live Reef Preview" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Growth Evolution Stages Carousel / Section -->
    <section class="py-16 bg-white/60 border-y border-sky-200/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-black uppercase text-cyan-700 tracking-wider">Visual Growth Engine</span>
                <h2 class="text-3xl font-black text-slate-900 mt-1">
                    Watch Knowledge Turn into Scales & Fins
                </h2>
                <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-2">
                    Every homework submission, math problem, and reading milestone feeds the student's fish with immutable points from our secure ledger.
                </p>
            </div>

            <!-- Stages Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($stages as $stage)
                    <div class="p-5 rounded-3xl ocean-glass border border-sky-200 shadow-sm text-center flex flex-col justify-between hover:border-cyan-400 hover:shadow-md transition">
                        <div>
                            <span class="text-4xl block mb-3">
                                @if($stage->name === 'tiny_fry') 🐣
                                @elseif($stage->name === 'little_swimmer') 🐟
                                @elseif($stage->name === 'reef_explorer') 🐠
                                @elseif($stage->name === 'coral_guardian') 🐡
                                @else 🐬
                                @endif
                            </span>

                            <h3 class="text-sm font-black text-slate-900 mb-1">
                                {{ $stage->display_name }}
                            </h3>

                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed mb-3">
                                {{ $stage->description }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-200/60 text-xs font-extrabold text-cyan-700 bg-cyan-50/70 py-1.5 rounded-xl">
                            {{ $stage->min_points }} - {{ $stage->max_points > 10000 ? '700+' : $stage->max_points }} pts
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Grade 1, 2, 3 Curriculum Features -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-black uppercase text-indigo-700 tracking-wider">Academics First</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-1">
                    Designed Specifically for Early Primary
                </h2>
                <p class="text-sm font-medium text-slate-600 mt-2">
                    AquaClass is tailored to the motor skills, reading abilities, and developmental needs of Grade 1-3 learners.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Grade 1 Card -->
                <div class="ocean-glass rounded-3xl p-8 border border-sky-200 shadow-md space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-2xl">
                        🌱
                    </div>
                    <span class="text-xs font-black uppercase text-amber-700 tracking-wide">Age 6 - 7 Years</span>
                    <h3 class="text-xl font-black text-slate-900">Grade 1: Discovery & Phonics</h3>
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                        Audio-narrated questions, counting up to 20 with sea creatures, short vowel CVC word matching, and living vs non-living identification.
                    </p>
                    <ul class="space-y-1.5 text-xs font-bold text-slate-700 pt-2 border-t">
                        <li>✓ Audio Text-to-Speech support</li>
                        <li>✓ Tap-and-hear phonics sounds</li>
                        <li>✓ Single-step math problems</li>
                    </ul>
                </div>

                <!-- Grade 2 Card -->
                <div class="ocean-glass rounded-3xl p-8 border-2 border-cyan-400 shadow-xl space-y-4 relative">
                    <span class="absolute -top-3 right-6 px-3 py-1 rounded-full bg-cyan-600 text-white text-[10px] font-black uppercase tracking-wider shadow-sm">
                        Most Popular
                    </span>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-100 flex items-center justify-center text-2xl">
                        🌊
                    </div>
                    <span class="text-xs font-black uppercase text-cyan-700 tracking-wide">Age 7 - 8 Years</span>
                    <h3 class="text-xl font-black text-slate-900">Grade 2: Fluency & Ecosystems</h3>
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                        Two-digit addition and subtraction, parts of speech (nouns, verbs, adjectives), animal adaptation habitats, and weekly reading journals.
                    </p>
                    <ul class="space-y-1.5 text-xs font-bold text-slate-700 pt-2 border-t">
                        <li>✓ Odd & even number sorting</li>
                        <li>✓ Sentence writing with feedback</li>
                        <li>✓ Marine biology explorations</li>
                    </ul>
                </div>

                <!-- Grade 3 Card -->
                <div class="ocean-glass rounded-3xl p-8 border border-sky-200 shadow-md space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-2xl">
                        🐬
                    </div>
                    <span class="text-xs font-black uppercase text-indigo-700 tracking-wide">Age 8 - 9 Years</span>
                    <h3 class="text-xl font-black text-slate-900">Grade 3: Mastery & Reasoning</h3>
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                        Skip counting and multiplication tables (2, 5, 10, 3), fractional concepts, paragraph reading comprehension, and water cycle experiments.
                    </p>
                    <ul class="space-y-1.5 text-xs font-bold text-slate-700 pt-2 border-t">
                        <li>✓ Multiplication arrays</li>
                        <li>✓ Multi-paragraph inferences</li>
                        <li>✓ Water cycle & weather systems</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Teacher Superpowers Section -->
    <section class="py-16 bg-gradient-to-r from-indigo-900 via-blue-900 to-cyan-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-cyan-400 text-cyan-950 text-xs font-black uppercase">
                        👩‍🏫 Built for Educators
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                        A Whole Classroom in One Sparkling Aquarium
                    </h2>
                    <p class="text-sm font-medium text-sky-200 leading-relaxed">
                        Teachers project the <strong>Classroom Live Tank</strong> on smartboards during class or Friday reward sessions. Every child feels recognized when their fish swims happily into view.
                    </p>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-sm">✓</span>
                            <span class="text-xs font-bold text-sky-100">Quick 7-letter join code (e.g. AQUA202) for frictionless student login</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-sm">✓</span>
                            <span class="text-xs font-bold text-sky-100">Review homework submissions with custom encouragement and 1-click points</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-sm">✓</span>
                            <span class="text-xs font-bold text-sky-100">One-click gradebook CSV export for school reports and parent-teacher meetings</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('demo.switch', 'teacher') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-cyan-400 hover:bg-cyan-300 text-cyan-950 font-black text-sm shadow-xl transition">
                            <span>Open Teacher Workspace</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl space-y-4">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                            <span class="text-xs font-bold text-cyan-300">Grade 2 Blue Dolphins Live</span>
                            <span class="text-xs font-black text-emerald-400">● 4 Fish Swimming</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="p-3 rounded-xl bg-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span>⭐</span>
                                    <span class="font-bold">Leo Starfish</span>
                                </div>
                                <span class="font-black text-cyan-300">Finny (Reef Explorer • 280 pts)</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span>🪸</span>
                                    <span class="font-bold">Maya Coral</span>
                                </div>
                                <span class="font-black text-cyan-300">Bubbles (Reef Explorer • 170 pts)</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span>🐚</span>
                                    <span class="font-bold">Kai Nautil</span>
                                </div>
                                <span class="font-black text-cyan-300">Sunny (Little Guppy • 95 pts)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
