<x-layouts.app title="Create Assignment — AquaClass">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        
        <div class="mb-6">
            <a href="{{ route('teacher.dashboard') }}" class="text-xs font-bold text-sky-700 hover:underline mb-2 inline-block">
                ← Back to Teacher Desk
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                Create Classroom Assignment
            </h1>
            <p class="text-xs text-slate-500 font-medium">
                Publish a homework task or linked learning challenge to {{ $classroom->name }}
            </p>
        </div>

        <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-xl border border-sky-200">
            <form action="{{ route('teacher.assignments.store', $classroom) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                        Assignment Title
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. My Favorite Ocean Creature" class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-bold text-slate-800">
                </div>

                <!-- Subject & Type -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Subject
                        </label>
                        <select name="subject_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-bold text-slate-800">
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Assignment Type
                        </label>
                        <select name="type" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-bold text-slate-800">
                            <option value="homework">Homework Writing / Reflection</option>
                            <option value="activity">Gamified Activity Challenge</option>
                            <option value="reading">Reading Log & Summary</option>
                            <option value="project">Creative Ocean Project</option>
                        </select>
                    </div>
                </div>

                <!-- Learning Objective Link -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                        Aligned Learning Objective (Grades 1-3)
                    </label>
                    <select name="objective_id" class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-xs text-slate-800">
                        <option value="">-- Optional: Link Curriculum Standard --</option>
                        @foreach($objectives as $obj)
                            <option value="{{ $obj->id }}">{{ $obj->subject->name }}: {{ $obj->topic }} ({{ $obj->difficulty }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Instructions -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                        Student Instructions & Prompts
                    </label>
                    <textarea name="description" rows="5" required placeholder="Write clear, engaging instructions for your young learners..." class="w-full p-4 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">{{ old('description') }}</textarea>
                </div>

                <!-- Points & Due Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Growth Points Reward
                        </label>
                        <input type="number" name="points" value="{{ old('points', 25) }}" min="5" max="100" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-black text-amber-700">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Due Date
                        </label>
                        <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(3)->format('Y-m-d')) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-bold text-slate-800">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="allow_late" value="1" id="allow_late" checked class="w-4 h-4 rounded text-cyan-600 focus:ring-cyan-500">
                    <label for="allow_late" class="text-xs font-bold text-slate-700">Allow flexible late submissions</label>
                </div>

                <div class="pt-4 border-t flex items-center justify-end gap-3">
                    <a href="{{ route('teacher.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                        Cancel
                    </a>
                    <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-black text-sm shadow-md shadow-indigo-500/25 hover:scale-105 transition">
                        Publish Assignment 🚀
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>
