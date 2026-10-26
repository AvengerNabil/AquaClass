<x-layouts.app title="Teacher Desk — AquaClass">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ awardModal: false, selectedStudent: null, pointsToAward: 10, awardReason: 'Excellence in classroom activity' }">

        <!-- Top Teacher Bar -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100 text-indigo-900 text-xs font-bold mb-1">
                    <span>👩‍🏫 Teacher Workspace</span>
                    <span>•</span>
                    <span>{{ $classroom ? $classroom->name : 'No Classrooms' }}</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    Classroom Command Center
                </h1>
                <div class="flex items-center gap-3 text-xs font-semibold text-slate-500 mt-1">
                    <span>Join Code: <strong class="text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded font-mono font-bold">{{ $classroom?->join_code ?? 'N/A' }}</strong></span>
                    <span>•</span>
                    <span>{{ $classroom?->gradeLevel?->display_name ?? 'Grade 2' }}</span>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @if($classroom)
                    <a href="{{ route('teacher.classrooms.tank', $classroom) }}" class="px-4 py-2.5 rounded-2xl bg-cyan-600 hover:bg-cyan-500 text-white font-extrabold text-xs shadow-md shadow-cyan-600/20 flex items-center gap-1.5 transition">
                        <span>🐬</span>
                        <span>Classroom Tank</span>
                    </a>

                    <a href="{{ route('teacher.assignments.create', $classroom) }}" class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-md shadow-indigo-600/20 flex items-center gap-1.5 transition">
                        <span>+</span>
                        <span>New Assignment</span>
                    </a>

                    <a href="{{ route('teacher.classrooms.export', $classroom) }}" class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-sm flex items-center gap-1.5 transition">
                        <span>📥</span>
                        <span>Export CSV</span>
                    </a>
                @endif
            </div>
        </div>

        @if($classroom)
            <!-- Metric Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="ocean-glass rounded-3xl p-5 border border-sky-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-bold">
                        👥
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-slate-900">{{ $classroom->students->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Enrolled Students</span>
                    </div>
                </div>

                <div class="ocean-glass rounded-3xl p-5 border border-sky-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl font-bold">
                        🪙
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-amber-950">
                            {{ $classroom->students->sum(fn($s) => $s->studentProfile?->total_points ?? 0) }}
                        </span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Class Total Points</span>
                    </div>
                </div>

                <div class="ocean-glass rounded-3xl p-5 border border-sky-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl font-bold">
                        📥
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-slate-900">{{ $pendingSubmissions->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Needs Grading</span>
                    </div>
                </div>

                <div class="ocean-glass rounded-3xl p-5 border border-sky-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center text-2xl font-bold">
                        🔥
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-orange-950">
                            {{ number_format($classroom->students->avg(fn($s) => $s->studentProfile?->current_streak ?? 0), 1) }}d
                        </span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Avg Active Streak</span>
                    </div>
                </div>
            </div>

            <!-- Classroom Tank Live Preview -->
            <div class="ocean-glass rounded-3xl p-6 sm:p-8 mb-8 border border-sky-200 shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <span>🐠</span>
                            <span>Shared Classroom Aquarium (All Students' Fish Swimming)</span>
                        </h2>
                        <p class="text-xs text-slate-500 font-medium">As students complete homework and activities, their fish grow larger and gain new stages!</p>
                    </div>

                    <a href="{{ route('teacher.classrooms.tank', $classroom) }}" class="text-xs font-extrabold text-cyan-700 hover:underline">
                        Fullscreen Tank Mode →
                    </a>
                </div>

                <x-aquarium-canvas :fishes="$classroom->students->map(fn($s) => $s->fish)->filter()" height="320px" tankTitle="{{ $classroom->name }}" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left 7 cols: Student Roster & Quick Award -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <span>📋</span>
                                <span>Student Roster & Progression</span>
                            </h2>
                            <span class="text-xs font-bold text-slate-400">{{ $classroom->students->count() }} active</span>
                        </div>

                        <div class="space-y-3">
                            @foreach($classroom->students as $student)
                                <div class="p-4 rounded-2xl bg-white border border-sky-100 hover:border-sky-300 shadow-sm transition flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-sky-100 flex items-center justify-center text-xl shadow-inner">
                                            {{ $student->avatar ?? '🐠' }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-extrabold text-slate-900 leading-tight">
                                                {{ $student->name }}
                                            </h4>
                                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                                <span>Fish: <strong>{{ $student->fish?->name ?? 'None' }}</strong></span>
                                                <span>•</span>
                                                <span class="text-cyan-700 font-bold">{{ $student->fish?->stage?->display_name ?? 'Tiny Fry' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="text-right">
                                            <span class="block text-xs font-black text-amber-600">
                                                {{ $student->studentProfile?->total_points ?? 0 }} pts
                                            </span>
                                            <span class="text-[11px] font-bold text-orange-600">
                                                🔥 {{ $student->studentProfile?->current_streak ?? 1 }}d
                                            </span>
                                        </div>

                                        <button type="button" 
                                                @click="selectedStudent = { id: {{ $student->id }}, name: '{{ $student->name }}' }; awardModal = true" 
                                                class="px-3 py-1.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-black text-xs shadow-sm transition">
                                            + Award
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right 5 cols: Submissions Needing Review -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <span>📥</span>
                                <span>Submissions to Grade</span>
                            </h2>
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                                {{ $pendingSubmissions->count() }}
                            </span>
                        </div>

                        @if($pendingSubmissions->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($pendingSubmissions as $sub)
                                    <div class="p-4 rounded-2xl bg-white border border-amber-200 shadow-sm space-y-3">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-bold text-slate-800">{{ $sub->user->name }}</span>
                                            <span class="text-slate-400">{{ $sub->submitted_at?->diffForHumans() }}</span>
                                        </div>

                                        <div class="text-xs text-slate-700 bg-sky-50/60 p-3 rounded-xl border border-sky-100 italic">
                                            "{{ $sub->content }}"
                                        </div>

                                        <!-- Review Form -->
                                        <form action="{{ route('teacher.submissions.review', $sub) }}" method="POST" class="pt-2 border-t border-slate-100 space-y-2">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            
                                            <div class="flex items-center justify-between gap-2">
                                                <input type="text" name="feedback" placeholder="Encouraging feedback..." required class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-medium">
                                                
                                                <div class="flex items-center gap-1">
                                                    <input type="number" name="points_awarded" value="{{ $sub->assignment->points }}" max="{{ $sub->assignment->points }}" min="0" class="w-16 px-2 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-center">
                                                    <span class="text-xs font-bold text-slate-500">pts</span>
                                                </div>
                                            </div>

                                            <button type="submit" class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-sm transition">
                                                ✓ Approve & Credit Points
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 text-center bg-white/60 rounded-2xl border border-dashed border-sky-200 text-slate-500 text-xs font-semibold">
                                🌟 All homework submissions reviewed!
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Award Custom Points Modal -->
            <div x-show="awardModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 backdrop-blur-sm">
                    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-sky-200 space-y-4" @click.away="awardModal = false">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                <span>⭐</span>
                                <span>Award Growth Points</span>
                            </h3>
                            <button type="button" @click="awardModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
                        </div>

                        <form action="{{ route('teacher.points.award') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="user_id" :value="selectedStudent ? selectedStudent.id : ''">

                            <div>
                                <span class="block text-xs font-bold text-slate-500 uppercase mb-1">Student</span>
                                <span class="block text-sm font-black text-slate-800" x-text="selectedStudent ? selectedStudent.name : ''"></span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Points to Award</label>
                                <input type="number" name="points" min="1" max="50" x-model="pointsToAward" class="w-full px-4 py-2 rounded-xl border border-slate-300 font-extrabold text-amber-700">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Recognition Reason</label>
                                <input type="text" name="reason" x-model="awardReason" required class="w-full px-4 py-2 rounded-xl border border-slate-300 font-medium text-xs text-slate-800">
                            </div>

                            <div class="pt-3 border-t flex items-center justify-end gap-2">
                                <button type="button" @click="awardModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600">Cancel</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-amber-950 font-black text-xs shadow-md transition">Award Points ✨</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-layouts.app>
