<x-layouts.app :title="$activity->title . ' — AquaClass Activity'">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8" x-data="activityPlayer()">

        <!-- Activity Top Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-700 bg-white/80 hover:bg-white px-3.5 py-2 rounded-xl border border-sky-200 shadow-sm transition">
                <span>←</span>
                <span>All Activities</span>
            </a>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Subject Badge -->
                <span class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm" style="background-color: {{ $activity->subject->color ?? '#0284c7' }};">
                    {{ $activity->subject->name }}
                </span>
                <!-- Grade Badge -->
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-sm">
                    {{ $activity->gradeLevel->display_name }}
                </span>
                <!-- Dynamic Rotation Badge -->
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-gradient-to-r from-teal-50 to-cyan-100 text-teal-800 border border-teal-200 shadow-sm flex items-center gap-1" title="Dynamic Question Rotation active for varied practice">
                    <span>🎲</span>
                    <span>Dynamic Rotation</span>
                </span>
            </div>
        </div>

        <!-- Session Length Selector (when not finished and on first question) -->
        <div class="ocean-glass rounded-2xl p-3.5 mb-6 shadow-sm border border-sky-200 flex flex-wrap items-center justify-between gap-3" x-show="!finished && currentIndex === 0 && Object.keys(selectedAnswers).length === 0">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                <span class="text-base">🎯</span>
                <span>Choose Session Practice Length:</span>
                <span class="text-slate-400 font-normal">({{ $availableTotal ?? 25 }}+ questions in bank)</span>
            </div>

            <div class="flex items-center gap-1.5">
                <a href="{{ route('activities.show', ['activity' => $activity, 'length' => 10]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-black transition {{ ($sessionLength ?? 10) == 10 ? 'bg-cyan-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-sky-50 border border-slate-200' }}">
                    10 Questions
                </a>
                <a href="{{ route('activities.show', ['activity' => $activity, 'length' => 15]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-black transition {{ ($sessionLength ?? 10) == 15 ? 'bg-cyan-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-sky-50 border border-slate-200' }}">
                    15 Questions
                </a>
                <a href="{{ route('activities.show', ['activity' => $activity, 'length' => 20]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-black transition {{ ($sessionLength ?? 10) == 20 ? 'bg-cyan-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-sky-50 border border-slate-200' }}">
                    20 Questions
                </a>
            </div>
        </div>

        <!-- Progress Tracker Bar -->
        <div class="ocean-glass rounded-3xl p-5 mb-8 shadow-md">
            <div class="flex items-center justify-between text-xs font-extrabold text-slate-700 mb-2">
                <div class="flex items-center gap-2">
                    <span class="text-lg">⭐</span>
                    <span x-text="'Question ' + (currentIndex + 1) + ' of ' + questions.length"></span>
                    <span class="text-slate-400 text-[11px] font-semibold" x-text="'(' + Object.keys(selectedAnswers).length + ' answered)'"></span>
                </div>
                <div class="flex items-center gap-1.5 text-cyan-700 font-black">
                    <span>Reward:</span>
                    <span class="px-2 py-0.5 rounded-lg bg-cyan-100 text-cyan-800" x-text="'+' + {{ $activity->max_points }} + ' pts'"></span>
                </div>
            </div>

            <!-- Visual Progress Track -->
            <div class="w-full bg-slate-200/80 rounded-full h-3 overflow-hidden p-0.5">
                <div class="h-full rounded-full bg-gradient-to-r from-cyan-400 to-blue-600 transition-all duration-300"
                     :style="'width: ' + (((currentIndex + 1) / questions.length) * 100) + '%'"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="ocean-glass rounded-3xl p-6 sm:p-10 shadow-xl border-2 border-sky-300/60 relative overflow-hidden" x-show="!finished">
            <template x-for="(q, idx) in questions" :key="q.id">
                <div x-show="currentIndex === idx" x-transition:enter="transition ease-out duration-250 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    
                    <!-- Question Prompt with Text-to-Speech -->
                    <div class="mb-8">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-800 leading-snug" x-text="q.question_text"></h2>
                            <button type="button" @click="speak(q.question_text)" class="shrink-0 p-3 rounded-2xl bg-sky-100 hover:bg-sky-200 text-sky-700 text-lg shadow-sm transition active:scale-95" title="Read Aloud to Me">
                                🔊
                            </button>
                        </div>

                        <!-- Instructions / Hint banner if any -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyan-50 border border-cyan-200 text-cyan-800 text-xs font-bold">
                            <span>💡 Tip:</span>
                            <span>Choose the best answer below to earn stars and feed your fish!</span>
                        </div>
                    </div>

                    <!-- Answer Options Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <template x-for="(opt, oIdx) in q.options" :key="opt.id">
                            <button type="button" 
                                    @click="selectOption(q.id, opt.option_text)"
                                    :disabled="answersSubmitted"
                                    class="p-5 rounded-2xl text-left font-bold text-base sm:text-lg border-2 transition duration-150 flex items-center justify-between group active:scale-98 shadow-sm"
                                    :class="{
                                        'bg-white hover:bg-sky-50/80 border-sky-200 text-slate-800 hover:border-cyan-400': selectedAnswers[q.id] !== opt.option_text,
                                        'bg-cyan-50 border-cyan-500 text-cyan-900 shadow-md ring-2 ring-cyan-400': selectedAnswers[q.id] === opt.option_text && !answersSubmitted,
                                        'bg-emerald-50 border-emerald-500 text-emerald-950 font-black ring-2 ring-emerald-400': answersSubmitted && opt.is_correct,
                                        'bg-rose-50 border-rose-400 text-rose-900 opacity-60': answersSubmitted && selectedAnswers[q.id] === opt.option_text && !opt.is_correct
                                    }">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-cyan-100 flex items-center justify-center text-xs font-black text-slate-600 group-hover:text-cyan-800"
                                          x-text="['A', 'B', 'C', 'D'][oIdx] || (oIdx + 1)"></span>
                                    <span x-text="opt.option_text"></span>
                                </div>
                                
                                <span x-show="selectedAnswers[q.id] === opt.option_text" class="text-xl">
                                    ✨
                                </span>
                            </button>
                        </template>
                    </div>

                    <!-- Bottom Nav / Next Action -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200/60">
                        <button type="button" 
                                @click="currentIndex = Math.max(0, currentIndex - 1)" 
                                :disabled="currentIndex === 0"
                                class="px-5 py-2.5 rounded-xl font-bold text-xs text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 disabled:opacity-30 disabled:pointer-events-none transition">
                            Previous
                        </button>

                        <div class="text-xs font-bold text-slate-400">
                            <span x-text="(currentIndex + 1) + ' / ' + questions.length"></span>
                        </div>

                        <template x-if="currentIndex < questions.length - 1">
                            <button type="button" 
                                    @click="nextQuestion()" 
                                    :disabled="!selectedAnswers[q.id]"
                                    class="px-6 py-3 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 shadow-md shadow-cyan-500/25 hover:shadow-cyan-500/40 disabled:opacity-40 disabled:pointer-events-none transition">
                                Next Question →
                            </button>
                        </template>

                        <template x-if="currentIndex === questions.length - 1">
                            <button type="button" 
                                    @click="submitAll()" 
                                    :disabled="!allAnswered || submitting"
                                    class="px-8 py-3.5 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 shadow-lg shadow-emerald-500/30 hover:scale-105 active:scale-95 disabled:opacity-40 disabled:pointer-events-none transition">
                                <span x-show="!submitting">Submit & Claim Stars 🌟</span>
                                <span x-show="submitting">Feeding Your Fish...</span>
                            </button>
                        </template>
                    </div>

                </div>
            </template>
        </div>

        <!-- Completion Celebration View -->
        <div class="ocean-glass rounded-3xl p-8 sm:p-12 text-center shadow-2xl border-4 border-emerald-400 relative overflow-hidden" x-show="finished" x-cloak>
            
            <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-gradient-to-tr from-amber-400 to-yellow-300 flex items-center justify-center text-5xl shadow-xl shadow-amber-400/30 animate-bounce">
                🏆
            </div>

            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-2">
                Hooray! Practice Session Completed!
            </h2>
            <p class="text-slate-600 font-semibold mb-6">
                You showed amazing curiosity, problem-solving skills, and reef exploration!
            </p>

            <!-- Results Summary Card -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto mb-8">
                <div class="p-4 rounded-2xl bg-white/90 border border-sky-200 shadow-sm">
                    <span class="block text-2xl font-black text-emerald-600" x-text="results.correct_count + ' / ' + results.total_questions"></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Correct</span>
                </div>

                <div class="p-4 rounded-2xl bg-white/90 border border-sky-200 shadow-sm">
                    <span class="block text-2xl font-black text-blue-600" x-text="accuracyPercent + '%'"></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Accuracy</span>
                </div>

                <div class="p-4 rounded-2xl bg-white/90 border border-sky-200 shadow-sm">
                    <span class="block text-2xl font-black text-cyan-600" x-text="'+' + results.points_earned + ' 🪙'"></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Points Earned</span>
                </div>

                <div class="p-4 rounded-2xl bg-white/90 border border-sky-200 shadow-sm">
                    <span class="block text-2xl font-black text-amber-500">🔥 {{ Auth::user()?->studentProfile?->current_streak ?? 1 }}d</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Daily Streak</span>
                </div>
            </div>

            <!-- Stage Upgraded Banner (if applicable) -->
            <div x-show="results.point_result && results.point_result.stage_upgraded" class="p-5 rounded-2xl bg-gradient-to-r from-amber-400 to-pink-500 text-white font-black text-lg mb-8 shadow-lg shadow-pink-500/20 animate-pulse">
                🎉 WOW! Your fish grew into a <span x-text="results.point_result.new_stage"></span>! 🐠
            </div>

            <!-- Guest Encouragement Banner (if playing as guest) -->
            <div x-show="results.is_guest" class="p-5 rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white font-black text-sm mb-8 shadow-lg shadow-sky-500/20">
                🌟 Awesome work! <a href="{{ route('login') }}" class="underline hover:text-amber-200">Sign in with Google</a> or choose Student Demo to save these points and feed your virtual fish!
            </div>

            <!-- Detailed Answer Review Section -->
            <div class="text-left bg-white/80 rounded-2xl p-6 border border-sky-200 mb-8 max-w-2xl mx-auto shadow-sm">
                <div class="flex items-center justify-between mb-4 border-b pb-3">
                    <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                        <span>📝 Question Review & Explanations</span>
                    </h3>
                    <button type="button" @click="showReview = !showReview" class="text-xs font-bold text-cyan-700 hover:text-cyan-900 underline">
                        <span x-text="showReview ? 'Hide Details' : 'Show Details'"></span>
                    </button>
                </div>

                <div x-show="showReview" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <div class="p-4 rounded-xl border transition"
                             :class="results.details && results.details[q.id] && results.details[q.id].correct ? 'bg-emerald-50/70 border-emerald-200' : 'bg-rose-50/70 border-rose-200'">
                            <div class="flex items-start justify-between gap-3 mb-1">
                                <span class="text-xs font-black"
                                      :class="results.details && results.details[q.id] && results.details[q.id].correct ? 'text-emerald-700' : 'text-rose-700'"
                                      x-text="'Q' + (idx + 1) + ': ' + (results.details && results.details[q.id] && results.details[q.id].correct ? '✓ Correct' : '✗ Needs Practice')">
                                </span>
                            </div>
                            <p class="text-xs font-bold text-slate-800 mb-2" x-text="q.question_text"></p>
                            
                            <div class="text-xs space-y-1 mb-2">
                                <div>
                                    <span class="font-semibold text-slate-500">Your Answer:</span>
                                    <span class="font-bold" :class="results.details && results.details[q.id] && results.details[q.id].correct ? 'text-emerald-800' : 'text-rose-800'" x-text="selectedAnswers[q.id]"></span>
                                </div>
                                <div x-show="results.details && results.details[q.id] && !results.details[q.id].correct">
                                    <span class="font-semibold text-slate-500">Correct Answer:</span>
                                    <span class="font-bold text-emerald-800" x-text="results.details && results.details[q.id] ? results.details[q.id].correct_answer : ''"></span>
                                </div>
                            </div>

                            <p class="text-[11px] font-medium text-slate-600 bg-white/70 p-2 rounded-lg" x-text="'💡 ' + (results.details && results.details[q.id] ? results.details[q.id].explanation : '')"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Action Buttons: Play Again (with Fresh Questions), Visit Aquarium, Explore -->
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('activities.show', ['activity' => $activity, 'length' => $sessionLength ?? 10]) }}"
                   class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-extrabold text-sm shadow-md shadow-emerald-500/25 hover:scale-105 active:scale-95 transition flex items-center gap-2">
                    <span>🔄</span>
                    <span>Play Again (Fresh Questions)</span>
                </a>

                <a href="{{ route('student.aquarium') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-extrabold text-sm shadow-md shadow-cyan-500/25 hover:scale-105 transition">
                    🌊 Visit My Aquarium
                </a>
                <a href="{{ route('activities.index') }}" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-extrabold text-sm shadow-sm transition">
                    Explore Next Challenge →
                </a>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    function activityPlayer() {
        return {
            questions: @json($sessionQuestions ?? $activity->questions),
            sessionToken: '{{ $quizSession->session_token ?? "" }}',
            currentIndex: 0,
            selectedAnswers: {},
            submitting: false,
            answersSubmitted: false,
            finished: false,
            results: {},
            showReview: true,

            get allAnswered() {
                return this.questions.length > 0 && this.questions.every(q => !!this.selectedAnswers[q.id]);
            },

            get accuracyPercent() {
                if (!this.results.total_questions) return 0;
                return Math.round((this.results.correct_count / this.results.total_questions) * 100);
            },

            selectOption(questionId, text) {
                if (this.answersSubmitted) return;
                this.selectedAnswers[questionId] = text;
                if (window.AquaAudio) window.AquaAudio.playChime('pop');
            },

            nextQuestion() {
                if (this.currentIndex < this.questions.length - 1) {
                    this.currentIndex++;
                    if (window.AquaAudio) window.AquaAudio.playChime('pop');
                }
            },

            speak(text) {
                if (window.AquaAudio) window.AquaAudio.speakText(text);
            },

            async submitAll() {
                this.submitting = true;
                try {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                    const res = await fetch("{{ route('activities.submit', $activity) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            session_token: this.sessionToken,
                            answers: this.selectedAnswers
                        })
                    });

                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        throw new Error(err.message || 'Submission failed (' + res.status + ')');
                    }

                    const data = await res.json();
                    this.results = data;
                    this.finished = true;
                    this.answersSubmitted = true;
                    if (window.AquaAudio) window.AquaAudio.playChime('cheer');
                } catch (e) {
                    console.error('Quiz submission error:', e);
                    alert('Could not submit answers: ' + (e.message || 'Please try again.'));
                } finally {
                    this.submitting = false;
                }
            }
        };
    }
    </script>
    @endpush
</x-layouts.app>
