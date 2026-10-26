<x-layouts.app title="Student Dashboard — AquaClass">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Top Welcome & Fish Hero Card -->
        <div class="ocean-glass rounded-3xl p-6 sm:p-8 mb-8 shadow-xl border-2 border-sky-300/60 relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left: Greeting & Fish Stage Status -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100/80 text-cyan-800 text-xs font-bold">
                        <span>🌊 Student Dashboard</span>
                        <span>•</span>
                        <span>{{ $classroom ? $classroom->name : 'Home Learner' }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Splash into learning, <span class="text-cyan-600">{{ explode(' ', $user->name)[0] }}!</span>
                    </h1>

                    <p class="text-sm font-medium text-slate-600">
                        Your virtual fish <strong class="text-slate-800">{{ $fish?->name ?? 'Finny' }}</strong> is currently at stage <span class="text-cyan-700 font-bold">{{ $fish?->stage?->display_name ?? 'Tiny Fry' }}</span>. Keep practicing to help it evolve!
                    </p>

                    <!-- Growth Progress Bar -->
                    <div class="bg-white/80 rounded-2xl p-4 border border-sky-200/80 shadow-sm space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-600">Stage Progress</span>
                            <span class="text-cyan-700 font-extrabold">{{ $fish?->getGrowthPercentage() ?? 50 }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-cyan-400 to-blue-600 transition-all duration-500"
                                 style="width: {{ $fish?->getGrowthPercentage() ?? 50 }}%;"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium">
                            <span>Current: {{ $fish?->stage?->display_name ?? 'Tiny Fry' }}</span>
                            @if($fish?->getNextStageName())
                                <span class="text-cyan-800 font-semibold">Next: {{ $fish->getNextStageName() }} ({{ $fish->getPointsToNextStage() }} pts left)</span>
                            @else
                                <span class="text-emerald-600 font-bold">Max Stage Achieved! ⭐</span>
                            @endif
                        </div>
                    </div>

                    <!-- Metric Counters -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3.5 rounded-2xl bg-amber-50/90 border border-amber-200 shadow-sm flex items-center gap-3">
                            <span class="text-2xl">🪙</span>
                            <div>
                                <span class="block text-xl font-black text-amber-900">{{ $studentProfile?->total_points ?? 0 }}</span>
                                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wide">Growth Points</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-orange-50/90 border border-orange-200 shadow-sm flex items-center gap-3">
                            <span class="text-2xl">🔥</span>
                            <div>
                                <span class="block text-xl font-black text-orange-900">{{ $studentProfile?->current_streak ?? 1 }} Days</span>
                                <span class="text-[11px] font-bold text-orange-700 uppercase tracking-wide">Learning Streak</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('student.aquarium') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-sm shadow-md shadow-cyan-500/25 hover:scale-105 transition">
                            <span>🐠 Full Aquarium Room & Decor</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Live Canvas Mini-Tank -->
                <div class="lg:col-span-7">
                    <x-aquarium-canvas :fishes="[$fish]" height="320px" tankTitle="{{ $fish?->name ?? 'Finny' }}'s Tank" />
                </div>

            </div>
        </div>

        <!-- Main Content 2-Column Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left 8 Columns: Assignments & Activities -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section: Classroom Homework & Assignments -->
                <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-100 flex items-center justify-center text-xl">
                                📝
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Classroom Homework</h2>
                                <p class="text-xs font-semibold text-slate-500">Teacher assigned learning tasks and reflections</p>
                            </div>
                        </div>
                    </div>

                    @if($assignments->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($assignments as $asg)
                                @php
                                    $sub = $asg->submissions->first();
                                @endphp
                                <div class="p-5 rounded-2xl bg-white border border-sky-100 hover:border-sky-300 shadow-sm transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-extrabold text-white" style="background-color: {{ $asg->subject?->color ?? '#0284c7' }};">
                                                {{ $asg->subject?->name ?? 'General' }}
                                            </span>
                                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">
                                                +{{ $asg->points }} Points
                                            </span>
                                        </div>
                                        <h3 class="text-base font-extrabold text-slate-800">
                                            {{ $asg->title }}
                                        </h3>
                                        <p class="text-xs text-slate-500 font-medium line-clamp-1">
                                            {{ $asg->description }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto justify-between sm:justify-end">
                                        @if($sub)
                                            @if($sub->status === 'approved')
                                                <span class="px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-black flex items-center gap-1">
                                                    ✓ Approved (+{{ $sub->points_awarded }} pts)
                                                </span>
                                            @elseif($sub->status === 'submitted')
                                                <span class="px-3 py-1.5 rounded-xl bg-amber-100 text-amber-800 text-xs font-bold flex items-center gap-1">
                                                    ⏳ Under Review
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-1 rounded-xl bg-rose-50 text-rose-700 text-xs font-bold">
                                                Due {{ $asg->due_date ? $asg->due_date->diffForHumans() : 'Soon' }}
                                            </span>
                                        @endif

                                        <a href="{{ route('student.assignments.show', $asg) }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-cyan-700 transition">
                                            {{ $sub ? 'View Work' : 'Complete Homework →' }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-white/60 rounded-2xl border border-dashed border-sky-200">
                            <span class="text-3xl block mb-2">🎉</span>
                            <p class="text-sm font-bold text-slate-700">All caught up! No pending homework.</p>
                            <p class="text-xs text-slate-500 mt-1">Play an activity below to earn bonus fish growth points!</p>
                        </div>
                    @endif
                </div>

                <!-- Section: Recommended Learning Activities -->
                <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-cyan-100 flex items-center justify-center text-xl">
                                🌟
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Learning Activities</h2>
                                <p class="text-xs font-semibold text-slate-500">Interactive challenges tailored for Grades 1, 2, and 3</p>
                            </div>
                        </div>

                        <a href="{{ route('activities.index') }}" class="text-xs font-extrabold text-cyan-700 hover:underline">
                            View All →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($activities as $act)
                            <div class="p-5 rounded-2xl bg-white border border-sky-100 hover:border-cyan-400 hover:shadow-md transition flex flex-col justify-between gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold text-white" style="background-color: {{ $act->subject?->color ?? '#0284c7' }};">
                                            {{ $act->subject?->name }}
                                        </span>
                                        <span class="text-xs font-black text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-md">
                                            +{{ $act->max_points }} pts
                                        </span>
                                    </div>
                                    <h3 class="text-base font-extrabold text-slate-800 mb-1 leading-snug">
                                        {{ $act->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2">
                                        {{ $act->instructions }}
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-[11px] font-semibold text-slate-400">
                                        🎯 {{ ucfirst($act->difficulty) }}
                                    </span>
                                    <a href="{{ route('activities.show', $act) }}" class="px-4 py-1.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-xs font-extrabold shadow-sm hover:scale-105 transition">
                                        Play Now ✨
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right 4 Columns: Badges & Ledger -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Badges Showcase -->
                <div class="ocean-glass rounded-3xl p-6 shadow-md border border-sky-200">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-2xl">🏆</span>
                        <div>
                            <h2 class="text-lg font-black text-slate-800">Badge Showcase</h2>
                            <p class="text-xs text-slate-500 font-semibold">{{ count($unlockedAchievementIds) }} of {{ count($achievements) }} unlocked</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach($achievements as $ach)
                            @php
                                $isUnlocked = in_array($ach->id, $unlockedAchievementIds);
                            @endphp
                            <div class="p-3 rounded-2xl border text-center transition {{ $isUnlocked ? 'bg-amber-50/90 border-amber-300 shadow-sm' : 'bg-white/40 border-slate-200/60 opacity-50' }}">
                                <span class="text-2xl block mb-1 {{ $isUnlocked ? 'animate-bounce' : 'grayscale' }}">
                                    {{ $ach->icon }}
                                </span>
                                <h4 class="text-xs font-black text-slate-800 leading-tight">
                                    {{ $ach->name }}
                                </h4>
                                <span class="text-[10px] font-semibold {{ $isUnlocked ? 'text-amber-700 font-bold' : 'text-slate-400' }}">
                                    {{ $isUnlocked ? 'Unlocked!' : 'Locked' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Unlocked Tank Decorations -->
                <div class="ocean-glass rounded-3xl p-6 shadow-md border border-sky-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🪸</span>
                            <h2 class="text-lg font-black text-slate-800">Decorations</h2>
                        </div>
                        <a href="{{ route('student.aquarium') }}" class="text-xs font-bold text-cyan-700 hover:underline">
                            Decorate →
                        </a>
                    </div>

                    <p class="text-xs text-slate-600 mb-4 font-medium">
                        Earn points through homework and activities to unlock rare corals and pirate treasures!
                    </p>

                    <div class="space-y-2">
                        @foreach($decorations->take(4) as $dec)
                            @php
                                $unlocked = in_array($dec->id, $unlockedDecorationIds);
                            @endphp
                            <div class="p-3 rounded-xl bg-white/80 border border-sky-100 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">{{ $dec->type === 'treasure' ? '🪙' : ($dec->type === 'castle' ? '🏰' : '🌿') }}</span>
                                    <span class="font-extrabold text-slate-800">{{ $dec->name }}</span>
                                </div>
                                @if($unlocked)
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[10px]">Unlocked</span>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400">{{ $dec->unlock_points }} pts</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-layouts.app>
