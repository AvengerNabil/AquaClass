<x-layouts.app title="Curriculum Standards — AquaClass">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100 text-cyan-800 text-xs font-bold mb-2">
                <span>📚 Academic Framework</span>
                <span>•</span>
                <span>International Primary (Ages 6-9)</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Curriculum Learning Objectives
            </h1>
            <p class="text-sm font-medium text-slate-600 mt-1">
                Explore the aligned standards for Grades 1, 2, and 3 that power AquaClass homework and challenges.
            </p>
        </div>

        <!-- Grade Level & Subject Tabs -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <!-- Grade Tabs -->
            <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-200/60 border border-slate-300/40">
                @foreach($gradeLevels as $g)
                    <a href="{{ route('curriculum.index', ['grade' => $g->name, 'subject' => $selectedSubject]) }}"
                       class="px-4 py-2 rounded-xl text-xs font-black transition {{ $selectedGrade === $g->name ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ $g->display_name }} (Ages {{ $g->min_age }}-{{ $g->max_age }})
                    </a>
                @endforeach
            </div>

            <!-- Subject Filter -->
            <div class="flex items-center gap-2">
                <a href="{{ route('curriculum.index', ['grade' => $selectedGrade, 'subject' => 'all']) }}" 
                   class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition {{ $selectedSubject === 'all' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border' }}">
                    All Subjects
                </a>
                @foreach($subjects as $sub)
                    <a href="{{ route('curriculum.index', ['grade' => $selectedGrade, 'subject' => $sub->slug]) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-extrabold transition {{ $selectedSubject === $sub->slug ? 'text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border' }}"
                       style="{{ $selectedSubject === $sub->slug ? 'background-color: ' . $sub->color : '' }}">
                        {{ $sub->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Objectives Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($objectives as $obj)
                <div class="ocean-glass rounded-3xl p-6 shadow-md border border-sky-200 hover:border-cyan-400 hover:shadow-lg transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black text-white" style="background-color: {{ $obj->subject?->color ?? '#0284c7' }};">
                                {{ $obj->subject?->name }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-slate-100 text-slate-600">
                                {{ $obj->difficulty }}
                            </span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900 mb-2 leading-snug">
                            {{ $obj->topic }}
                        </h3>

                        <p class="text-xs text-slate-600 font-medium leading-relaxed mb-4">
                            {{ $obj->description }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-[11px] font-bold text-slate-400">
                        <span>Target: Ages {{ $obj->min_age }}-{{ $obj->max_age }}</span>
                        <span class="text-cyan-700">✓ Aligned</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full ocean-glass rounded-3xl p-12 text-center text-slate-500">
                    <span class="text-4xl block mb-2">📚</span>
                    <p class="text-base font-bold">No objectives match this filter.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $objectives->links() }}
        </div>

    </div>
</x-layouts.app>
