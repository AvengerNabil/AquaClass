<x-layouts.app :title="$classroom->name . ' — Shared Aquarium'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('teacher.dashboard') }}" class="text-xs font-bold text-sky-700 hover:underline mb-1 inline-block">
                    ← Back to Teacher Desk
                </a>
                <h1 class="text-3xl font-black text-slate-900 flex items-center gap-2">
                    <span>🐬</span>
                    <span>{{ $classroom->name }} — Classroom Live Tank</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium">
                    Every student in your class swims together. Their growth points dictate their size and evolution stage!
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-2xl bg-white border border-sky-200 text-cyan-800 text-xs font-black shadow-sm">
                    👥 {{ $students->count() }} Swimmers
                </span>
            </div>
        </div>

        <!-- Fullscreen Tank Canvas -->
        <div class="mb-8">
            <x-aquarium-canvas :fishes="$students->map(fn($s) => $s->fish)->filter()" height="540px" tankTitle="{{ $classroom->name }}" />
        </div>

        <!-- Swimmer Roster Legend Cards -->
        <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
            <h2 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                <span>🐠</span>
                <span>Swimmer Legend & Growth Levels</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($students as $s)
                    <div class="p-4 rounded-2xl bg-white border border-sky-100 flex items-center gap-3 shadow-sm">
                        <div class="w-10 h-10 rounded-2xl bg-sky-100 flex items-center justify-center text-xl">
                            {{ $s->avatar ?? '🐠' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-extrabold text-slate-900 truncate">
                                {{ $s->name }}
                            </h4>
                            <span class="block text-[11px] font-bold text-cyan-700">
                                {{ $s->fish?->name ?? 'Fish' }} • {{ $s->fish?->stage?->display_name ?? 'Tiny Fry' }}
                            </span>
                            <span class="block text-[10px] text-amber-600 font-black">
                                🪙 {{ $s->studentProfile?->total_points ?? 0 }} pts
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-layouts.app>
