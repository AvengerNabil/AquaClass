<x-layouts.app :title="$assignment->title . ' — Homework'">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        
        <div class="mb-6">
            <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-sky-700 hover:underline mb-2 inline-block">
                ← Back to Dashboard
            </a>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-extrabold text-white" style="background-color: {{ $assignment->subject?->color ?? '#0284c7' }};">
                            {{ $assignment->subject?->name }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-extrabold bg-amber-100 text-amber-800">
                            +{{ $assignment->points }} Growth Points
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                        {{ $assignment->title }}
                    </h1>
                </div>

                <div class="text-right">
                    <span class="block text-xs font-bold text-slate-500 uppercase">Due Date</span>
                    <span class="text-sm font-extrabold text-slate-800">
                        {{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'Flexible' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            
            <!-- Left 7 cols: Instructions & Submission Form -->
            <div class="md:col-span-8 space-y-6">
                
                <!-- Prompt Instructions -->
                <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Teacher Instructions</h3>
                    <div class="prose text-slate-800 text-sm leading-relaxed font-medium">
                        {{ $assignment->description }}
                    </div>
                </div>

                <!-- Submission Box -->
                <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-md border border-sky-200">
                    <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                        <span>✏️</span>
                        <span>Your Response</span>
                    </h3>

                    @if($submission && $submission->status === 'approved')
                        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 mb-4">
                            <div class="flex items-center gap-2 text-emerald-800 font-black text-sm mb-2">
                                <span>🎉 Homework Approved!</span>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-200 text-emerald-900 text-xs">+{{ $submission->points_awarded }} Points</span>
                            </div>
                            <p class="text-xs text-slate-700 italic font-medium mb-3">
                                "{{ $submission->content }}"
                            </p>
                            @if($submission->feedback)
                                <div class="pt-3 border-t border-emerald-200 text-xs text-emerald-900 font-semibold">
                                    <strong>Teacher Feedback:</strong> {{ $submission->feedback }}
                                </div>
                            @endif
                        </div>
                    @else
                        @if($submission && $submission->status === 'submitted')
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold mb-4 flex items-center gap-2">
                                <span>⏳</span>
                                <span>Submitted on {{ $submission->submitted_at?->format('M d, H:i') }}. Awaiting teacher review! You can update below if needed.</span>
                            </div>
                        @endif

                        <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <textarea name="content" rows="6" required class="w-full p-4 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-slate-800 text-sm" placeholder="Write your sentences or answers here...">{{ old('content', $submission?->content) }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-extrabold text-sm shadow-md shadow-cyan-500/25 hover:scale-[1.01] active:scale-[0.99] transition">
                                {{ $submission ? 'Update My Homework ✨' : 'Submit Homework to Teacher 🚀' }}
                            </button>
                        </form>
                    @endif
                </div>

            </div>

            <!-- Right 4 cols: Info & Fish Motivation -->
            <div class="md:col-span-4 space-y-6">
                
                <div class="ocean-glass rounded-3xl p-6 shadow-md border border-sky-200 text-center">
                    <span class="text-4xl block mb-2">🐠</span>
                    <h4 class="text-base font-extrabold text-slate-800 mb-1">Feed Your Fish!</h4>
                    <p class="text-xs text-slate-600 font-medium mb-4">
                        Completing this assignment earns you <strong class="text-cyan-700 font-bold">+{{ $assignment->points }} growth points</strong> for your aquarium.
                    </p>
                    <div class="p-3 rounded-xl bg-cyan-50 text-cyan-900 text-xs font-bold">
                        Class: {{ $assignment->classroom?->name }}
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-layouts.app>
