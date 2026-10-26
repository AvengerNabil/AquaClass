<x-layouts.app title="Learning Activities — AquaClass">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100 text-cyan-800 text-xs font-bold mb-2">
                    <span>🌟 Dynamic Question Bank & Rotation</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Explore Learning Challenges
                </h1>
                <p class="text-sm font-medium text-slate-600 mt-1">
                    Complete 10, 15, or 20 question practice sessions with fresh question rotation every time!
                </p>
            </div>

            <!-- Subject & Grade Filter Controls -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <!-- Subject Filter -->
                <div class="flex flex-wrap items-center gap-1.5 bg-white/80 p-1 rounded-2xl border border-sky-200 shadow-sm text-xs font-bold">
                    <a href="{{ route('activities.index', array_filter(['grade' => $currentGrade])) }}" class="px-3 py-1.5 rounded-xl transition {{ !$currentSubject ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                        All Subjects
                    </a>
                    <a href="{{ route('activities.index', array_filter(['subject' => 'math', 'grade' => $currentGrade])) }}" class="px-3 py-1.5 rounded-xl transition {{ $currentSubject === 'math' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-blue-50' }}">
                        📘 Math
                    </a>
                    <a href="{{ route('activities.index', array_filter(['subject' => 'english', 'grade' => $currentGrade])) }}" class="px-3 py-1.5 rounded-xl transition {{ $currentSubject === 'english' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50' }}">
                        📗 English
                    </a>
                    <a href="{{ route('activities.index', array_filter(['subject' => 'science', 'grade' => $currentGrade])) }}" class="px-3 py-1.5 rounded-xl transition {{ $currentSubject === 'science' ? 'bg-amber-600 text-white shadow-sm' : 'text-slate-600 hover:bg-amber-50' }}">
                        📙 Science
                    </a>
                </div>

                <!-- Grade Filter -->
                <div class="flex flex-wrap items-center gap-1.5 bg-white/80 p-1 rounded-2xl border border-sky-200 shadow-sm text-xs font-bold">
                    <a href="{{ route('activities.index', array_filter(['subject' => $currentSubject])) }}" class="px-3 py-1.5 rounded-xl transition {{ !$currentGrade ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                        All Grades
                    </a>
                    <a href="{{ route('activities.index', array_filter(['grade' => 'grade_1', 'subject' => $currentSubject])) }}" class="px-3 py-1.5 rounded-xl transition {{ $currentGrade === 'grade_1' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-sky-50' }}">
                        Grade 1
                    </a>
                    <a href="{{ route('activities.index', array_filter(['grade' => 'grade_2', 'subject' => $currentSubject])) }}" class="px-3 py-1.5 rounded-xl transition {{ $currentGrade === 'grade_2' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-indigo-50' }}">
                        Grade 2
                    </a>
                    <a href="{{ route('activities.index', array_filter(['grade' => 'grade_3', 'subject' => $currentSubject])) }}" class="px-3 py-1.5 rounded-xl transition {{ $currentGrade === 'grade_3' ? 'bg-purple-600 text-white shadow-sm' : 'text-slate-600 hover:bg-purple-50' }}">
                        Grade 3
                    </a>
                </div>
            </div>
        </div>

        <!-- Activities Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activities as $act)
                <div class="ocean-glass rounded-3xl p-6 shadow-md border border-sky-200 hover:border-cyan-400 hover:shadow-xl transition flex flex-col justify-between group">
                    <div>
                        <!-- Badges Header -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded-xl text-xs font-black text-white shadow-sm" style="background-color: {{ $act->subject?->color ?? '#0284c7' }};">
                                {{ $act->subject?->name }}
                            </span>
                            <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-white text-slate-700 border border-slate-200">
                                {{ $act->gradeLevel?->display_name }}
                            </span>
                        </div>

                        <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-cyan-700 transition leading-snug">
                            {{ $act->title }}
                        </h3>

                        <p class="text-xs text-slate-500 font-medium line-clamp-2 mb-4">
                            {{ $act->instructions }}
                        </p>

                        <!-- Bank & Rotation Info -->
                        <div class="flex items-center gap-2 mb-4 text-[11px] font-bold text-slate-600 bg-white/70 px-3 py-1.5 rounded-xl border border-sky-100">
                            <span class="text-cyan-600 font-black">📚 {{ $act->questions_count ?? $act->questions->count() }} Questions in Bank</span>
                            <span>•</span>
                            <span class="text-emerald-700">🎲 Dynamic Rotation</span>
                        </div>
                    </div>

                    <!-- Card Footer: Quick Session Length Buttons & Play -->
                    <div class="pt-4 border-t border-slate-200/60 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1 text-[11px] font-bold">
                            <span class="text-slate-400">Mode:</span>
                            <a href="{{ route('activities.show', ['activity' => $act, 'length' => 10]) }}" class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-800 hover:bg-sky-200 transition" title="Start 10 questions session">10Q</a>
                            <a href="{{ route('activities.show', ['activity' => $act, 'length' => 15]) }}" class="px-2 py-0.5 rounded-md bg-cyan-100 text-cyan-800 hover:bg-cyan-200 transition" title="Start 15 questions session">15Q</a>
                            <a href="{{ route('activities.show', ['activity' => $act, 'length' => 20]) }}" class="px-2 py-0.5 rounded-md bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition" title="Start 20 questions session">20Q</a>
                        </div>

                        <a href="{{ route('activities.show', ['activity' => $act, 'length' => 10]) }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-xs font-black shadow-md shadow-cyan-500/20 group-hover:scale-105 active:scale-95 transition">
                            Play Now ✨
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full ocean-glass rounded-3xl p-12 text-center text-slate-500">
                    <span class="text-4xl block mb-2">🌊</span>
                    <p class="text-base font-bold">No activities found matching this filter.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
        </div>

    </div>
</x-layouts.app>
