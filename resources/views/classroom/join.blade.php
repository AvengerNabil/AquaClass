<x-layouts.app title="Join Classroom — AquaClass">
    <div class="max-w-md mx-auto px-4 py-16">
        
        <div class="ocean-glass rounded-3xl p-8 sm:p-10 shadow-2xl border-2 border-sky-300 text-center">
            
            <div class="w-16 h-16 mx-auto mb-4 rounded-3xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-3xl shadow-lg shadow-cyan-500/25">
                🔑
            </div>

            <h1 class="text-2xl font-black text-slate-900 mb-2">
                Join Your Classroom
            </h1>
            <p class="text-xs text-slate-500 font-medium mb-6">
                Enter the secret code given by your teacher to swim into your classroom fish tank!
            </p>

            <form action="{{ url('/join') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="join_code" value="{{ old('join_code', 'AQUA202') }}" required maxlength="8" placeholder="e.g. AQUA202" class="w-full text-center text-2xl font-black tracking-widest uppercase px-4 py-3 rounded-2xl border-2 border-sky-300 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 text-cyan-800 placeholder:text-slate-300">
                    <span class="block text-[11px] font-bold text-slate-400 mt-2">
                        💡 Demo code: <strong class="text-cyan-700">AQUA202</strong> (Grade 2 Blue Dolphins)
                    </span>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cyan-500 via-sky-600 to-blue-600 text-white font-extrabold text-sm shadow-md shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition">
                    Swim into Classroom 🐬
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200/60 text-xs text-slate-500 font-medium">
                Are you a teacher? <a href="{{ route('demo.switch', 'teacher') }}" class="text-cyan-700 font-bold hover:underline">Open Teacher Desk</a>
            </div>

        </div>

    </div>
</x-layouts.app>
